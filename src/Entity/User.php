<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 
 * @ApiResource(
 *        attributes={
 *          "force_eager"=false,
 *       },
 *       collectionOperations={
 *          "adUser" = {
 *              "method":"POST",
 *              "path" =  "/adminSystem/user",
 *              "denormalization_context"={"groups": {"users:write"}},
 *              "deserialize" = false,
 *              "route_name" = "addUser"
 *          },
 *          "TransactionList" = {
 *              "method":"GET",
 *              "path" =  "/adminSystem/user",
 *              "normalization_context"={"groups"={"user:read"}},
 *          },
 *          "getCaissiers_adminS" = {
 *              "method" = "get",
 *              "path" =  "/adminSystem/cassier/",
 *              "normalization_context"={"groups"={"cassier:read"}},
 *          },
 *      },
 *       itemOperations={
 *          "getUser_adminS" = {
 *              "method" = "get",
 *              "path" =  "/adminSystem/user/{id}",
 *              "normalization_context"={"groups"={"user:read"}},
 *          },
 *          "lockCaissier_adminS" = {
 *              "method" = "delete",
 *              "path" =  "/adminSystem/user/{id}",
 *              "normalization_context"={"groups"={"user:read"}},
 *          },
 *          "lockUser_adminA" = {
 *              "method" = "delete",
 *              "path" =  "/adminAgence/user/{id}",
 *              "normalization_context"={"groups"={"user:read"}},
 *          },
 *      },
 * )
 * @ApiFilter(SearchFilter::class, properties={"profil": "exact", "username": "exact"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"agenceUsers:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"users:read", "cassier:read", "agenceUsers:read", "user:read"})
     */
    private $username;


    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"agenceUsers:read"})
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Depot::class, mappedBy="user")
     */
    private $depot;

    /**
     * @ORM\OneToMany(targetEntity=Compte::class, mappedBy="user")
     */
    private $adminSysteme;

    /**
     * @ORM\ManyToOne(targetEntity=Agence::class, inversedBy="users")
     * @Groups({"user:read"})
     */
    private $agence;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @Groups({"users:read", "cassier:read", "agenceUsers:read"})
     */
    private $profil;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users:read", "cassier:read", "agenceUsers:read", "user:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users:read", "cassier:read", "agenceUsers:read", "user:read"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"users:read", "cassier:read", "agenceUsers:read", "user:read"})
     */
    private $phone;

    /**
     * @ORM\Column(type="integer", length=255)
     * @Groups({"users:read", "cassier:read", "agenceUsers:read", "user:read"})
     */
    private $cni;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"users:read", "cassier:read"})
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users:read", "cassier:read", "agenceUsers:read", "user:read"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archivage;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="userDepot")
     * @Groups({"user:read", "users:write", "transaction:read"})
     */
    private $transaction;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="userRetrait")
     * @Groups({"user:read"})
     */
    private $transactionRetrait;

    public function __construct()
    {
        $this->depot = new ArrayCollection();
        $this->adminSysteme = new ArrayCollection();
        $this->agence = new ArrayCollection();
        $this->retrait = new ArrayCollection();
        $this->deposer = new ArrayCollection();
        $this->transaction = new ArrayCollection();
        $this->transactionRetrait = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_' . $this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Depot[]
     */
    public function getDepot(): Collection
    {
        return $this->depot;
    }

    public function addDepot(Depot $depot): self
    {
        if (!$this->depot->contains($depot)) {
            $this->depot[] = $depot;
            $depot->setUser($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depot->removeElement($depot)) {
            // set the owning side to null (unless already changed)
            if ($depot->getUser() === $this) {
                $depot->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Compte[]
     */
    public function getAdminSysteme(): Collection
    {
        return $this->adminSysteme;
    }

    public function addAdminSysteme(Compte $adminSysteme): self
    {
        if (!$this->adminSysteme->contains($adminSysteme)) {
            $this->adminSysteme[] = $adminSysteme;
            $adminSysteme->setUser($this);
        }

        return $this;
    }

    public function removeAdminSysteme(Compte $adminSysteme): self
    {
        if ($this->adminSysteme->removeElement($adminSysteme)) {
            // set the owning side to null (unless already changed)
            if ($adminSysteme->getUser() === $this) {
                $adminSysteme->setUser(null);
            }
        }

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        $this->agence = $agence;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCni(): ?int
    {
        return $this->cni;
    }

    public function setCni(int $cni): self
    {
        $this->cni = $cni;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(bool $archivage): self
    {
        $this->archivage = $archivage;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransaction(): Collection
    {
        return $this->transaction;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transaction->contains($transaction)) {
            $this->transaction[] = $transaction;
            $transaction->setUserDepot($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transaction->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getUserDepot() === $this) {
                $transaction->setUserDepot(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactionRetrait(): Collection
    {
        return $this->transactionRetrait;
    }

    public function addTransactionRetrait(Transaction $transactionRetrait): self
    {
        if (!$this->transactionRetrait->contains($transactionRetrait)) {
            $this->transactionRetrait[] = $transactionRetrait;
            $transactionRetrait->setUserRetrait($this);
        }

        return $this;
    }

    public function removeTransactionRetrait(Transaction $transactionRetrait): self
    {
        if ($this->transactionRetrait->removeElement($transactionRetrait)) {
            // set the owning side to null (unless already changed)
            if ($transactionRetrait->getUserRetrait() === $this) {
                $transactionRetrait->setUserRetrait(null);
            }
        }

        return $this;
    }
}
