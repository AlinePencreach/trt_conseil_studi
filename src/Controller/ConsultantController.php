<?php

namespace App\Controller;

use App\Entity\Consultant;
use App\Repository\ConsultantRepository;
use App\Form\ConsultantType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ConsultantController extends AbstractController
{
    /**
     * This controller display all consultants
     * 
     * READ
     *
     * @param ConsultantRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/consultant', name: 'app_consultant')]
    public function index(ConsultantRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $consultants = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            7 /*limit per page*/
        );

        return $this->render('consultant/index.html.twig', [
            'controller_name' => 'Page Consultant',
            'consultants' => $consultants,
        ]);
    }


    /**
     * this controller display form consultant to add a new consultant
     * 
     * CREATE
     *
     * @return Response
     */
    #[Route('/new_consultant', name: 'app_new_consultant', methods: ['GET', 'POST'])]
    public function form(Request $request, EntityManagerInterface $manager): Response
    {
        $consultant = new Consultant();
        $consultant->setName('');
        $consultant->setEmail('');
        $consultant->setPassword('');
        $form = $this->createForm(ConsultantType::class, $consultant);
        // $user->setRoles(['ROLE_USER']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $consultant = $form->getData();

            // ... perform some action, such as saving the task to the database
            $manager->persist($consultant);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le nouveau consultant a bien été ajouté.'
            );

            return $this->redirectToRoute('app_consultant');
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
    public function edit(Request $request, Consultant $consultant, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(ConsultantType::class, $consultant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $consultant = $form->getData();

            // ... perform some action, such as saving the task to the database
            $manager->persist($consultant);
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
    public function delete(EntityManagerInterface $manager, Consultant $consultant): Response
    {
        $manager->remove($consultant);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le consultant a bien été supprimé.'
        );


        return $this->redirectToRoute('app_consultant');
    }
}
