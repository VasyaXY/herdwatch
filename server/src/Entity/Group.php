<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
#[UniqueEntity(fields: 'name', message: 'name {{ value }} already exists')]
class Group implements \JsonSerializable
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
    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'myGroups')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getMyUsersArray(): array
    {
        $ret = [];
        foreach ($this->getUsers() as $myUsers) {
            $ret[] = $myUsers->jsonSerializeForGroup();
        }
        return $ret;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'users' => $this->getMyUsersArray()
        );
    }

    public function jsonSerializeNoUsers(): array
    {
        return array(
            'id' => $this->id,
            'name' => $this->name
        );
    }

}
