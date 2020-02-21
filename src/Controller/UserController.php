<?php


namespace App\Controller;


use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Form\UserRegisterType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;


class UserController extends AbstractController
{
    /*
     *
     */
    private $postRepo;
    private $userRepo;
    private $objectManager;
    private $encoder;


    public function __construct(UserRepository $userRepo,PostRepository $postRepo,UserPasswordEncoderInterface $encoder,EntityManagerInterface $objectManager)
    {
        $this->userRepo =$userRepo;
        $this->postRepo =$postRepo;
        $this->objectManager = $objectManager;
        $this->encoder = $encoder;
    }
    /**
     * @Route("/register",name="register")
     */
    public function register(Request $req,GuardAuthenticatorHandler $guardhandler,LoginFormAuthenticator $formAuthenticator){

            $form = $this->createForm(UserRegisterType::class);
            $form->handleRequest($req);
            if($req->isMethod('POST'))
            {

                if($form->isValid() && $form->isSubmitted()){
                    $user = $form->getData();
                    $user->setPassword($this->encoder->encodePassword(
                        $user,
                        $user->getPassword()
                    ));
                    $this->objectManager->persist($user);
                    $this->objectManager->flush();
                    return $guardhandler->authenticateUserAndHandleSuccess(
                        $user,
                        $req,
                        $formAuthenticator,
                        'main'
                    );
                }
            }

            return $this->render('User/register.html.twig',['form'=>
            $form->createView()
            ]);
    }

    /**
     * @Route("/login",name="login")
     */
    public function login(AuthenticationUtils $auth){
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $username = $auth->getLastUsername();
        $error = $auth->getLastAuthenticationError();
        if($this->isGranted('ROLE_USER')){
            return $this->redirectToRoute('home');
        }else{

            return $this->render('User/login.html.twig',
                ['username' => $username,
                    'error' => $error
                ]);
        }
        }

    /**
     * @Route("logout",name="logout")
     */
    public function logout(){
        throw new \Exception('logout');

    }

    /**
     * @Route("/user/{id}",name="user_profil")
     */
    public function index($id){
      if($this->isGranted('ROLE_USER')) // user authentificated
      {

        $users = $this->userRepo->findAll();
        $loggedUser = $this->getUser();
        $currentUser = $this->userRepo->find($id);
        $last_posts = $this->postRepo->findLastPosts(10);
        $posts = $this->postRepo->findAllUserPost($id);

        return $this->render('account/index.html.twig',['last_posts'=>$last_posts,'posts'=>$posts,'users'=>$users,'currentUser' =>$currentUser,'loggedUser' =>$loggedUser]);
      }else{
        return $this->redirectToRoute('login');
      }
    }




    /*
    * @Route(name="user_post_edit")
    */
    public function edit(Post $post,Request $req){
        $form = $this->createForm(PostType::class,null);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success','post updated');
            return $this->redirectToRoute('user_profil');
        }


    }

    /*
   * @Route(name="user_post_delete",method="DELETE")
   */
    public function delete(Post $post,Request $req){
        if($this->isCsrfTokenValid('Delete',$post->getId(),$req->get('token'))){
            $this->em->remove();
            $this->em->flush();
            $this->addFlash('success','post deleted');
            return $this->redirectToRoute('user_profil');
        }

    }





}
