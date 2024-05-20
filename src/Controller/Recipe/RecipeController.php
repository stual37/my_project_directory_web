<?php

namespace App\Controller\Recipe;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Requirement\Requirement;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

use function Symfony\Component\Clock\now;

#[Route("/recipe/recettes", name: 'recipes.recipe.')]
class RecipeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function recettes(Request $request, RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAll();

        return $this->render('recipes/recipe/index.html.twig', [
            'controller_name' => 'Les recettes',
            'recipes' => $recipes
        ]);
    }



    // premier paramètres :  obligatoire => format de la route
    // name :  obligatoire => nom de la route
    // requirements : optionnel => paramètres permettant de définir les formats attendues pour le paramètrs de l'url
    #[Route('/{id}', name: 'show', requirements: ['id' => Requirement::DIGITS])]
    public function show(Request $request, int $id, RecipeRepository $recipeRepository) : Response
    {
        /** @var Recipe $recipe */
        $recipe = $recipeRepository->find($id);

        return $this->render('recipes/recipe/show.html.twig', [
            'slug' => $recipe->getSlug(),
            'recipe' => $recipe,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', requirements:['id' => Requirement::DIGITS], methods:['GET', 'POST'])]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $em, UploaderHelper $uploaderHelper)
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            //$file = $form->get('thumbnailFile')->getData();
            //$fileName = $recipe->getId() . '-' . $recipe->getSlug() . '.' . $file->getClientOriginalExtension();
            //$param = $this->getParameter('kernel.project_dir') . '/public/images/recettes';
            //$file->move($param, $fileName);
            //$recipe->setThumbnail($fileName);

            /** @var  EntityManagerInterface $em */
            $em->flush();
            $this->addFlash('success', 'La recette a bien été mis à jour');
            return $this->redirectToRoute('recipes.recipe.index');
        }


        return $this->render('recipes/recipe/edit.html.twig', [
            'slug' => $recipe->getSlug(),
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    #[Route('/add' , name: 'add', methods:['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $em)
    {
        /** @var Recipe $recipe */
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'La recette a bien été ajouté');
            return $this->redirectToRoute('recipes.recipe.index');
        }

        return $this->render('recipes/recipe/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', requirements:['id' => Requirement::DIGITS], methods:['DELETE'])]
    public function delete(Request $request, Recipe $recipe , EntityManagerInterface $em)
    {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'La recette a été supprimé avec succès');

        return $this->redirectToRoute('recipes.recipe.index');
    }
}
