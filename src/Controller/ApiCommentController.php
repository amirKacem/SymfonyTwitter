<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiCommentController extends AbstractController
{
    private $serializer;
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }


}
