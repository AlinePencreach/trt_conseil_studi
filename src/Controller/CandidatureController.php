<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Annonce;
use App\Repository\CandidatureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CandidatureController extends AbstractController
{
    #[Route('/candidature/candidat/', name: 'app_candidature')]
    public function index(CandidatureRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {

        /** @var User $user */
        $user = $this->getUser();
        $candidatures = $paginator->paginate(

            $repository->findByUser($user),
            $request->query->getInt('page', 1), /*page number*/
            7 /*limit per page*/
        );

        return $this->render('candidature/index.html.twig', [
            'candidatures' => $candidatures,
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

        return $this->render('candidature/index.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }

    

}
