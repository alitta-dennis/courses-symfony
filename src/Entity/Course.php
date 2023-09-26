<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constarints as Assert;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $courseName = null;
     #@Assert\NotBlank

    #[ORM\Column(length: 255)]
    private ?string $courseCode = null;
     #@Assert\NotBlank

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $startDate = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $starRating = null;
     #@Assert\NotBlank

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    // #[ORM\ManyToOne(inversedBy: 'courses')]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?category $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourseName(): ?string
    {
        return $this->courseName;
    }

    public function setCourseName(string $courseName): static
    {
        $this->courseName = $courseName;

        return $this;
    }

    public function getCourseCode(): ?string
    {
        return $this->courseCode;
    }

    public function setCourseCode(string $courseCode): static
    {
        $this->courseCode = $courseCode;

        return $this;
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function setStartDate(?string $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStarRating(): ?float
    {
        return $this->starRating;
    }

    public function setStarRating(float $starRating): static
    {
        $this->starRating = $starRating;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    // public function getCategory(): ?category
    // {
    //     return $this->category;
    // }

    // public function setCategory(?category $category): static
    // {
    //     $this->category = $category;

    //     return $this;
    // }
}
