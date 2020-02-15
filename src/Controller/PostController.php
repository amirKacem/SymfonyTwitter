<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

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

    /**
     * @Route("api/posts2",name="posts")
     */
    public function posts(SerializerInterface $serializer){
        $posts = $this->repositry->findAll();
        $po=$serializer->serialize($posts,'json',
            ['ignored_attributes' => ['no']]);

        return new JsonResponse($po,200,['Content-Type:application/json'],true);
    }


}
