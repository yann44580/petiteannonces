<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;


/**
 * @route("/admin/categories", name="admin_categories_")
 * @package App\Controller\Admin 
 */



class CategoriesController extends AbstractController
{
    /**
     * 
     * @Route("/", name="home")
     */
    public function index(CategoriesRepository $categoriesrepo)
    {
        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categoriesrepo->findAll() 
        ]);
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
