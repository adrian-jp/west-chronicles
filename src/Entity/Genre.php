<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 */
class Genre
{
    const BOOM_BAP = 'Boom-Bap';
    const RNB = 'R\'n B';
    const WEST = 'West-side';
    const TRAP = 'Trap';
    const DANCEHALL = 'Dancehall';
    const CLOUD = 'Cloud Trap';
    const NUSOUL = 'New Soul';
    const ELECTRO = 'Electro';
    const ALL_GENRES = [
        Genre::BOOM_BAP,
        Genre::CLOUD,
        Genre::RNB,
        Genre::WEST,
        Genre::TRAP,
        Genre::DANCEHALL,
        Genre::NUSOUL,
        Genre::ELECTRO
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Clip::class, mappedBy="genre")
     */
    private $clips;

    /**
     * @ORM\OneToMany(targetEntity=Track::class, mappedBy="genre")
     */
    private $tracks;

    public function __construct()
    {
        $this->clips = new ArrayCollection();
        $this->tracks = new ArrayCollection();
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
            $clip->setGenre($this);
        }

        return $this;
    }

    public function removeClip(Clip $clip): self
    {
        if ($this->clips->contains($clip)) {
            $this->clips->removeElement($clip);
            // set the owning side to null (unless already changed)
            if ($clip->getGenre() === $this) {
                $clip->setGenre(null);
            }
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
            $track->setGenre($this);
        }

        return $this;
    }

    public function removeTrack(Track $track): self
    {
        if ($this->tracks->contains($track)) {
            $this->tracks->removeElement($track);
            // set the owning side to null (unless already changed)
            if ($track->getGenre() === $this) {
                $track->setGenre(null);
            }
        }

        return $this;
    }

    public static function getGenresList() {
        return array(
            self::NUSOUL,
            self::DANCEHALL,
            self::TRAP,
            self::WEST,
            self::RNB,
            self::CLOUD,
            self::BOOM_BAP,
            self::ELECTRO
        );
    }
}
