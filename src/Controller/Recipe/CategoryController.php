<?php

namespace App\Controller\Recipe;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/recipe/category", name: 'recipes.category.')]
#[IsGranted('ROLE_USER')]
class CategoryController extends AbstractController
{
    /**
     * function index : Permet d'afficher la liste des catégories disponibles
     *
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    #[Route('/', name: 'index')]
    public function index(Request $request, CategoryRepository $categoryRepository ): Response
    {
        // Permet de récupérer toutes les catégories dans la Bdd
        $categories = $categoryRepository->findAll();
        return $this->render('recipes/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * function add : Permet d'ajouter une catégorie
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
    */
    #[Route('/add' , name: 'add', methods:['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $em )
    {
        $category  = new Category();
        // Permet de créer un formulaire
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            // enregistre les données
            $em->persist($category);
            // valide l'enregistrement
            $em->flush();
            $this->addFlash('success', 'La categorie a bien été ajouté');
            return $this->redirectToRoute('recipes.category.index');
        }

        return $this->render('recipes/category/add.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * function edit : Permet de modifier une catégorie
     *
     * @param Request $request
     * @param Category $category
     * @param EntityManagerInterface $em
     * @return Response
    */
    #[Route('/edit/{id}', name: 'edit', requirements:['id' => Requirement::DIGITS], methods:['GET', 'POST'])]
    public function edit(Request $request, Category $category,  EntityManagerInterface $em )
    {
        // Permet de créer un formulaire avec les données passées en paramêtre
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            // valide les momdifications apportées
            $em->flush();
            $this->addFlash('success', 'La catégorie a bien été modifiée');
            return $this->redirectToRoute('recipes.category.index');
        }

        return $this->render('recipes/category/edit.html.twig', [
            'form' => $form
        ]);
    }
    /**
     * function delete : Permet de supprimer une catégorie de cuisine 
     *
     * @param Request $request
     * @param Category $category
     * @param RecipeRepository $recipeRepository
     * @return Response
     */
    #[Route('/delete/{id}', name: 'delete', requirements:['id' => Requirement::DIGITS], methods:['DELETE'])]
    public function delete(Request $request, Category $category , EntityManagerInterface $em)
    {
        // Permet de supprimer une catégorie en fonction de son id
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', 'La catégorie a été supprimé avec succès');

        return $this->redirectToRoute('recipes.category.index');
    }
}
