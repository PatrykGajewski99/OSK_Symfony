<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
#[ORM\Table(name: 'organizations')]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('nip')]
class Organization
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['organization:read', 'user:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[OA\Property(description: 'Name of the organization', example: 'Example Organization Ltd.')]
    #[Groups(['organization:read', 'user:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 128)]
    #[OA\Property(description: 'Street name', example: 'Main Street')]
    #[Groups(['organization:read', 'user:read'])]
    private ?string $street = null;

    #[ORM\Column(length: 16)]
    #[OA\Property(description: 'House number', example: '123')]
    #[Groups(['organization:read', 'user:read'])]
    private ?string $houseNumber = null;

    #[ORM\Column(length: 16, nullable: true)]
    #[OA\Property(description: 'Flat number (optional)', example: '45', nullable: true)]
    #[Groups(['organization:read', 'user:read'])]
    private ?string $flatNumber = null;

    #[ORM\Column(length: 32)]
    #[OA\Property(description: 'Tax identification number', example: '1234567890')]
    #[Groups(['organization:read', 'user:read'])]
    private ?string $nip = null;

    #[ORM\Column(length: 128)]
    #[OA\Property(description: 'Country name', example: 'Poland')]
    #[Groups(['organization:read', 'user:read'])]
    private ?string $country = null;

    #[ORM\Column(type: 'datetime')]
    #[OA\Property(description: 'Creation timestamp', example: '2024-03-19T10:00:00+00:00')]
    #[Groups(['organization:read', 'user:read'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    #[OA\Property(description: 'Last update timestamp', example: '2024-03-19T10:00:00+00:00')]
    #[Groups(['organization:read', 'user:read'])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'organizations')]
    #[MaxDepth(1)]
    #[Groups(['organization:read'])]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(string $houseNumber): static
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    public function getFlatNumber(): ?string
    {
        return $this->flatNumber;
    }

    public function setFlatNumber(?string $flatNumber): static
    {
        $this->flatNumber = $flatNumber;

        return $this;
    }

    public function getNip(): ?string
    {
        return $this->nip;
    }

    public function setNip(string $nip): static
    {
        $this->nip = $nip;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[OA\Property(type: 'string', format: 'date-time')]
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTime();
        $this->setUpdatedAt();
    }

    #[ORM\PreUpdate]
    #[OA\Property(type: 'string', format: 'date-time')]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addOrganization($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeOrganization($this);
        }

        return $this;
    }
}
