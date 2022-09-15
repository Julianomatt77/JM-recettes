<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $nb_personnes = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description = null;    

    #[ORM\ManyToOne(inversedBy: 'recettes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Source $source = null;

    #[ORM\ManyToMany(targetEntity: Course::class, mappedBy: 'recette')]
    private Collection $courses;

    #[ORM\OneToMany(mappedBy: 'recette', targetEntity: IngredientPerRecette::class)]
    private Collection $ingredientPerRecettes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function __construct()
    {
        $this->ingredient = new ArrayCollection();
        $this->courses = new ArrayCollection();
        $this->ingredientPerRecettes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNbPersonnes(): ?int
    {
        return $this->nb_personnes;
    }

    public function setNbPersonnes(int $nb_personnes): self
    {
        $this->nb_personnes = $nb_personnes;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }  


    public function getSource(): ?Source
    {
        return $this->source;
    }

    public function setSource(?Source $source): self
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->addRecette($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            $course->removeRecette($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, IngredientPerRecette>
     */
    public function getIngredientPerRecettes(): Collection
    {
        return $this->ingredientPerRecettes;
    }

    public function addIngredientPerRecette(IngredientPerRecette $ingredientPerRecette): self
    {
        if (!$this->ingredientPerRecettes->contains($ingredientPerRecette)) {
            $this->ingredientPerRecettes->add($ingredientPerRecette);
            $ingredientPerRecette->setRecette($this);
        }

        return $this;
    }

    public function removeIngredientPerRecette(IngredientPerRecette $ingredientPerRecette): self
    {
        if ($this->ingredientPerRecettes->removeElement($ingredientPerRecette)) {
            // set the owning side to null (unless already changed)
            if ($ingredientPerRecette->getRecette() === $this) {
                $ingredientPerRecette->setRecette(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function __toString(){
        return $this->name;
    }
}
