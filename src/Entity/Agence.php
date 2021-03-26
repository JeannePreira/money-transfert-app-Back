<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AgenceRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AgenceRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"agence:read"}},
 *      collectionOperations={
 *          "createAgence_adminS" = {
 *              "method" = "POST",
 *              "path" =  "/adminSystem/agence/",
 *              "security"="is_granted('ROLE_ADMIN-SYSTEM')",
 *              "security_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *      },
 *      itemOperations={
 *          "getCompte_adminS" = {
 *              "method" = "GET",
 *              "path" =  "/adminSystem/agence/{id}"
 *          },
 *          "getUsers_adminA" = {
 *              "method" = "get",
 *              "path" =  "/adminAgence/user/agence/{id}",
 *              "normalization_context"={"groups"={"agenceUsers:read"}},
 *          },
 *          "lockAgence_adminA" = {
 *              "method" = "delete",
 *              "path" =  "/adminAgence/agence/{id}",
 *              "normalization_context"={"groups"={"agenceUsers:read"}},
 *          }
 *      }
 * )
 * @UniqueEntity(
 *   fields={"nom"},
 *   message="Le nom doit être unique"
 * )
 */
class Agence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"agence:read", "compte:write", "users:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"agence:read", "agenceUsers:read"})
     * @Assert\NotBlank(message="Le Nom est obligatoire")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"agence:read"})
     * @Assert\NotBlank(message="L'adresse est obligatoire")
     */
    private $adresse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="agence", cascade={"persist", "remove"})
     * @Groups({"agenceUsers:read"})
     */
    private $users;

    /**
     * @ORM\OneToOne(targetEntity=Compte::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $compte;

    public function __construct()
    {
        $this->status = 0;
        $this->users = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->statut = isset($statut) ? $statut:false;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAgence($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAgence() === $this) {
                $user->setAgence(null);
            }
        }

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

}
