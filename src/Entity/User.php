<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: 'login', message: 'Этот логин уже используется')]
#[UniqueEntity(fields: 'email', message: 'Этот email уже используется')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $login = null;

    #[ORM\Column(length: 60)]
    private ?string $name = null;

    #[ORM\Column(length: 60)]
    private ?string $surname = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $patronymic = null;

    #[ORM\Column(length: 15)]
    private ?string $phoneNumber = null;

    /**
     * @var Collection<int, Reviews>
     */
    #[ORM\OneToMany(targetEntity: Reviews::class, mappedBy: 'users', orphanRemoval: true)]
    private Collection $reviews;

    /**
     * @var Collection<int, CompareSection>
     */
    #[ORM\OneToMany(targetEntity: CompareSection::class, mappedBy: 'user')]
    private Collection $compareSections;

    /**
     * @var Collection<int, UserEntries>
     */
    #[ORM\OneToMany(targetEntity: UserEntries::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $userEntries;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->compareSections = new ArrayCollection();
        $this->userEntries = new ArrayCollection();
    }

    public function __toString(): string
    {
       return $this->login;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(string $patronymic): static
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection<int, Reviews>
     */
    public function getFeedBack(): Collection
    {
        return $this->feedBack;
    }

    public function addFeedBack(Reviews $feedBack): static
    {
        if (!$this->feedBack->contains($feedBack)) {
            $this->feedBack->add($feedBack);
            $feedBack->setUserId($this);
        }

        return $this;
    }

    public function removeFeedBack(Reviews $feedBack): static
    {
        if ($this->feedBack->removeElement($feedBack)) {
            // set the owning side to null (unless already changed)
            if ($feedBack->getUserId() === $this) {
                $feedBack->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reviews>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Reviews $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setUsers($this);
        }

        return $this;
    }

    public function removeReview(Reviews $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUsers() === $this) {
                $review->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CompareSection>
     */
    public function getCompareSections(): Collection
    {
        return $this->compareSections;
    }

    public function addCompareSection(CompareSection $compareSection): static
    {
        if (!$this->compareSections->contains($compareSection)) {
            $this->compareSections->add($compareSection);
            $compareSection->setUser($this);
        }

        return $this;
    }

    public function removeCompareSection(CompareSection $compareSection): static
    {
        if ($this->compareSections->removeElement($compareSection)) {
            // set the owning side to null (unless already changed)
            if ($compareSection->getUser() === $this) {
                $compareSection->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserEntries>
     */
    public function getUserEntries(): Collection
    {
        return $this->userEntries;
    }

    public function addUserEntry(UserEntries $userEntry): static
    {
        if (!$this->userEntries->contains($userEntry)) {
            $this->userEntries->add($userEntry);
            $userEntry->setUser($this);
        }

        return $this;
    }

    public function removeUserEntry(UserEntries $userEntry): static
    {
        if ($this->userEntries->removeElement($userEntry)) {
            // set the owning side to null (unless already changed)
            if ($userEntry->getUser() === $this) {
                $userEntry->setUser(null);
            }
        }

        return $this;
    }
}
