<?php

namespace App\Entity;

use App\Repository\ComissionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComissionRepository::class)
 */
class Comission
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $commissionEnvoi;

    /**
     * @ORM\Column(type="integer")
     */
    private $commsionRetrait;

    /**
     * @ORM\Column(type="integer")
     */
    private $commisionSystem;

    /**
     * @ORM\Column(type="integer")
     */
    private $commisionEtat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommissionEnvoi(): ?int
    {
        return $this->commissionEnvoi;
    }

    public function setCommissionEnvoi(int $commissionEnvoi): self
    {
        $this->commissionEnvoi = $commissionEnvoi;

        return $this;
    }

    public function getCommsionRetrait(): ?int
    {
        return $this->commsionRetrait;
    }

    public function setCommsionRetrait(int $commsionRetrait): self
    {
        $this->commsionRetrait = $commsionRetrait;

        return $this;
    }

    public function getCommisionSystem(): ?int
    {
        return $this->commisionSystem;
    }

    public function setCommisionSystem(int $commisionSystem): self
    {
        $this->commisionSystem = $commisionSystem;

        return $this;
    }

    public function getCommisionEtat(): ?int
    {
        return $this->commisionEtat;
    }

    public function setCommisionEtat(int $commisionEtat): self
    {
        $this->commisionEtat = $commisionEtat;

        return $this;
    }
}
