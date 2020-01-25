<?php
namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
    * @Route("/", methods={"GET"})
    */
    public function index(){
        $posts = $this->repositry->findAll();

        return $this->render('Home/home.html.twig',['posts'=> $posts]);
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