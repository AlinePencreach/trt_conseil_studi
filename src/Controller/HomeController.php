<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    /**
     * Undocumented function
     *
     * @return Response
     */
    #[Route('/', name: 'app_home')]
    public function index(Annonce $annonce, AnnonceRepository $repository, PaginatorInterface $paginator, Request $request): Response
    { 
        
        $annonce = $paginator->paginate(

            $repository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        return $this->render('home/index.html.twig', [
            'annonce' => $annonce,
        ]);
    }
}
