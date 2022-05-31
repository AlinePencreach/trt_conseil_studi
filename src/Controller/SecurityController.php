<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/connexion', name: 'app_security_login', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('security/login.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route('/déconnexion', name: 'app_security_logout')]
    public function logout()
    {
        //auto with symfony
    }

    #[Route('/registration', name: 'app_security_registration', methods: ['GET', 'POST'])]
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        // $user->setName('');
        // $user->setEmail('');
        // // $user->setPassword('');
        // $plaintextPassword = 'password';
        $user->setRoles(['ROLE_USER']);

        // $hashedPassword = $passwordHasher->hashPassword(
        //     $user,
        //     $plaintextPassword
        // );
        // $user->setPassword($hashedPassword);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();

            // ... perform some action, such as saving the task to the database
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Vous avez bien été enregisté'
            );

            return $this->redirectToRoute('app_home');
        }


        return $this->renderForm('security/registration.html.twig', [
            'form' => $form,
        ]);
    }
}
