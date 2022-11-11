<?php

namespace App\Entity;

use App\Api\Core\Services\QrCodeManager;
use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Business::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $business;

    /**
     * @ORM\Column(type="text")
     */
    private $qrCodeImgFilename;

    /**
     * This property does not map any db column. It holds the image base64 value of an image.
     *
     * @var string|null
     */
    private ?string $qrCodeBase64 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

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

    public function getBusiness(): ?Business
    {
        return $this->business;
    }

    public function setBusiness(?Business $business): self
    {
        $this->business = $business;

        return $this;
    }

    public function getQrCodeImgFilename(): ?string
    {
        return $this->qrCodeImgFilename;
    }

    public function setQrCodeImgFilename(string $qrCodeImgFilename): self
    {
        $this->qrCodeImgFilename = $qrCodeImgFilename;

        return $this;
    }

    /**
     * @param string|null $qrCodeBase64
     */
    public function setQrCodeBase64(?string $qrCodeBase64): void
    {
        $this->qrCodeBase64 = $qrCodeBase64;
    }

    /**
     * Use this function to build the base64 value respective the
     * image represented by qrCodeImgFilename
     *
     * @return string|null
     * @throws Exception
     */
    public function getQrCodeBase64(): ?string
    {
        if ($this->qrCodeImgFilename) {
            $this->qrCodeBase64 = QrCodeManager::getQrCodeBase64($this->qrCodeImgFilename);
        }

        return $this->qrCodeBase64;
    }
}
