<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegisterType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/settings/{id}",name="profile_settings")
     */
    public function index($id,User $user,Request $request){

        $form = $this->createForm(UserRegisterType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $this->em->flush();
            return $this->redirectToRoute('profile_settings',['id'=>$user->getId()]);
        }
        return $this->render('account/setting.html.twig',['form'=>$form->createView()]);
    }


}
