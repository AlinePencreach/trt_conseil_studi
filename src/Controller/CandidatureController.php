<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\Candidature;
use App\Repository\AnnonceRepository;
use App\Repository\CandidatureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CandidatureController extends AbstractController
{
    #[Route('/candidature/candidat/', name: 'app_candidature')]
    public function index(AnnonceRepository $annonceRepository, CandidatureRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {

        /** @var User $user */
        $user = $this->getUser();
        $candidatures = $paginator->paginate(

            $repository->findByUser($user),
            $request->query->getInt('page', 1), /*page number*/
            7 /*limit per page*/
        );

        return $this->render('candidature/candidat.html.twig', [
            'candidatures' => $candidatures,
            'annonces' => $annonceRepository,
        ]);
    }

    #[Route('/candidature/recruteur/{annonce}', name: 'app_recruteur_candidature')]
    public function indexrecruteur(Annonce $annonce, CandidatureRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        
        $candidatures = $paginator->paginate(

            $repository->findByAnnonce($annonce),
            $request->query->getInt('page', 1), /*page number*/
            7 /*limit per page*/
        );

        return $this->render('candidature/recruteur.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }

    #[Route('/candidature/new', name: 'app_candidature_new', methods: ['GET', 'POST'])]
    public function newCandidature(CandidatureRepository $candidatureRepository, Annonce $annonce): Response
    {
         /** @var User $user */
         $user = $this->getUser();
         $candidature = new Candidature();
         $candidature->setCandidatId($user)

         /** @var Annonce $annonce */
                    ->setAnnonceId($annonce);

    
       {
            $candidatureRepository->add($candidature, true);
            
            $this->addFlash(
                'success',
                'Votre candidature à bien été prise en compte. Un consultant doit maintenant la valider'
            );

            return $this->redirectToRoute('app_annonce_index', [
                'candidature' => $candidature, 
            ], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/candidature/makeItValide/{page}/{id}', name: 'app_candidature_valide', methods: ['GET', 'POST'])]
    public function makeItValide($page, $id, CandidatureRepository $candidatureRepository): Response
    {
        $candidature = $candidatureRepository->find($id);
        if ($candidature->isValide()) {
            $candidature->setValide(false);
        } else {
            $candidature->setValide(true);
        }

        $candidatureRepository->add($candidature, true);

        $this->addFlash(
            'success',
            'La candidature à bien été valider'
        );


        return $this->redirectToRoute('app_annonce_index', [
            'page' => $page,
        ], Response::HTTP_SEE_OTHER);
    }

}
