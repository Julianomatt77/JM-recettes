<?php

namespace App\Entity;

use App\Repository\CourseRecetteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRecetteRepository::class)]
class CourseRecette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $qty = null;

    #[ORM\ManyToOne(inversedBy: 'courseRecettes')]
    // #[ORM\Column(nullable: true)]
    #[ORM\JoinColumn(onDelete:"SET NULL")]
    private ?Course $course = null;

    #[ORM\ManyToOne(inversedBy: 'courseRecettes')]
    // #[ORM\Column(nullable: true)]
    #[ORM\JoinColumn(onDelete:"SET NULL")]
    private ?Recette $recette = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(?int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

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
}
