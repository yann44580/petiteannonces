<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Form\EditProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Users;
use App\Security\AppAuthenticator;
use App\Form\RegistrationFormType;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;



class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }
    /**
     * @Route("/user/annonces/ajout", name="user_annonces_ajout")
     */
    public function ajoutAnnonce(Request $request, EntityManagerInterface $entityManager): Response
    {
        $annonce = new Annonces;

        $form = $this->createForm(AnnoncesType::class, $annonce);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonce->setUsers($this->getUser());
            $annonce->setActive(false);

            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('user');
        }

        $form = $this->createForm(AnnoncesType::class, $annonce);




        return $this->render('user/annonces/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/profil/modifier", name="user_profil_modifier")
     */
    public function editProfil(Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('user');
        }




        return $this->render('user/editprofil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/pass/modifier", name="user_pass_modifier")
     */
    public function editPass(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $password): Response
    {
        if ($request->isMethod('POST')) {
            
            $user = $this->getUser();

            // On vérifie si les 2 mots de passe sont identiques
            if ($request->request->get('pass') == $request->request->get('pass2')) {
                $user->setPassword(
                    $password->encodePassword(
                        $user, $request->request->get('pass')
                    )
                );

                
                $entityManager->flush();
                $this->addFlash('message', 'mot de passe mis à jour');

                return $this->redirectToRoute('user');
            } else {
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identique.');
            }
        }



        return $this->render('user/editpass.html.twig');
    }
}
