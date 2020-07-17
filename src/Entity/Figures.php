<?php

namespace App\Entity;

use App\Repository\FiguresRepository;
use Doctrine\ORM\Mapping as ORM;

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
}
