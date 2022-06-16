<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\Candidature;
use Symfony\Component\Mime\Email;
use App\Repository\AnnonceRepository;
use App\Repository\CandidatureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CandidatureController extends AbstractController
{
    

//affiche les candidatures du candidat connecté
    #[Route('/candidature/candidat/', name: 'app_candidature')]
    public function indexCandidat(AnnonceRepository $annonceRepository, CandidatureRepository $repository, PaginatorInterface $paginator, Request $request): Response
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

    // //affiche les candidature aux annonces du recruteur 
    // #[Route('/candidature/recruteur/', name: 'app_candidature_recruteur')]
    // public function indexrecruteur(): Response
    // {
        
    //     // $candidatures = $paginator->paginate(

    //     //     $repository->findByAnnonce($annonce),
    //     //     $request->query->getInt('page', 1), /*page number*/
    //     //     7 /*limit per page*/
    //     // );

    //     return $this->render('candidature/recruteur.html.twig', [
            
    //     ]);
    // }

        //affiche les candidature aux annonces du recruteur 
        #[Route('/candidatures/annonce/{annonce}', name: 'app_annonce_candidature')]
        public function indexUserAnnonce(Annonce $annonce, PaginatorInterface $paginator, CandidatureRepository $repository, Request $request): Response
        {
    
            $candidatures = $paginator->paginate(
    
                $repository->findValideByAnnonce($annonce),
                $request->query->getInt('page', 1), /*page number*/
                7 /*limit per page*/
            );
    
            return $this->render('annonce/candidature.html.twig', [
                'candidatures' => $candidatures, 
               'annonce' => $annonce,
            ]);
        }

    //permet de postuler a une annonce en recuperant id annonce et id candidat
    #[Route('/candidature/new/{annonce}', name: 'app_candidature_new', methods: ['GET', 'POST'])]
    public function newCandidature(Annonce $annonce, CandidatureRepository $candidatureRepository, MailerInterface $mailer): Response
    {
        
         /** @var User $user */
         $user = $this->getUser();
        //Verifier que le candidat n'a pas déjà postuler donc la fiche condidature n'existe pas encore.
        $candidatureDone = $candidatureRepository->findOneByUserAndAnnonce($user, $annonce);
        if ($candidatureDone) {
            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }



         $candidature = new Candidature();
         $candidature->setCandidatId($user)
                    ->setAnnonceId($annonce);
                

                    $email = (new Email())
                    ->from($user->getEmail())
                    ->to($annonce->getAuteur()->getEmail())
                    ->subject('Vous avez recu une nouvelle candidature')
                    ->text('Connectez vous et rendez vous sur vos annonces pour voir vos nouvelles candidatures')
                    ->html('<p>See Twig integration for better HTML integration!</p>');
        
                $mailer->send($email);

    
       {
            $candidatureRepository->add($candidature, true);
            
            $this->addFlash(
                'success',
                'Votre candidature à bien été prise en compte. Un consultant doit maintenant la valider'
            );

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    //affiche les candidatures a valider par les consultants
    #[Route('/candidature/consultant', name: 'app_candidature_consultant', methods: ['GET'])]
    public function candidatureConsultant(CandidatureRepository $candidatureRepository, PaginatorInterface $paginator, Request $request): Response
    {
        
        $candidatures = $paginator->paginate(

            $candidatureRepository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('candidature/consultant.html.twig', [
            'candidatures' => $candidatures,
            

        ]);

    }


    //supprime une candidature
    #[Route('/candidature/{id}', name: 'app_candidature_delete', methods: ['POST'])]
    public function delete(Request $request, Candidature $candidature, CandidatureRepository $candidatureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $candidature->getId(), $request->request->get('_token'))) {
            $candidatureRepository->remove($candidature, true);
        }

        return $this->redirectToRoute('app_candidature', [
        ], Response::HTTP_SEE_OTHER);
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


        return $this->redirectToRoute('app_candidature_consultant', [
            'page' => $page,
        ], Response::HTTP_SEE_OTHER);
    }








}
