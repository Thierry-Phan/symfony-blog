<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/{id}")
     */
    public function index(Article $article, Request $request)
    {
        $comment = new Comment();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()){
                $currentDate = new \DateTime();
                $currentUser = $this->getUser();
                $comment
                    ->setArticle($article)
                    ->setDateComment($currentDate)
                    ->setUser($currentUser);
                $em->persist($comment);
                $em->flush();
            }
        }

        /*
         * afficher toutes les informations de l'article
         * avec l'image s'il y en a une
         *
         * Sous l'article, si l'utilisateur n'est pas connecté,
         * l'inviter à le faire pour pouvoir écrire un commentaire
         * Sinon, lui afficher un formulaire avec un textarea
         * pour pouvoir écrire un commentaire
         * Nécessite une entité Comment :
         * -content(text en bdd)
         * -date de publication (datetime)
         * -user (l'utilisateur qui écrit le commentaire)
         * -article(l'article sur lequel on écrit le commentaire)
         * Nécessite le form type qui va avec, contenant le textarea
         * le contenu du commentaire ne doit pas être vide
         *
         * Lister les commentaires en dessous, avec nom utilisateur,
         * date de publication, contenu du message.
         */
        return $this->render('article/index.html.twig',
            [
                'article' => $article,
                'form' => $form->createView(),
            ]
        );
    }


}
