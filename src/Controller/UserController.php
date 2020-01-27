<?php


namespace App\Controller;


use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /*
     *
     */
    private $postRepo;
    private  $em;


    public function __construct(PostRepository $postRepo,UserPasswordEncoderInterface $encoder)
    {
        $this->postRepo =$postRepo;
        $this->em = $this->getDoctrine()->getManager();
        $this->encoder = $encoder;

    }

    public function Inscription(){
            $user = new User();
            $user->setUsername();
            $user->setPassword($this->encoder->encodePassword($user,'password'));
            $this->em->persist($user);
            $this->em->flush();
    }

    /**
     * @Route("/login",name="login")
     */
    public function login(AuthenticationUtils $auth){
        $username = $auth->getLastUsername();
        return $this->render('User/login.html.twig',
            ['username'=>$username,
                'error'=>$auth->getLastUsername()
                ]);
    }

    /*
     * @Route("/user/{username}",name="user_profil")
     */
    public function index(){
        $posts = $this->postRepo->findAll();
        return $this->render('User/index.html.twig',['posts'=>$posts]);
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