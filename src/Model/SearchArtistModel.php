<?php


namespace App\Model;


use App\Entity\Album;
use App\Entity\Clip;
use App\Entity\Track;

class SearchArtistModel
{
    /**
     * @var string
     */
    private $nom;

    /**
     * @var Clip
     */
    private $clip;

    /**
     * @var Track
     */
    private $track;

    /**
     * @var Album
     */
    private $album;

    /**
     * @return string
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return Clip
     */
    public function getClip(): ?Clip
    {
        return $this->clip;
    }

    /**
     * @param Clip $clip
     */
    public function setClip(Clip $clip): void
    {
        $this->clip = $clip;
    }

    /**
     * @return Track
     */
    public function getTrack(): ?Track
    {
        return $this->track;
    }

    /**
     * @param Track $track
     */
    public function setTrack(Track $track): void
    {
        $this->track = $track;
    }

    /**
     * @return Album
     */
    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    /**
     * @param Album $album
     */
    public function setAlbum(Album $album): void
    {
        $this->album = $album;
    }

    /**
     * @return string
     */
    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    /**
     * @param string $departement
     */
    public function setDepartement(string $departement): void
    {
        $this->departement = $departement;
    }

    /**
     * @var string
     */
    private $departement;

}