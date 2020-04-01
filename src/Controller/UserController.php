<?php


namespace App\Controller;



use App\Entity\Profile;
use App\Form\UserRegisterType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\Uploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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


    public function __construct(UserRepository $userRepo,
                                PostRepository $postRepo,UserPasswordEncoderInterface $encoder,
                                EntityManagerInterface $objectManager,
                                EntityManagerInterface $em
)
    {
        $this->userRepo =$userRepo;
        $this->postRepo =$postRepo;
        $this->objectManager = $objectManager;
        $this->encoder = $encoder;
        $this->em = $em;

    }
    /**
     * @Route("/register",name="register")
     */
    public function register(Request $req,GuardAuthenticatorHandler $guardhandler,LoginFormAuthenticator $formAuthenticator,Uploader $upload){

            $form = $this->createForm(UserRegisterType::class);
            $form->handleRequest($req);
            if($req->isMethod('POST'))
            {

                if($form->isValid() && $form->isSubmitted()){

                    $user = $form->getData();
                    $profile = new Profile();
                    $profileImage = $form['Profile']['profil_img']->getData();
                    $couverture = $form['Profile']['couverture_img']->getData();
                    $image = $upload->uploadImage($profileImage);
                    if($image){
                        $profile->setProfilImg($image);
                    }
                    $imageCouv = $upload->uploadImage($couverture);
                    if($imageCouv){
                        $profile->setCouvertureImg($imageCouv);
                    }
                    $profile->setPays($form['Profile']['pays']->getData());
                    $profile->setDescription($form['Profile']['description']->getData());
                    $profile->setShortPresentation($form['Profile']['short_presentation']->getData());
                    $profile->setId($user->getId());
                    $user->setPassword($this->encoder->encodePassword(
                        $user,
                        $user->getPassword()
                    ));
                    $user->setProfile($profile);
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

        $users = $this->userRepo->findAllLimit(10);

        $currentUser = $this->userRepo->find($id);
        $last_posts = $this->postRepo->findLastPosts(10);
        $posts = $this->postRepo->findAllUserPost($id);
        $lastUsersRegitred=$this->userRepo->findLastUsersRegsitred(3);

        return $this->render('account/index.html.twig',
            ['last_posts'=>$last_posts,'posts'=>$posts,
              'users'=>$users,'currentUser' =>$currentUser,'user' =>$currentUser,
              'lastUsersRegitred'=>$lastUsersRegitred
            ]);
      }else{
        return $this->redirectToRoute('login');
      }
    }

    /**
     * @Route("/api/follow/add", name="api_add", methods={"POST"})
     */
    public function addFollower(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $data = json_decode($request->getContent());
            $id = $data->id;
            $user = $this->getUser();
               $userFollow =  $this->userRepo->find($id);
            $userFollow->addFollower($user);
            $this->em->flush();




            return $this->json('follwed');
        }
        return new Response('Failed', 404);
    }




}
