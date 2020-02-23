<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    private $postrepositry;
    private $em;

    public function __construct(PostRepository $postrepositry,EntityManagerInterface $em)
    {
        $this->postrepositry = $postrepositry;
        $this->em = $em;

    }


}
