<?php
namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
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


    public function __construct(PostRepository $repositry)
    {
        $this->repositry = $repositry;

    }


    /**
     * @Route("/", name="home",methods={"GET"})
     */
    public function index(Request $req){
        $posts = $this->repositry->findAll();


            $post = new post();
            $form = $this->createForm(PostType::class,null);
            $form->handleRequest($req);
            if($form->isSubmitted() && $form->isValid()){
                $this->persist($post);
                $this->em->flush();
                $this->addFlash('success','post added');
            }



        return $this->render('Home/home.html.twig',['posts'=> $posts,'form'=>$form->createView()]);

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