<?php

namespace App\Entity;

use App\Repository\FiguresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;


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
     * @Assert\NotBlank(message="Merci d'entrer un nom de figure!")
     */
    private $figure;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Merci d'entrer une description!")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Merci d'entrer un groupe de figure!")
     */
    private $groupe;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $featured_image = 0;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="id_figure", orphanRemoval=true, cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="id_figure", orphanRemoval=true, cascade={"persist"})
     */
    private $videos;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="figures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_client;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="figure", orphanRemoval=true)
     */
    private $commentaires;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    /**
     *
     */
    CONST GROUP = [
        'Groupe 1',
        'Groupe 2',
    ];

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

    public function getFeaturedImage(): ?int
    {
        return $this->featured_image;
    }

    public function setFeaturedImage(int $featured_image): self
    {
        $this->featured_image = $featured_image;
        return $this;
    }

    public function getGroupeText(): ?string
    {
        return self::GROUP[$this->groupe];
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setIdFigure($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getIdFigure() === $this) {
                $image->setIdFigure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setIdFigure($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getIdFigure() === $this) {
                $video->setIdFigure(null);
            }
        }

        return $this;
    }

    public function getIdClient(): ?client
    {
        return $this->id_client;
    }

    public function setIdClient(?client $id_client): self
    {
        $this->id_client = $id_client;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function updateTimestamps()
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt( new \DateTime(null, new \DateTimeZone('Europe/Paris')) );
        }
            $this->setUpdatedAt( new \DateTime(null, new \DateTimeZone('Europe/Paris')) );
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setFigure($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getFigure() === $this) {
                $commentaire->setFigure(null);
            }
        }

        return $this;
    }
}