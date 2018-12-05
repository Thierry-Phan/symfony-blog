<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 03/12/2018
 * Time: 17:01
 */

namespace App\Controller\Admin;


use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller\Admin
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        /*
         * faire la page qui liste les articles dans un tableau html
         * avec nom de la catégorie
         * nom de l'auteur
         * et date au format français
         * (tous les champs sauf le contenu)
         */

        {
            $em = $this->getDoctrine()->getManager();

            $repository = $em->getRepository(Article::class);

            $articles = $repository->findBy([], ['publicationDate' => 'desc']);

            return $this->render(
                'Admin/Article/index.html.twig',
                [
                    'articles' => $articles
                ]
            );
        }
    }

    /**
     * {id} est optionnel et doit être un nombre
     * @Route("/edition/{id}", defaults={"id": null}, requirements={"id":"\d+"})
     */
    public function edit(Request $request, $id)
    {
        dump($id);
        $em = $this->getDoctrine()->getManager();
        $originalImage = null;

        if(is_null($id)){ //création
            $article = new Article();
        }else {// modification
            $article = $em->find(Article::class, $id);

            if (!is_null($article->getImage())){
                // nom du fichier venant de la bdd
                $originalImage = $article->getimage();
                // on sette l'image avec un objet File
                // pour le traitement par le formulaire
                $article->setImage(
                    new File($this->getParameter('upload_dir'). $originalImage)
                );
            }
            // 404 si l'id recu dans l'url n'est pas en BDD
            if (is_null($article)){
                throw new NotFoundHttpException();
            }
        }

//        création du formulaire lié à la catégorie
        $form = $this->createForm(ArticleType::class, $article);
//        le formulaire analyse le requête HTTP
//        et traite le formulaire s'il a été soumis
        $form->handleRequest($request);
//        Si le formulaire a été envoyé
        if($form->isSubmitted()){
            dump($article);

//        si les validations à partir des annotations dans
//        l'entité Category sont ok
            if($form->isValid()){
                /** @var UploadedFile $image */
                $image = $article->getimage();

//                s'il y a eut une image uploadée
                if (!is_null($image)) {
                    // nom de l'image dans notre application
                    $filename = uniqid() .  '.' . $image->guessExtension();

                    // équivalent de move_uploaded_file()
                    $image->move(
                        // répertoire de destination
                        // cf le parametre upload_dir dans config/services.yaml
                        $this->getParameter('upload_dir'),
                        //nom du fichier
                        $filename
                    );
                    // on sette l'attribut image de l'article avec le nom
                    // de l'image pour enregistrement en bdd
                    $article->setImage($filename);

                    // en modifiant, on supprime l'ancienne image s'il y en a une
                    if (!is_null($originalImage)){
                        unlink($this->getParameter('upload_dir') . $originalImage);
                    } else{
                        // sans upload, pour la modification, on sette l'attribut
                        // image avec le nom de l'ancienne image
                        $article->setImage($originalImage);
                    }
                }
                $currentUser = $this->getUser();
                $currentDate = new \DateTime();
                if(is_null($id)){
                    $article->setAuthor($currentUser);
                    $article->setPublicationDate($currentDate);
                }
//                enregistrement de la catégorie en bdd
                $em->persist($article);
                $em->flush();

//                message de confirmation
                $this->addFlash('success','La catégorie est créée');
//                redirection vers la liste
                return $this->redirectToRoute('app_admin_article_index');
            } else {
                $this->addFlash('error', 'Le Formulaire contient des erreurs');
            }
        }
        return $this->render(
            'Admin/Article/edit.html.twig',
            [
//                passage du formulaire au template
                'form' => $form->createView(),
                'original_image' => $originalImage
            ]
        );
    }

    /**
     * @param Article $article
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/delete")
     */
    public function delete(Article $article)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($article);
        $em->flush();

        $this->addFlash(
            'success',
            'L\'article est supprimé'
        );
        return $this->redirectToRoute('app_admin_article_index');
    }
}
