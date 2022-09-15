<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'ingrediient', targetEntity: IngredientPerRecette::class)]
    private Collection $ingredientPerRecettes;

    public function __construct()
    {
        $this->recettes = new ArrayCollection();
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
        $this->name = ucfirst(strtolower($name));

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
            $ingredientPerRecette->setIngrediient($this);
        }

        return $this;
    }

    public function removeIngredientPerRecette(IngredientPerRecette $ingredientPerRecette): self
    {
        if ($this->ingredientPerRecettes->removeElement($ingredientPerRecette)) {
            // set the owning side to null (unless already changed)
            if ($ingredientPerRecette->getIngrediient() === $this) {
                $ingredientPerRecette->setIngrediient(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->name;
    }

   
}
