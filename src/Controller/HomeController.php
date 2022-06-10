<?php

namespace App\Controller;


use App\Repository\AnnonceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(AnnonceRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
       

        $annonces = $paginator->paginate(

            $repository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            7 /*limit per page*/
        );

        return $this->render('home/index.html.twig', [
            'annonces' => $annonces,
            
        ]);
    }
}
