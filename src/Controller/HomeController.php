<?php
namespace App\Controller;

use App\Entity\Post;
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
    public function index(Request $req){
        $posts = $this->repositry->findAll();
        $last_posts = $posts = $this->repositry->findLastPosts(10);

        $form = $this->createForm(PostType::class);

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
            'last_posts'=>$last_posts

        ]);

    }

    /**
     * @Route("/post/{id}",name="show_post")
     */
    public function show($id){
        $post = $this->repositry->find($id);
        return $this->render('post/single_post.html.twig',['post'=>$post]);

    }



    public function addPost(){
        $post = new Post();
        $post->setTitle('First Post')
            ->setDescription('Description')
            ->setUserCreated(1);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($post);
        $manager->flush();
    }
}