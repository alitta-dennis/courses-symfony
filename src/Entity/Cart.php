<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Course::class)]
    private Collection $course;

    // #[ORM\OneToOne(mappedBy: 'cart', cascade: ['persist', 'remove'])]
    
 
    #[ORM\OneToOne(inversedBy: 'cart', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $email = null;

    public function __construct()
    {
        $this->course = new ArrayCollection();
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, course>
     */
    public function getCourse(): Collection
    {
        return $this->course;
    }

    public function addCourse(course $course): static
    {
        if (!$this->course->contains($course)) {
            $this->course->add($course);
        }

        return $this;
    }

    public function removeCourse(course $course): static
    {
        $this->course->removeElement($course);

        return $this;
    }

    
     public function getEmail(): ?User
    {
        
        return $this->email;
    }
    // public function __toString()
    //     {
    //         return $this->getUser(); // Replace with the actual property you want to display.
    //     }

    public function setEmail(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->email !== null) {
            $this->email->setCart(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getCart() !== $this) {
            $user->setCart($this);
        }

        $this->email = $user;

        return $this;
    }
    // public function __toString()
    // {
    //     return $this->userId;
    // }
}
