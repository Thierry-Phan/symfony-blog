<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 30/11/2018
 * Time: 14:54
 */

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package App\Controller\Admin
 *
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(Category::class);
        // $categories = $repository->findAll();
//        findAll() avec un tri sur name :
        $categories = $repository->findBy([],['name' => 'asc']);
        return $this->render(
            'Admin/Category/index.html.twig',
            [
                'categories' => $categories
            ]
        );
    }

    /**
 * {id} est optionnel et doit être un nombre
 * @Route("/edition/{id}", defaults={"id": null}, requirements={"id":"\d+"})
 */
    public function edit(Request $request, $id)
    {
        dump($id);
        $em = $this->getDoctrine()->getManager();
        if(is_null($id)){ //création
            $category = new Category();
        }else {// modification
            $category = $em->find(Category::class, $id);
            // 404 si l'id recu dans l'url n'est pas en BDD
            if (is_null($category)){
                throw new NotFoundHttpException();
            }
        }

//        création du formulaire lié à la catégorie
        $form = $this->createForm(CategoryType::class, $category);
//        le formulaire analyse le requête HTTP
//        et traite le formulaire s'il a été soumis
        $form->handleRequest($request);
//        Si le formulaire a été envoyé
        if($form->isSubmitted()){
        dump($category);

//        si les validations à partir des annotations dans
//        l'entité Category sont ok
            if($form->isValid()){
//                enregistrement de la catégorie en bdd
                $em->persist($category);
                $em->flush();

//                message de confirmation
                $this->addFlash('success','La catégorie est créée');
//                redirection vers la liste
                return $this->redirectToRoute('app_admin_category_index');
            } else {
                $this->addFlash('error', 'Le Formulaire contient des erreurs');
            }
        }
        return $this->render(
            'Admin/Category/edit.html.twig',
            [
//                passage du formulaire au template
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route ("/suppression/{id}")
     */
    public function delete(Category $category)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($category);
        $em->flush();

        $this->addFlash(
            'success',
            'La catégorie est supprimée'
        );
        return $this->redirectToRoute('app_admin_category_index');
    }
}