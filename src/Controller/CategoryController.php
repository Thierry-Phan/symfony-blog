<?php

namespace App\Controller;


use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package App\Controller
 * @Route("/category")
 */
class CategoryController extends AbstractController
{


    /**
     * @Route("/{id}")
     */
    public function index(Category $category)
    {
        /**
         * afficher les 5 dernieres articles de la catégorie
         * par ordre de date de publication descroissant
         * avec un lien vers la page article
         */
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findBy(['category' => $category],['publicationDate' => 'desc'], 5);

        return $this->render(
            'category/index.html.twig',
            [
                'category' => $category,
                'articles' => $articles
            ]
        );
    }

    public function menu()
    {
        $repository = $this->getdoctrine()->getRepository(Category::class);
        $categories = $repository->findBy([],['name'=>'asc']);

        return $this->render(
            'category/menu.html.twig',
            [
                'categories' => $categories
            ]
        );
    }
}
