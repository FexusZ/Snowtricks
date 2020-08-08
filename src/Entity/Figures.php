<?php

namespace App\Entity;

use App\Repository\FiguresRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Video;
use Image;


/**
 * @ORM\Entity(repositoryClass=FiguresRepository::class)
 */
class Figures
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
    private $figure;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $groupe;

    /**
     * @OneToMany(targetEntity="Image", mappedBy="id_figure", indexBy="id_figure")
     * @JoinColumn(name="figure_id", referencedColumnName="id")
     */
    private $image;

    /**
     * @OneToMany(targetEntity="Video", mappedBy="id_figure", indexBy="id_figure")
     * @JoinColumn(name="figure_id", referencedColumnName="id")
     */
    private $video;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFigure(): ?string
    {
        return $this->figure;
    }

    public function setFigure(string $figure): self
    {
        $this->figure = $figure;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getGroupe(): ?int
    {
        return $this->groupe;
    }

    public function setGroupe(int $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getVideo()
    {
        return $this->video;
    }
}
