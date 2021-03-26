<?php
namespace App\DataPersister;
use App\Entity\Compte;
use App\Repository\CompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

final class CompteDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(EntityManagerInterface $manager, Security $security, CompteRepository $compteRepo)
    {
        $this->manager = $manager;
        $this->security = $security;
        $this->compteRepo = $compteRepo;
    }
    
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Compte;
    }

    public function persist($data, array $context = [])
    {
        $data->setNumero($data->getNumero());
        
        $data->setUser($this->security->getUser());
        $compte = $this->manager->persist($data);
        $this->manager->flush($compte);
        return $data;
    }

    public function remove($data, array $context = [])
    {
        // $compte = $this->compteRepo->findOneById($data);
        $data->setArchivage(1);
        
        $this->manager->persist($data);
        $this->manager->flush();

        return $data;
    }
}