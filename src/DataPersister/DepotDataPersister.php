<?php
namespace App\DataPersister;
use App\Entity\Depot;
use App\Repository\DepotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

final class DepotDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(EntityManagerInterface $manager, Security $security, DepotRepository $depotRepo)
    {
        $this->manager = $manager;
        $this->security = $security;
        $this->depotRepo = $depotRepo;
    }
    
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Depot;
    }

    public function persist($data, array $context = [])
    {
        
        return $data;
    }

    public function remove($data, array $context = [])
    {
        
       
        $data->getCompte()->setSolde(-$data->getMontant());
        // dd($data);
        $this->manager->remove($data);
        $this->manager->flush();

        return $data;
    }
}