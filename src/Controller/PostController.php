<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{

    private $repositry;

    private $em;


    public function __construct(PostRepository $repositry)
    {
        $this->repositry = $repositry;

    }

    /**
     * @Route("/post", name="post_show")
     */
    public function index($slug)
    {
        $post = $this->repositry->find($slug);
        return $this->render('post/index.html.twig',['post'=>$post]);
    }


}
