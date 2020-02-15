<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ApiResource(normalizationContext={"groups"={"posts"}})
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"posts"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"posts"})
     */
    private $description;


    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="no")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"posts"})
     */
    private $created_by;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post")
     * @Groups({"posts"})
     */
    private $yes;

    public function __construct()
    {
        $this->yes = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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



    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getYes(): Collection
    {
        return $this->yes;
    }

    public function addYe(Comment $ye): self
    {
        if (!$this->yes->contains($ye)) {
            $this->yes[] = $ye;
            $ye->setPost($this);
        }

        return $this;
    }

    public function removeYe(Comment $ye): self
    {
        if ($this->yes->contains($ye)) {
            $this->yes->removeElement($ye);
            // set the owning side to null (unless already changed)
            if ($ye->getPost() === $this) {
                $ye->setPost(null);
            }
        }

        return $this;
    }


}
