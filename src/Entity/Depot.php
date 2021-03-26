<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DepotRepository;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DepotRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "depotCompte_cassier" = {
 *              "method" = "POST",
 *              "path" =  "/cassier/depot/",
 *              "denormalization_context"={"groups"={"cassier:write"}},
 *              "security" = "is_granted('ROLE_ADMIN_AGENCE')",
 *              "security_message" = "Seules les admin ont accèes à cette ressource!"
 *          }
 *       },
 *        itemOperations={
 *           "annulerDepot_cassier" = {
 *              "method" = "DELETE",
 *              "path" =  "/cassier/depot/{id}"
 *              
 *          }   
 *        }
 * 
 * )
 */
class Depot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"cassier:write"})
     * @Assert\NotBlank(message="La date est obligatoire")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"cassier:write"})
     * @Assert\NotBlank(message="Le montant est obligatoire")
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="depot")
     * @Groups({"cassier:write"})
     * @Assert\NotBlank(message="Le compte est obligatoire")
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="depot")
     * @Groups({"cassier:write"})
     * @Assert\NotBlank(message="L'utilisateur est obligatoire")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

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
}
