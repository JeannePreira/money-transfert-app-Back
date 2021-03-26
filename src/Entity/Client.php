<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "getClient_adminA" = {
 *              "method" = "GET",
 *              "path" =  "/adminAgence/client/",
 *              "security"="is_granted('ROLE_ADMIN-AGENCE')",
 *              "security_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *      }
 * )
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"deposer:write", "retrait:write", "transaction:read"})
     */
    private $CNI;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"deposer:write", "retrait:write", "transaction:read"})
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="clientEnvoi")
     */
    private $transaction;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="clientRetrait")
     */
    private $transactionRetrait;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"part:read", "deposer:write", "retrait:write", "impression:read", "transaction:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"part:read", "deposer:write", "retrait:write", "impression:read", "transaction:read"})
     */
    private $prenom;

    public function __construct()
    {
        $this->envoi = new ArrayCollection();
        $this->recuperer = new ArrayCollection();
        $this->transaction = new ArrayCollection();
        $this->transactionRetrait = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCNI(): ?string
    {
        return $this->CNI;
    }

    public function setCNI(string $CNI): self
    {
        $this->CNI = $CNI;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

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
            $transaction->setClientEnvoi($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transaction->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getClientEnvoi() === $this) {
                $transaction->setClientEnvoi(null);
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
            $transactionRetrait->setClientRetrait($this);
        }

        return $this;
    }

    public function removeTransactionRetrait(Transaction $transactionRetrait): self
    {
        if ($this->transactionRetrait->removeElement($transactionRetrait)) {
            // set the owning side to null (unless already changed)
            if ($transactionRetrait->getClientRetrait() === $this) {
                $transactionRetrait->setClientRetrait(null);
            }
        }

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
}
