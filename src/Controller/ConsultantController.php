<?php

namespace App\Controller;

use App\Entity\Consultant;
use App\Repository\ConsultantRepository;
use App\Form\ConsultantType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ConsultantController extends AbstractController
{
    /**
     * This controller display all consultants
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
            'controller_name' => 'ConsultantController',
            'consultants' => $consultants,
        ]);
    }


    /**
     * this controller display form consultant to add a new consultant
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
                'Le consultant a bien été modiifé.'
            );

            return $this->redirectToRoute('app_consultant');
        }

        return $this->renderForm('consultant/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
