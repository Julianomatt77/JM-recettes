<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_course = null;

    // #[ORM\ManyToMany(targetEntity: Recette::class, inversedBy: 'courses')]
    // // #[ORM\JoinColumn(onDelete:"SET NULL")]
    // private Collection $recette;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    // #[ORM\JoinColumn(nullable: false)]
    #[ORM\JoinColumn(onDelete:"SET NULL")]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: CourseRecette::class)]
    private Collection $courseRecettes;

    public function __construct()
    {
        $this->courseRecettes = new ArrayCollection();
    }

    // public function __construct()
    // {
    //     $this->recette = new ArrayCollection();
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCourse(): ?\DateTimeInterface
    {
        return $this->date_course;
    }

    public function setDateCourse(?\DateTimeInterface $date_course): self
    {
        $this->date_course = $date_course;

        return $this;
    }

    // /**
    //  * @return Collection<int, Recette>
    //  */
    // public function getRecette(): Collection
    // {
    //     return $this->recette;
    // }

    // public function addRecette(Recette $recette): self
    // {
    //     if (!$this->recette->contains($recette)) {
    //         $this->recette->add($recette);
    //     }

    //     return $this;
    // }

    // public function removeRecette(Recette $recette): self
    // {
    //     $this->recette->removeElement($recette);

    //     return $this;
    // }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, CourseRecette>
     */
    public function getCourseRecettes(): Collection
    {
        return $this->courseRecettes;
    }

    public function addCourseRecette(CourseRecette $courseRecette): self
    {
        if (!$this->courseRecettes->contains($courseRecette)) {
            $this->courseRecettes->add($courseRecette);
            $courseRecette->setCourse($this);
        }

        return $this;
    }

    public function removeCourseRecette(CourseRecette $courseRecette): self
    {
        if ($this->courseRecettes->removeElement($courseRecette)) {
            // set the owning side to null (unless already changed)
            if ($courseRecette->getCourse() === $this) {
                $courseRecette->setCourse(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->name;
    }
}
