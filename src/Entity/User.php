<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ApiResource
 */
class User implements UserInterface
{
    /**
     * @Groups("posts")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *@ORM\Column(type="string", length=190,unique=true)
     *
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="created_by")
     * @Groups("posts")
     */
    private $no;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("posts")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"posts"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="array")
     *
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=150,nullable=true,unique=true)
     */
    private $email;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Profile", mappedBy="user_id")
     */
    private $profiles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="comment_by")
     * @Groups({"posts"})
     */
    private $comments;

    public function __construct()
    {
        $this->no = new ArrayCollection();
        $this->profiles = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @inheritDoc
     */

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }


    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        // TODO: Implement getPassword() method.
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {

    }



    /**
     * @return Collection|Post[]
     */
    public function getNo(): Collection
    {
        return $this->no;
    }

    public function addNo(Post $no): self
    {
        if (!$this->no->contains($no)) {
            $this->no[] = $no;
            $no->setCreatedBy($this);
        }

        return $this;
    }

    public function removeNo(Post $no): self
    {
        if ($this->no->contains($no)) {
            $this->no->removeElement($no);
            // set the owning side to null (unless already changed)
            if ($no->getCreatedBy() === $this) {
                $no->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }



    /**
     * @return Collection|Profile[]
     */
    public function getProfiles(): Collection
    {
        return $this->profiles;
    }

    public function addProfile(Profile $profile): self
    {
        if (!$this->profiles->contains($profile)) {
            $this->profiles[] = $profile;
            $profile->setUserId($this);
        }

        return $this;
    }

    public function removeProfile(Profile $profile): self
    {
        if ($this->profiles->contains($profile)) {
            $this->profiles->removeElement($profile);
            // set the owning side to null (unless already changed)
            if ($profile->getUserId() === $this) {
                $profile->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setCommentBy($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getCommentBy() === $this) {
                $comment->setCommentBy(null);
            }
        }

        return $this;
    }
}
