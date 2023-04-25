<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: 'email', message: 'email {{ value }} already exists')]
class User implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: '`name` cannot be blank')]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: '`name` must be at least {{ limit }} characters long',
        maxMessage: '`name` cannot be longer than {{ limit }} characters'
    )]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotBlank(message: '`email` cannot be blank')]
    #[Assert\Email(message: 'The `email` {{ value }} is not a valid email.',)]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: '`email` must be at least {{ limit }} characters long',
        maxMessage: '`email` cannot be longer than {{ limit }} characters'
    )]
    ##[Assert\Unique]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'users')]
    private Collection $myGroups;

    public function __construct()
    {
        $this->myGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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
     * @return Collection<int, Group>
     */
    public function getMyGroups(): Collection
    {
        return $this->myGroups;
    }

    public function getMyGroupsArray(): array
    {
        $ret = [];
        foreach ($this->getMyGroups() as $myGroup) {
            $ret[] = $myGroup->jsonSerializeNoUsers();
        }
        return $ret;
    }

    public function addMyGroup(Group $myGroup): self
    {
        if (!$this->myGroups->contains($myGroup)) {
            $this->myGroups->add($myGroup);
            $myGroup->addUser($this);
        }

        return $this;
    }

    public function removeMyGroup(Group $myGroup): self
    {
        if ($this->myGroups->removeElement($myGroup)) {
            $myGroup->removeUser($this);
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'groups' => $this->getMyGroupsArray()
        );
    }

    public function jsonSerializeForGroup(): array
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email
        );
    }
}
