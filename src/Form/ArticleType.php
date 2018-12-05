<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'Titre'
                ]

            )
            ->add(
                'content',
                TextareaType::class,
                [
                    'label' => 'Contenu'
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'label' => 'Categorie',
                    // nom de l'entité
                    'class' => Category::class,
                    // nom de l'attribut utilisé pour l'affichage des options (__toString)
                    'choice_label' => 'Name',
                    // pour avoir une 1ère option vide
                    'placeholder' => 'Choissez une catégorie'

                ]
            )
            ->add(
                'image',
                // input type="file"
                FileType::class,
                [
                    'label' => 'Illustration',
                    'required' => false
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }

    /*
     * ajouter la méthode edit() qui fait le rendu du formulaire et son trateement
     * mettre un lien ajouter dans la page de liste
     *
     * Validation : tous les champs obligatoires
     *
     * En création :
     * -setter l'auteur avec l'utilisateur connecté
     *          ($this->>getUser() depuis le contrôleur)
     * -mettre la date de publication à maintenant
     *
     * Adapter la route et le contenu de la m'éthode
     * pour que la page fonctionne en modification
     * et ajouter le bouton modifier dans la page de la liste
     *
     * Enregistrer l'article en bdd si le formulaire est bien rempli
     * puis rediriger vers la liste avec un message de confirmation
     */
}
