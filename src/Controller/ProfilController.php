<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\RecruteurType;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/profil')]
class ProfilController extends AbstractController
{
    #[Route('/', name: 'app_profil')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }



    #[Route('/edition/{id}', name: 'app_profil_edit', methods: ['GET', 'POST'])]
    /**
     * Edite the profil off the user connected
     *
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(User $user, Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {

        $form = $this->createForm(UserType::class, $user);
        


        $form->handleRequest(($request));
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_security_login');
        }

        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_security_logout');
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre profil a bien été mis à jour. '
            );

              /** @var UploadedFile $CVFile */
              $CVFile = $form->get('CV')->getData();
              

              if ($CVFile) {
                  $originalFilename = pathinfo($CVFile->getClientOriginalName(), PATHINFO_FILENAME);
                  // this is needed to safely include the file name as part of the URL
                  $safeFilename = $slugger->slug($originalFilename);
                  $newFilename = $safeFilename.'-'.uniqid().'.'.$CVFile->guessExtension();
  
                  // Move the file to the directory where brochures are stored
                  try {
                      $CVFile->move(
                          $this->getParameter('cv_directory'),
                          $newFilename
                      );
                  } catch (FileException $e) {
                      // ... handle exception if something happens during file upload
                  }
  
                  // updates the 'brochureFilename' property to store the PDF file name
                  // instead of its contents
                  $user->setCV($newFilename);
              // ... perform some action, such as saving the task to the database
              };

            return $this->redirectToRoute('app_profil');
        }

        return $this->renderForm('profil/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
