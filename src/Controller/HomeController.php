<?php
namespace App\Controller;

use App\Entity\Post;
use App\Entity\Profile;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\PostController;
use Symfony\Component\Validator\Constraints\DateTime;

class HomeController extends AbstractController
{
    /**
     * @var PostRepository
     */
    private $repositry;

    private $em;

    private $userRepo;
    private $commentRepo;


    public function __construct(PostRepository $repositry,CommentRepository $commentRepo,EntityManagerInterface $em,UserRepository $userRepo)
    {
        $this->repositry = $repositry;
        $this->em = $em;
        $this->userRepo = $userRepo;
        $this->commentRepo =$commentRepo;

    }


    /**
     * @Route("/", name="home")
     */
    public function index(Request $req)
    {
      if($this->isGranted('ROLE_USER')) // user authentificated
      {
        $users = $this->userRepo->findAllLimit(10);
        $last_posts = $posts = $this->repositry->findLastPosts(10);
        $form = $this->createForm(PostType::class);
        $user = $this->getUser();


        if ($req->isMethod('POST')) {
            $form->handleRequest($req);

            if ($form->isSubmitted() && $form->isValid()) {
                $post = $form->getData();
                $post->setCreatedBy($this->getUser());
                $date = new \DateTime();
                $post->setCreatedAt($date);
                $this->em->persist($post);
                $this->em->flush();
                $this->addFlash('success', 'post added');
                return $this->redirectToRoute('home');
            }
        }

        $lastUsersRegitred=$this->userRepo->findLastUsersRegsitred(3);
        return $this->render('Home/home.html.twig',[
            'posts'=> $posts,
            'form'=>$form->createView(),
            'last_posts'=> $last_posts,
            'user'=> $user,
            'users'=> $users,
            'lastUsersRegitred'=>$lastUsersRegitred
        ]);
      }else{
        return $this->redirectToRoute('login');
      }

    }




}
