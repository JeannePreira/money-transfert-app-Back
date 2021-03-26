<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompteRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CompteRepository::class)
 * @ApiResource(
 *       attributes = {
 *         "security" = "is_granted('ROLE_ADMIN-SYSTEM')",
 *         "security_message" = "Seules les admin ont accèes à cette ressource!"
 *      },
 *      collectionOperations={
 *          "createCompte_adminS" = {
 *              "method" = "POST",
 *              "path" =  "/adminSystem/compte/",
 *              "denormalization_context"={"groups"={"compte:write"}}, 
 *              "security" = "is_granted('ROLE_ADMIN-SYSTEM')",
 *              "security_message" = "Seules les admin ont accèes à cette ressource!"    
 *          }
 *      },
 *       itemOperations={
 *          "getCompte_adminS" = {
 *              "method" = "GET",
 *              "path" =  "/adminSystem/compte/{id}",
 *              "normalization_context"={"groups"={"compte:read"}},
 *          },
 *          "updateCompte_adminA" = {
 *              "method" = "put",
 *              "path" =  "/adminSystem/compte/{id}",
 *              "normalization_context"={"groups"={"compte:write"}},
 *          },
 *          "lockCompte_adminS" = {
 *              "method" = "delete",
 *              "path" =  "/adminSystem/compte/{id}",
 *              "normalization_context"={"groups"={"compte:read"}},
 *          }
 *      }
 * )
 *  @UniqueEntity(
 *   fields={"numero"},
 *   message="Le libelle doit être unique"
 * )
 */
class Compte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"cassier:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le numero est obligatoire")
     * @Groups({"compte:read", "compte:write"})
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le Nom est obligatoire")
     * @Groups({"compte:read", "compte:write"})
     */
    private $solde;

    /**
     * @ORM\OneToMany(targetEntity=Depot::class, mappedBy="compte")
     * @Groups({"compte:read"})
     */
    private $depot;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="compte")
     */
    private $transaction;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="adminSysteme")
     * @Groups({"compte:read", "compte:write"})
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archivage;

    public function __construct()
    {
        $this->archivage = 0;
        $this->solde = 700000;
        $this->depot = new ArrayCollection();
        $this->transaction = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getSolde(): ?string
    {
        return $this->solde;
    }

    public function setSolde(string $solde): self
    {
        $this->solde += $solde;

        return $this;
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
            $depot->setCompte($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depot->removeElement($depot)) {
            // set the owning side to null (unless already changed)
            if ($depot->getCompte() === $this) {
                $depot->setCompte(null);
            }
        }

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
            $transaction->setCompte($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transaction->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCompte() === $this) {
                $transaction->setCompte(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(bool $archivage): self
    {
        $this->archivage = isset($archivage) ? $archivage:false;

        return $this;
    }
}
