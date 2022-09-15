<?php

namespace App\Entity;

use App\Repository\IngredientPerRecetteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientPerRecetteRepository::class)]
class IngredientPerRecette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $qty = null;

    #[ORM\ManyToOne(inversedBy: 'ingredientPerRecettes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ingredient $ingrediient = null;

    #[ORM\ManyToOne(inversedBy: 'ingredientPerRecettes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recette $recette = null;

    #[ORM\Column]
    private ?float $qty_pp = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getIngrediient(): ?Ingredient
    {
        return $this->ingrediient;
    }

    public function setIngrediient(?Ingredient $ingrediient): self
    {
        $this->ingrediient = $ingrediient;

        return $this;
    }

    public function getRecette(): ?Recette
    {
        return $this->recette;
    }

    public function setRecette(?Recette $recette): self
    {
        $this->recette = $recette;

        return $this;
    }

    public function getQtyPp(): float
    {
        return $this->qty_pp;
    }

    public function setQtyPp(float $qty_pp): self
    {
        $this->qty_pp = $qty_pp;

        return $this;
    }

    public function __toString(){
        return $this->ingrediient;
    }
}
