<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    #[Route('/profil/edition/{id}', name: 'app_profil_edit', methods: ['GET', 'POST'])]
    public function edit(User $user, Request $request, EntityManagerInterface $manager, UserRepository $repository): Response
    {
        $repository->findBy('id');
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest(($request));
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_security_login');
        }

        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_security_logout');
        }

        
        if ($form->isSubmitted() && $form->isValid()) {
           $user = $form->getData();
           $manager->persist($user);
           $manager->flush();

           $this->addFlash(
               'success',
               'Votre profil a bien été mis à jour. '
           );

           return $this->redirectToRoute('app_profil');
           
        }

        return $this->renderForm('profil/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
