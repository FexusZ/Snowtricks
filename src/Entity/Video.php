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
     * @ORM\Column(type="integer")
     */
    protected $id_figure;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideo()
    {
        return $this->video;
    }

    public function setVideo($video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getIdFigure(): ?int
    {
        return $this->id_figure;
    }

    public function setIdFigure(int $id_figure): self
    {
        $this->id_figure = $id_figure;

        return $this;
    }
}
