<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use OpenApi\Attributes as OA;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(name: 'idx_user_email', columns: ['email'])]
#[ORM\Index(name: 'idx_user_pesel', columns: ['pesel'])]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 64)]
    #[OA\Property(description: 'User first name', example: 'John')]
    private ?string $first_name = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[OA\Property(description: 'User second name', example: 'Robert')]
    private ?string $second_name = null;

    #[ORM\Column(length: 64)]
    #[OA\Property(description: 'User last name', example: 'Smith')]
    private ?string $last_name = null;

    #[ORM\Column(length: 255)]
    #[OA\Property(description: 'User email address', example: 'john.smith@example.com')]
    private ?string $email = null;

    #[ORM\Column(length: 11)]
    #[OA\Property(description: 'User PESEL number', example: '12345678901')]
    private ?string $pesel = null;

    #[ORM\Column(length: 255)]
    #[OA\Property(description: 'User password', example: '123Qwerty!')]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: Organization::class, inversedBy: 'users')]
    private Collection $organizations;

    #[ORM\Column(type: 'datetime')]
    #[OA\Property(description: 'Creation timestamp', example: '2024-03-19T10:00:00+00:00')]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: 'datetime')]
    #[OA\Property(description: 'Last update timestamp', example: '2024-03-19T10:00:00+00:00')]
    private ?\DateTimeInterface $updated_at = null;

    public function __construct()
    {
        $this->organizations = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getSecondName(): ?string
    {
        return $this->second_name;
    }

    public function setSecondName(?string $second_name): static
    {
        $this->second_name = $second_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPesel(): ?string
    {
        return $this->pesel;
    }

    public function setPesel(string $pesel): static
    {
        $this->pesel = $pesel;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
    
    public function getOrganizations(): Collection
    {
        return $this->organizations;
    }

    public function addOrganization(Organization $organization): static
    {
        if (!$this->organizations->contains($organization)) {
            $this->organizations->add($organization);
        }

        return $this;
    }

    public function removeOrganization(Organization $organization): static
    {
        $this->organizations->removeElement($organization);

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
}
