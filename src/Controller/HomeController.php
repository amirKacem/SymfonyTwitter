<?php
namespace App\Controller;

use App\Entity\Post;
use App\Entity\Profile;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\PostController;
class HomeController extends AbstractController
{
    /**
     * @var PostRepository
     */
    private $repositry;

    private $em;


    public function __construct(PostRepository $repositry,EntityManagerInterface $em)
    {
        $this->repositry = $repositry;
        $this->em = $em;

    }


    /**
     * @Route("/", name="home")
     */
    public function index(Request $req)
    {
      if($this->isGranted('ROLE_USER')) // user authentificated
      {

        $posts = $this->repositry->findAll();
        $last_posts = $posts = $this->repositry->findLastPosts(10);
        $form = $this->createForm(PostType::class);
        $user = $this->getUser();


        if ($req->isMethod('POST')) {
            $form->handleRequest($req);

            if ($form->isSubmitted() && $form->isValid()) {
                $post = $form->getData();
                $post->setCreatedBy($this->getUser());
                $this->em->persist($post);
                $this->em->flush();
                $this->addFlash('success', 'post added');
                return $this->redirectToRoute('home');
            }
        }


        return $this->render('Home/home.html.twig',[
            'posts'=> $posts,
            'form'=>$form->createView(),
            'last_posts'=> $last_posts,
            'user'=> $user

        ]);
      }else{
        return $this->redirectToRoute('login');
      }

    }




}
