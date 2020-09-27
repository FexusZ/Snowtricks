<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    private $imageType;
    
    /**
     * @ORM\ManyToOne(targetEntity=Figures::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_figure;

    public function  __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getIdFigure(): ?Figures
    {
        return $this->id_figure;
    }

    public function setIdFigure(?Figures $id_figure): self
    {
        $this->id_figure = $id_figure;

        return $this;
    }
}
