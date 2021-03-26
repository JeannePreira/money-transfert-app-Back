<?php
namespace App\DataPersister;
use App\Entity\Agence;
use App\Repository\UserRepository;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

final class AgenceDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(EntityManagerInterface $manager, Security $security, UserRepository $userRepo, AgenceRepository $agenceRepo)
    {
        $this->manager = $manager;
        $this->security = $security;
        $this->userRepo = $userRepo;
        $this->agenceRepo = $agenceRepo;
    }
    
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Agence;
    }

    public function persist($data, array $context = [])
    {
        $data->setNom($data->getNom());
        $data->setUser($data->getUsers());
        $compte = $this->manager->persist($data);
        $this->manager->flush($compte);
        return $data;
    }

    public function remove($data, array $context = [])
    {
        $agence = $this->agenceRepo->findOneById($data);
        
        $user = $agence->getUsers();
        
        foreach ($user as $value) {
            $agence->removeUser($value); 
            $user = $this->userRepo->findOneById($data);
            $user->setArchivage(1);
        }
        $this->manager->persist($data);
        $this->manager->flush();

        return $data;
    }
}