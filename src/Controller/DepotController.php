<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Entity\Compte;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class DepotController extends AbstractController
{
     /**
     * @Route(
     *      name="depotCompte" ,
     *      path="api/cassier/depot/" ,
     *     methods={"POST"} ,
     *     defaults={
     *         "__controller"="App\Controller\DepotController::depotCompte",
     *         "_api_resource_class"=Depot::class,
     *         "_api_collection_operation_name"="depotCompte_cassier"
     *     }
     *)
    */

    public function depotCompte( TokenStorageInterface $tokenStorage,Request $request , EntityManagerInterface $manager, SerializerInterface $serialize) {
        $depot = $request->getContent();
        $cassier = $tokenStorage->getToken()->getUser();
        $compte = $cassier->getAgence()->getCompte();
        // dd($compte);
        $depot = $serialize->decode($depot, 'json');
        $depotCaissier = $serialize->denormalize($depot, Depot::class, true);
        $depotCaissier->setUser($cassier);
        $depotCaissier->setDate(new \DateTime);

        $depotCaissier->setCompte($compte);
        $compte->setSolde($compte->getSolde()+$depotCaissier->getMontant());
        // dd($depotCaissier);
        
       $manager->persist($depotCaissier);
       $manager->flush();

       return new JsonResponse(["message"=>"Dépot effectué!!", Response::HTTP_CREATED]);
    }

}
