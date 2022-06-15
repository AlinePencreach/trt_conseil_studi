<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\User;

use App\Form\Annonce1Type;
use App\Repository\AnnonceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/annonce')]
class AnnonceController extends AbstractController
{
    //Affiche toutes les annonces
    #[Route('/', name: 'app_annonce_index', methods: ['GET'])]
    public function index(AnnonceRepository $annonceRepository, PaginatorInterface $paginator, Request $request): Response
    {
        
        $annonces = $paginator->paginate(

            $annonceRepository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces,

        ]);

    }

    // affiche les annonce du recruteur connecté
    #[Route('/mes_annonces', name: 'app_annonce_user', methods: ['GET'])]
    public function indexUser(AnnonceRepository $annonceRepository, PaginatorInterface $paginator, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $annonces = $paginator->paginate(

            $annonceRepository->findByUser($user),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('annonce/user.html.twig', [
            'annonces' => $annonces,

        ]);

    }

    //affiche les annonce a valider par les consultants
    #[Route('/consultant', name: 'app_annonce_consultant', methods: ['GET'])]
    public function consultant(AnnonceRepository $annonceRepository, PaginatorInterface $paginator, Request $request): Response
    {
        
        $annonces = $paginator->paginate(

            $annonceRepository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('annonce/consultant.html.twig', [
            'annonces' => $annonces,

        ]);

    }


    #[Route('/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnnonceRepository $annonceRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $annonce = new Annonce();
        $annonce->setAuteur($user);
        
        $form = $this->createForm(Annonce1Type::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->add($annonce, true);
            
            
           

            $this->addFlash(
                'success',
                'Votre annonce à bien été prise en compte. Un consultant va la valider dans les plus bref délais.'
            );

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
            
        ]);
    }

    //MONTRE L'ANNONCE via l'id EN DÉTAIL
    #[Route('/{id}', name: 'app_annonce_show', methods: ['GET'])]
    public function show(Annonce $annonce): Response
    {
         //       /** @var User $user */
        //  $user = $this->getUser();
        //  if ($user = $annonce->getAuteur()) {
        //      # code...
        //      $candidatures = $paginator->paginate(
                 
        //          $repository->findByCandidature($annonce),
        //          $request->query->getInt('page', 1), /*page number*/
        //          7 /*limit per page*/
        //         );
                
        //     }



        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

  
//permet modifier une annonce
    #[Route('/{id}/edit', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        $form = $this->createForm(Annonce1Type::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->add($annonce, true);

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }


    //supprime une annonce
    #[Route('/{id}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $annonce->getId(), $request->request->get('_token'))) {
            $annonceRepository->remove($annonce, true);
        }

        return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/makeItValide/{page}/{id}', name: 'app_annonce_valide', methods: ['GET', 'POST'])]
    public function makeItValide($page, $id, Request $request, AnnonceRepository $annonceRepository): Response
    {
        $annonce = $annonceRepository->find($id);
        if ($annonce->isValide()) {
            $annonce->setValide(false);
        } else {
            $annonce->setValide(true);
        }

        $annonceRepository->add($annonce, true);

        $this->addFlash(
            'success',
            'L\'annonce à bien été valider'
        );


        return $this->redirectToRoute('app_annonce_index', [
            'page' => $page,
        ], Response::HTTP_SEE_OTHER);
    }

   

}
