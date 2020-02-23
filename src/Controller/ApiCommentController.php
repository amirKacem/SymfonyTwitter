<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;



class ApiCommentController extends AbstractController
{
    private $serializer;
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/comment/add", name="api_add", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    /*
    public function addArticle(Request $request,GetResponseForExceptionEvent $event)
    {     dd($request->getContent());
        if($request->isXmlHttpRequest()) {

            $comment = new Comment();
            $data = json_decode($request->getContent());
            $encoders = [
                new JsonEncoder()
            ];

            $normalizers = [
                (new ObjectNormalizer())
                    ->setCircularReferenceHandler(function ($object)
                    {
                        return $object->getName();
                    })
                    ->setIgnoredAttributes([
                        'product'
                    ])
            ];

            $comment->setContent($data->content);
            $comment->setCommentBy($data->comment_by);
            $comment->setPost($data->post);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            return new Response('ok', 201);
        }
        return new Response('Failed', 404);
    }*/

}
