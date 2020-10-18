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

    /**
     * @var string[]
     */
    private $type_image = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp'];

    /**
     * @ORM\ManyToOne(targetEntity=Figures::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_figure;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param $image
     * @return $this
     */
    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @param $image
     * @return bool
     */
    public function checkImage($image): bool
    {
        if (in_array($image->getMimeType(), $this->type_image) ){
            return true;
        }
        return false;
    }

    /**
     * @return Figures|null
     */
    public function getIdFigure(): ?Figures
    {
        return $this->id_figure;
    }

    /**
     * @param Figures|null $id_figure
     * @return $this
     */
    public function setIdFigure(?Figures $id_figure): self
    {
        $this->id_figure = $id_figure;

        return $this;
    }
}
