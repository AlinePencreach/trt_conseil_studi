<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ConsultantType;
use App\Form\RegistrationUserType;
use App\Repository\UserRepository;
use App\Form\RegistrationRecruteurType;


use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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



    #[Route('/registration/candidat', name: 'app_security_registration_candidat', methods: ['GET', 'POST'])]
    /**
     * Registration new candidat
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationUserType::class, $user);


        $user->setRoles(['ROLE_USER']);



        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
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


    #[Route('/registration/recruteur', name: 'app_security_registration_recruteur', methods: ['GET', 'POST'])]
    /**
     * Registration new recruteur
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    public function registrationRecruteur(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationRecruteurType::class, $user);


        $user->setRoles(['ROLE_RECRUTEUR']);


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


    /**
     * This controller display all consultants
     * 
     * READ
     *
     * @param UserRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/liste/{role}', name: 'app_consultant')]
    public function indexRoles($role, UserRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $paginator->paginate(

            $repository->findByRoles($role),
            $request->query->getInt('page', 1), /*page number*/
            7 /*limit per page*/
        );


        return $this->render('consultant/index.html.twig', [
            'controller_name' => 'Page Consultant',
            'user' => $user,
        ]);
    }




    #[Route('/new_consultant', name: 'app_new_consultant', methods: ['GET', 'POST'])]
    /**
     * Registration new Consultant by ADMIN
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function registrationConsultant(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(ConsultantType::class, $user);
        $user->setRoles(['ROLE_CONSULTANT']);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            // ... perform some action, such as saving the task to the database
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le nouveau consultant a bien été ajouté.'
            );

            return $this->redirectToRoute('app_home');
        }


        return $this->renderForm('consultant/new.html.twig', [
            'form' => $form,
        ]);
    }


    /**
     * this controller update consultants by id
     *
     * UPDATE
     */
    #[Route('/consultant/edition/{id}', name: 'consultant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(ConsultantType::class, $user);

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
                'Le consultant a bien été modifé.'
            );

            return $this->redirectToRoute('app_consultant');
        }

        return $this->renderForm('consultant/edit.html.twig', [
            'form' => $form,
        ]);
    }


    /**
     * 
     * this controller delete consultant
     * ne pas oublier de mettre la route en path sur les boutons en vue 
     * href="{{ path('consultant_remove', {id: consultant.id}) }}"
     * 
     * DELETE
     */
    #[Route('/consultant/suppression/{id}', name: 'consultant_delete')]
    public function delete(EntityManagerInterface $manager, User $user): Response
    {
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le consultant a bien été supprimé.'
        );


        return $this->redirectToRoute('app_consultant');
    }
}
