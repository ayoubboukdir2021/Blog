<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; 

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\PostRepository")
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * 
     * @ORM\Column(name="titre", type="string", length=100)
     * @Assert\NotBlank(message="Merci de mettre un titre")
     */
    private $titre;

    /**
     * @var string
     * 
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank(message="Meci de mettre une description")
     */
    private $description;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="merci de mettre une description")
     * @ORM\Column(name="datepublish", type="datetime")
     */
    private $datepublish;

    /**
     * @var string
     * @Assert\NotBlank(groups={"new"},message="merci de mettre une image")
     * @ORM\Column(name="image", type="string", length=255)
     * @Assert\File(mimeTypes={"image/png","image/jpeg"})
     * @Assert\Valid()
     */
    private $image;

     /**
     * @var string
     * @Assert\NotBlank(message="Merci de mettre un user")
     * @ORM\Column(name="user", type="integer")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="BlogBundle\Entity\Category")
     * @Assert\NotBlank(message="categorie est vide")
     */
    private $categories;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Post
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Post
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set datepublish
     *
     * @param \DateTime $datepublish
     *
     * @return Post
     */
    public function setDatepublish($datepublish)
    {
        $this->datepublish = $datepublish;

        return $this;
    }

    /**
     * Get datepublish
     *
     * @return \DateTime
     */
    public function getDatepublish()
    {
        return $this->datepublish;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Post
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Post
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set categories
     *
     * @param \BlogBundle\Entity\Category $categories
     *
     * @return Post
     */
    public function setCategories(\BlogBundle\Entity\Category $categories = null)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get categories
     *
     * @return \BlogBundle\Entity\Category
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
