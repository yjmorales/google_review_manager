<?php

namespace App\Entity;

use App\Repository\Business\BusinessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BusinessRepository::class)
 */
class Business
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    #[Assert\NotBlank]
    private $active;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    private $state;

    /**
     * @ORM\Column(type="string", length=15)
     */
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 15)]
    private $zipCode;

    /**
     * @ORM\ManyToOne(targetEntity=IndustrySector::class)
     */
    private $industrySector;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="business", orphanRemoval=true)
     */
    private $reviews;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getIndustrySector(): ?IndustrySector
    {
        return $this->industrySector;
    }

    public function setIndustrySector(?IndustrySector $industrySector): self
    {
        $this->industrySector = $industrySector;

        return $this;
    }

    /**
     * Builds the location string value.
     *
     * @return string
     */
    public function getLocation(): string
    {
        $location = [];
        if (!empty($address = $this->getAddress())) {
            $location[] = $address;
        }
        if (!empty($city = $this->getCity())) {
            $location[] = $city;
        }
        if (!empty($state = $this->getState())) {
            $location[] = $state;
        }
        if (!empty($zipCode = $this->getZipCode())) {
            $location[] = $zipCode;
        }

        return join(', ', $location);
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setBusiness($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getBusiness() === $this) {
                $review->setBusiness(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
