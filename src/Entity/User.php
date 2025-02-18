<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Uid\Uuid;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(name: 'idx_user_email', columns: ['email'])]
#[ORM\Index(name: 'idx_user_pesel', columns: ['pesel'])]
#[UniqueEntity(['email'])]
class User implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['user:read', 'organization:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 64)]
    #[OA\Property(description: 'User first name', example: 'John')]
    #[Groups(['user:read', 'organization:read'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[OA\Property(description: 'User second name', example: 'Robert')]
    #[Groups(['user:read', 'organization:read'])]
    private ?string $secondName = null;

    #[ORM\Column(length: 64)]
    #[OA\Property(description: 'User last name', example: 'Smith')]
    #[Groups(['user:read', 'organization:read'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[OA\Property(description: 'User email address', example: 'john.smith@example.com')]
    #[Groups(['user:read', 'organization:read'])]
    private ?string $email = null;

    #[Ignore]
    #[ORM\Column(length: 64)]
    #[OA\Property(description: 'User PESEL number', example: '12345678901')]
    private ?string $pesel = null;

    #[Ignore]
    #[ORM\Column(length: 255)]
    #[OA\Property(description: 'User password', example: '123Qwerty!')]
    private ?string $password = null;

    #[MaxDepth(1)]
    #[ORM\ManyToMany(targetEntity: Organization::class, inversedBy: 'users')]
    #[Groups(['user:read', 'organization:read'])]
    private Collection $organizations;

    #[ORM\Column(type: 'datetime')]
    #[OA\Property(description: 'Creation timestamp', example: '2024-03-19T10:00:00+00:00')]
    #[Groups(['user:read', 'organization:read'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    #[OA\Property(description: 'Last update timestamp', example: '2024-03-19T10:00:00+00:00')]
    #[Groups(['user:read'])]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var Collection<int, Role>
     */
    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    private Collection $roles;

    public function __construct()
    {
        $this->organizations = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getSecondName(): ?string
    {
        return $this->secondName;
    }

    public function setSecondName(?string $secondName): static
    {
        $this->secondName = $secondName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

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

    /**
     * @return Collection<int, Role>
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): static
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
    }

    public function removeRole(Role $role): static
    {
        $this->roles->removeElement($role);

        return $this;
    }
}
