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
 * @ApiResource(normalizationContext={"groups"={"posts","comments"}},
 *     formats={"json"})
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"posts","comments"})
     */
    private $title;



    /**
     * @ORM\Column(type="text")
     * @Groups({"posts","comments"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"posts","comments"})
     */
    private $created_at;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="no")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"comments"})
     */
    private $created_by;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post")
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
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



    public function removeComments(Comment $comments): self
    {
        if ($this->comments->contains($comments)) {
            $this->comments->removeElement($comments);
            // set the owning side to null (unless already changed)
            if ($comments->getPost() === $this) {
                $comments->setPost(null);
            }
        }

        return $this;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }


    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }


    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }


}
