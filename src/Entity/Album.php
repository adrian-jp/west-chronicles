<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AlbumRepository::class)
 */
class Album
{
    use TraitImage;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $nom;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity=Track::class, mappedBy="album", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $tracks;

    /**
     * @param ArrayCollection $tracks
     */
    public function setTracks(ArrayCollection $tracks): void
    {
        $this->tracks = $tracks;
        foreach ($tracks->toArray() as $track) {
            $track->setAlbum($this);
        }
    }

    /**
     * @ORM\ManyToMany(targetEntity=Artist::class, inversedBy="albums",cascade={"persist"})
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $artists;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
        $this->artists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Track[]
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(Track $track): self
    {
        if (!$this->tracks->contains($track)) {
            $track->setAlbum($this);
            $this->tracks[] = $track;

        }

        return $this;
    }

    public function removeTrack(Track $track): self
    {
        if ($this->tracks->contains($track)) {
            $this->tracks->removeElement($track);
            // set the owning side to null (unless already changed)
            if ($track->getAlbum() === $this) {
                $track->setAlbum(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Artist[]
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): self
    {
        if (!$this->artists->contains($artist)) {
            $this->artists[] = $artist;
        }

        return $this;
    }

    public function removeArtist(Artist $artist): self
    {
        if ($this->artists->contains($artist)) {
            $this->artists->removeElement($artist);
        }

        return $this;
    }
}
