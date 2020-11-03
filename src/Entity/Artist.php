<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=ArtistRepository::class)
 */
class Artist
{
    use TraitImage;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups("show_album")
     */
    const MAX_PER_PAGE = 9;

    const LA = '44';
    const VE = '85';
    const ML = '49';
    const MA = '53';
    const SA = '72';
    const IV = '35';
    const MO = '56';
    const CA = '22';
    const FI = '29';
    const ALL_DEPT = [
        Artist::LA,
        Artist::VE,
        Artist::ML,
        Artist::MA,
        Artist::SA,
        Artist::IV,
        Artist::MO,
        Artist::CA,
        Artist::FI
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity=Clip::class, inversedBy="artists")
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $clips;

    /**
     * @ORM\ManyToMany(targetEntity=Track::class, inversedBy="artists",cascade={"persist"})
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $tracks;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="artists")
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $events;

    /**
     * @ORM\ManyToMany(targetEntity=Album::class, mappedBy="artists", cascade={"persist"})
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $albums;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups(groups={"show_album"})
     */
    private $departement;

    public function __construct()
    {
        $this->clips = new ArrayCollection();
        $this->tracks = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->albums = new ArrayCollection();
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
     * @return Collection|Clip[]
     */
    public function getClips(): Collection
    {
        return $this->clips;
    }

    public function addClip(Clip $clip): self
    {
        if (!$this->clips->contains($clip)) {
            $this->clips[] = $clip;
        }

        return $this;
    }

    public function removeClip(Clip $clip): self
    {
        if ($this->clips->contains($clip)) {
            $this->clips->removeElement($clip);
        }

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
            $this->tracks[] = $track;
        }

        return $this;
    }

    public function removeTrack(Track $track): self
    {
        if ($this->tracks->contains($track)) {
            $this->tracks->removeElement($track);
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->addArtist($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            $event->removeArtist($this);
        }

        return $this;
    }

    /**
     * @return Collection|Album[]
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): self
    {
        if (!$this->albums->contains($album)) {
            $this->albums[] = $album;
            $album->addArtist($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        if ($this->albums->contains($album)) {
            $this->albums->removeElement($album);
            $album->removeArtist($this);
        }

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(?string $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public static function getAllDepartements() {
        return [
            self::LA,
            self::VE,
            self::ML,
            self::CA,
            self::FI,
            self::IV,
            self::MA,
            self::MO,
            self::SA
        ];
    }
}
