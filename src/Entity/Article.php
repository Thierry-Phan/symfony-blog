<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le titre est obligatoire.")
     * Validation : nombre de caractères
     * @Assert\Length(max="20",
     *     maxMessage="Le titre ne doit pas dépasser {{ limit }} caractères.")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Le contenu est obligatoire.")
     * Validation : nombre de caractères
     * @Assert\Length(max="255",
     *     maxMessage="Le contenu ne doit pas dépasser {{ limit }} caractères.")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publicationDate;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez selectionner une catégorie")
     */
    private $category;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Assert\Image(maxSize="1M",
     *     maxSizeMessage="Le fichier ne doit pas faire plus d'1 Mo",
     *     mimeTypesMessage="Le fichier doit être une image")
     */
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return Article
     */
    public function setCategory(Category $category): Article
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return Article
     */
    public function setAuthor(User $author): Article
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return Article
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }



}
