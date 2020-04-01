<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class PostController extends AbstractController
{

    private $postRepo;

    private $em;
    private $commentRepo;
    private $userRepo;


    public function __construct(PostRepository $postRepo,UserRepository $userRepo,EntityManagerInterface $em,CommentRepository $commentRepo)
    {
        $this->postRepo =$postRepo;
        $this->em = $em;
        $this->commentRepo =$commentRepo;
        $this->userRepo =$userRepo;


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
        $lastUsers=$this->userRepo->findLastUsersRegsitred(10);
        return $this->render('post/single_post.html.twig',[
            'post'=> $post,
            'nbComments'=> $countComments,
            'lastUsers'=> $lastUsers
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
