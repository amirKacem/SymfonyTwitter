<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 * fields={"username"},
 * errorPath="usename",
 * message="username already registered.")
 *@UniqueEntity(
 * fields={"email"},
 * errorPath="email",
 * message="email already registered.")
 */

class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *@ORM\Column(type="string", length=190,unique=true)
     * @Assert\NotBlank
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="created_by")
     */
    private $no;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private $lastname;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=150,nullable=true,unique=true)
     */
    private $email;





    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="comment_by")
     */
    private $comments;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Profile", cascade={"persist", "remove"})
     */
    private $profile;


    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="following")
     **/
    private $followers;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="followers")
     * @ORM\JoinTable(name="followers",
     * joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="following_user_id", referencedColumnName="id")}
     * )
     */
    private $following;

    public function __construct()
    {
        $this->no = new ArrayCollection();
        $this->profiles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();
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

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(User $follower): self
    {
        if (!$this->followers->contains($follower)) {
            $this->followers[] = $follower;
            $follower->addFollowing($this);
        }

        return $this;
    }

    public function removeFollower(User $follower): self
    {
        if ($this->followers->contains($follower)) {
            $this->followers->removeElement($follower);
            $follower->removeFollowing($this);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFollowing(): Collection
    {
        return $this->following;
    }

    public function addFollowing(User $following): self
    {
        if (!$this->following->contains($following)) {
            $this->following[] = $following;
        }

        return $this;
    }

    public function removeFollowing(User $following): self
    {
        if ($this->following->contains($following)) {
            $this->following->removeElement($following);
        }

        return $this;
    }
}
