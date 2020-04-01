<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfilesController extends AbstractController
{

    private $userRepo;


    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo =$userRepo;

    }

    /**
     * @Route("/profiles", name="profiles")
     */

    public function index()
    {   $users = $this->userRepo->findAll();
        return $this->render('profiles/profiles.html.twig', [
            'users' => $users,
        ]);
    }
}
