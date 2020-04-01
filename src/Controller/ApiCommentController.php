<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;




class ApiCommentController extends AbstractController
{
    private $serializer;
    private $postRepo;
    private $commentRepo;
    private $em;

    public function __construct(SerializerInterface $serializer,EntityManagerInterface $em ,PostRepository $postRepo, CommentRepository $commentRepo)
    {
        $this->serializer = $serializer;
        $this->postRepo = $postRepo;
        $this->commentRepo = $commentRepo;
        $this->em= $em;


    }

    /**
     * @Route("/api/comment/add", name="add_comment")
     */
    public function addComment(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $comment = new Comment();
            $data = json_decode($request->getContent());
            $post = $this->postRepo->find($data->post);
            $comment->setPost($post);
            $comment->setCommentBy($this->getUser());
            $comment->setContent($data->content);
            $date = new \DateTime('now');
            $comment->setCreatedat($date);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            $encoders = new JsonEncoder();

            $defaultContext = [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                },
                AbstractNormalizer::IGNORED_ATTRIBUTES => [ 'post', 'password','profiles','comments'],
                AbstractNormalizer::OBJECT_TO_POPULATE => $comment
            ];
            $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
            $serializer = new Serializer([$normalizer], [$encoders]);

            $comment = $serializer->serialize($comment,
                'json'
            );
            return $this->json($comment, Response::HTTP_OK);
        }
        return new Response('Failed', 404);
    }


    /**
     * @Route("/api/comment/remove", name="remove_comment")
     */
    public function removeComment(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $data = json_decode($request->getContent());
            $comment = $this->commentRepo->find($data->id);
            $this->em->remove($comment);
            $this->em->flush();
            return $this->json('deleted', Response::HTTP_OK);
        }
        return new Response('Failed', 404);
    }
}