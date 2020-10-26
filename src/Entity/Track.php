<?php

namespace App\Entity;

use App\Repository\TrackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TrackRepository::class)
 */
class Track
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Artist::class, mappedBy="tracks", cascade={"persist"})
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $artists;

    /**
     * @Assert\File(
     *     maxSize = "100M",
     *     uploadErrorMessage="Le fichier n'a pas été téléchargé",
     *     maxSizeMessage ="Le fichier est trop lourd : {{ limit }} {{ suffix }}",
     *     mimeTypes={ "audio/mpeg", "audio/mp3", "audio/vnd.wav" },
     *     mimeTypesMessage="Format non autorisé"
     * )
     * )
     * @var UploadedFile
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $length;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelleCourt;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="tracks")
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity=Album::class, inversedBy="tracks", cascade={"persist", "remove"})
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $album;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $ordreAlbum;

    public function __construct()
    {
        $this->artists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $artist->addTrack($this);
        }

        return $this;
    }

    public function removeArtist(Artist $artist): self
    {
        if ($this->artists->contains($artist)) {
            $this->artists->removeElement($artist);
            $artist->removeTrack($this);
        }

        return $this;
    }

    /**
     * @return string|UploadedFile|null
     */
    public function getUrl()
    {
        return $this->url;
    }


    /**
     * @param UploadedFile|string|null $url
     * @return $this
     */
    public function setUrl($url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getLength(): ?string
    {
        return $this->length;
    }

    public function setLength(?string $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getLibelleCourt(): ?string
    {
        return $this->libelleCourt;
    }

    public function setLibelleCourt(?string $libelleCourt): self
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): self
    {
        $this->album = $album;

        return $this;
    }

    public function getOrdreAlbum(): ?int
    {
        return $this->ordreAlbum;
    }

    public function setOrdreAlbum(?int $ordreAlbum): self
    {
        $this->ordreAlbum = $ordreAlbum;

        return $this;
    }
}
