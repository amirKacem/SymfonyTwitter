<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfileRepository")
 */
class Profile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $short_presentation;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profil_img;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $couverture_img;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pays;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="profiles")
     *
     */
    private $user_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShortPresentation(): ?string
    {
        return $this->short_presentation;
    }

    public function setShortPresentation(?string $short_presentation): self
    {
        $this->short_presentation = $short_presentation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProfilImg(): ?string
    {
        return $this->profil_img;
    }

    public function setProfilImg(?string $profil_img): self
    {
        $this->profil_img = $profil_img;

        return $this;
    }

    public function getCouvertureImg(): ?string
    {
        return $this->couverture_img;
    }

    public function setCouvertureImg(?string $couverture_img): self
    {
        $this->couverture_img = $couverture_img;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}
