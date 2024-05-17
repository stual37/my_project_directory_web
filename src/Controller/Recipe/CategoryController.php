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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/recipe/category", name: 'recipes.category.')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, CategoryRepository $categoryRepository ): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('recipes/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/add' , name: 'add', methods:['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $em )
    {
        $category  = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'La categorie a bien été ajouté');
            return $this->redirectToRoute('recipes.category.index');
        }

        return $this->render('recipes/category/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', requirements:['id' => Requirement::DIGITS], methods:['GET', 'POST'])]
    public function edit(Request $request, Category $category,  EntityManagerInterface $em )
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'La catégorie a bien été modifiée');
            return $this->redirectToRoute('recipes.category.index');
        }

        return $this->render('recipes/category/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', requirements:['id' => Requirement::DIGITS], methods:['DELETE'])]
    public function delete(Request $request, Category $category , EntityManagerInterface $em)
    {
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', 'La catégorie a été supprimé avec succès');

        return $this->redirectToRoute('recipes.category.index');
    }
}
