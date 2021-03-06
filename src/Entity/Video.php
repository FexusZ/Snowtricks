<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=VideoRepository::class)
 */
class Video
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
    private $video;

    /**
     * @ORM\ManyToOne(targetEntity=Figures::class, inversedBy="videos")
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
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param $video
     * @return $this
     */
    public function setVideo($video): self
    {
        $this->video = $video;

        return $this;
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
