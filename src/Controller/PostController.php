<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class PostController extends AbstractController
{

    private $postRepo;

    private $em;
    private $commentRepo;


    public function __construct(PostRepository $postRepo,EntityManagerInterface $em,CommentRepository $commentRepo)
    {
        $this->postRepo =$postRepo;
        $this->em = $em;
        $this->commentRepo =$commentRepo;

    }





    /**
     * @Route("/post/{id}",name="show_post")
     */
    public function show($id){
        $post = $this->postRepo->find($id);
        if(!$post){
            throw $this->createNotFoundException('Not Post found');
        }
        $countComments = $this->commentRepo->countPostComments($id);

        return $this->render('post/single_post.html.twig',[
            'post'=>$post,
            'nbComments'=>$countComments
            ]);

    }


    /**
     * @Route("/post/edit/{id}",name="user_post_edit")
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


    /**
    * @Route("/post/delete/{id}",name="user_post_delete")
    */
    public function delete($id){
        $post = $this->postRepo->find($id);

        if (!$post) {
            return $this->redirectToRoute('home');
        }

        $this->em->remove($post);
        $this->em->flush();
        $this->addFlash('success','post deleted');
        return $this->redirectToRoute('home');

    }


}
