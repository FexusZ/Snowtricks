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

    /**
     *
     */
    CONST GROUP = [
        'Groupe 1',
        'Groupe 2',
    ];

    /**
     * Figures constructor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFigure(): ?string
    {
        return $this->figure;
    }

    /**
     * @param string $figure
     * @return $this
     */
    public function setFigure(string $figure): self
    {
        $this->figure = $figure;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getGroupe(): ?int
    {
        return $this->groupe;
    }

    /**
     * @param int $groupe
     * @return $this
     */
    public function setGroupe(int $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFeaturedImage(): ?int
    {
        return $this->featured_image;
    }

    /**
     * @param int $featured_image
     * @return $this
     */
    public function setFeaturedImage(int $featured_image): self
    {
        $this->featured_image = $featured_image;
        return $this;
    }

    /**
     * @return string|null
     */
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

    /**
     * @param Image $image
     * @return $this
     */
    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setIdFigure($this);
        }

        return $this;
    }

    /**
     * @param Image $image
     * @return $this
     */
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

    /**
     * @param Video $video
     * @return $this
     */
    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setIdFigure($this);
        }

        return $this;
    }

    /**
     * @param Video $video
     * @return $this
     */
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

    /**
     * @return client|null
     */
    public function getIdClient(): ?client
    {
        return $this->id_client;
    }

    /**
     * @param client|null $id_client
     * @return $this
     */
    public function setIdClient(?client $id_client): self
    {
        $this->id_client = $id_client;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * @param \DateTimeInterface $created_at
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    /**
     * @param \DateTimeInterface $updated_at
     * @return $this
     */
    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @throws \Exception
     */
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

    /**
     * @param Commentaire $commentaire
     * @return $this
     */
    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setFigure($this);
        }

        return $this;
    }

    /**
     * @param Commentaire $commentaire
     * @return $this
     */
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