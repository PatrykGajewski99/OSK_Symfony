<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
#[ORM\Table(name: 'organizations')]
#[ORM\HasLifecycleCallbacks]
class Organization
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[OA\Property(description: 'Name of the organization', example: 'Example Organization Ltd.')]
    private ?string $name = null;

    #[ORM\Column(length: 128)]
    #[OA\Property(description: 'Street name', example: 'Main Street')]
    private ?string $street = null;

    #[ORM\Column(length: 16)]
    #[OA\Property(description: 'House number', example: '123')]
    private ?string $house_number = null;

    #[ORM\Column(length: 16, nullable: true)]
    #[OA\Property(description: 'Flat number (optional)', example: '45', nullable: true)]
    private ?string $flat_number = null;

    #[ORM\Column(length: 32)]
    #[OA\Property(description: 'Tax identification number', example: '1234567890')]
    private ?string $nip = null;

    #[ORM\Column(length: 128)]
    #[OA\Property(description: 'Country name', example: 'Poland')]
    private ?string $country = null;

    #[ORM\Column(type: 'datetime')]
    #[OA\Property(description: 'Creation timestamp', example: '2024-03-19T10:00:00+00:00')]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: 'datetime')]
    #[OA\Property(description: 'Last update timestamp', example: '2024-03-19T10:00:00+00:00')]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'organizations')]
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
        return $this->house_number;
    }

    public function setHouseNumber(string $house_number): static
    {
        $this->house_number = $house_number;

        return $this;
    }

    public function getFlatNumber(): ?string
    {
        return $this->flat_number;
    }

    public function setFlatNumber(?string $flat_number): static
    {
        $this->flat_number = $flat_number;

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
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    #[ORM\PrePersist]
    #[OA\Property(type: 'string', format: 'date-time')]
    public function setCreatedAt(): void
    {
        $this->created_at = new \DateTime();
        $this->setUpdatedAt();
    }

    #[ORM\PreUpdate]
    #[OA\Property(type: 'string', format: 'date-time')]
    public function setUpdatedAt(): void
    {
        $this->updated_at = new \DateTime();
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
