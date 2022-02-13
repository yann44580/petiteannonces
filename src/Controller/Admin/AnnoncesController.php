<?php

namespace App\Controller\Admin;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\AnnoncesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @route("/admin/annonces", name="admin_annonces_")
 * @package App\Controller\Admin 
 */



class AnnoncesController extends AbstractController
{
    /**
     * 
     * @Route("/", name="home")
     */
    public function index(AnnoncesRepository $annoncesrepo)
    {
        return $this->render('admin/annonces/index.html.twig', [
            'annonces' => $annoncesrepo->findAll() 
        ]);
    }

     /**
     * 
     * @Route("/activer/{id}", name="activer")
     */
    public function activer(Annonces $annonce, EntityManagerInterface $entityManager)
    {
        $annonce->setActive(($annonce->getActive())?false:true);

        $entityManager->persist($annonce);
        $entityManager->flush();

        return new Response("true");
    }
    /**
     * 
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(Annonces $annonce, EntityManagerInterface $entityManager)
    {
       

        $entityManager->remove($annonce);
        $entityManager->flush();

        $this->addflash('message', 'annonce supprimer avec succes');

        return $this->redirectToRoute('admin_annonces_home');
    }



    /**
     * 
     * @Route("/ajout", name="ajout")
     */
    public function ajoutCategorie(Request $request,EntityManagerInterface $entityManager)
    {
        $categorie = new Categories;

        $form = $this->createForm(CategoriesType::class, $categorie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('admin_categories_home');
        }

        return $this->render('admin/categories/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * 
     * @Route("/modifier/{id}", name="modifier")
     */
    public function ModifCategorie(Categories $categorie, Request $request,EntityManagerInterface $entityManager)
    {

        $form = $this->createForm(CategoriesType::class, $categorie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('admin_categories_home');
        }

        return $this->render('admin/categories/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
