<?php

namespace App\Controller;


use DateTime;
use App\Entity\Client;
use App\Entity\Transaction;
use App\Entity\InfoTransaction;
use App\Repository\UserRepository;
use App\Repository\InfoTransactionRepository;
use App\Services\TransactionService;
use App\Repository\ComissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TransactionController extends AbstractController
{

    public function __construct(TransactionRepository $transRepo, UserRepository $userRepo, InfoTransactionRepository $infoTransactionRepo)
    {
        $this->transRepo = $transRepo;
        $this->userRepo = $userRepo;
        $this->infoTransactionRepo = $infoTransactionRepo;
    }

    /**
     * @Route(
     *      name="depotTransaction" ,
     *      path="/api/utilisateur/deposer/" ,
     *     methods={"POST"} ,
     *     defaults={
     *         "__controller"="App\Controller\TransactionController::depotTransaction",
     *         "_api_resource_class"=Transaction::class,
     *         "_api_collection_operation_name"="TransactionDepot"
     *     }
     *)
     */
    public function depotTransaction(
        TokenStorageInterface $tokenStorage,
        Request $request,
        TransactionService $transactionService,
        EntityManagerInterface $manager,
        SerializerInterface $serialize,
        ComissionRepository $commissionRepo
    ) {
        $commission = $commissionRepo->findAll();
        $commission =  $commission[0];
        $transaction = $request->getContent();
        $user = $tokenStorage->getToken()->getUser();
        $compte = $user->getAgence()->getCompte();
        $solde = $compte->getSolde();
        if ($solde < 5000) {
            return $this->json("Le solde compte est inférieur à 5000", 404);
        }

        $transaction = $serialize->decode($transaction, 'json');
        $transactionDepot = $serialize->denormalize($transaction, Transaction::class, true);

        $clientDepot = $serialize->denormalize($transaction['clientDepot'], Client::class, true);
        $clientRetrait = $serialize->denormalize($transaction['clientRetrait'], Client::class, true);
        $transactionDepot->setDateDepot(new \DateTime);
        $transactionDepot->setTTC($transactionService->calculFrais($transactionDepot->getMontant()));
        $transactionDepot->setFraisEtat($transactionDepot->getTTC() * $commission->getCommisionEtat() / 100);
        $transactionDepot->setFraisSystem($transactionDepot->getTTC() * $commission->getcommisionSystem() / 100);
        $transactionDepot->setFraisRetrait($transactionDepot->getTTC() * $commission->getcommsionRetrait() / 100);
        $transactionDepot->setFraisEnvoi($transactionDepot->getTTC() * $commission->getcommissionEnvoi() / 100);
        $transactionDepot->setCodeTransaction($transactionService->code());
        $transactionDepot->setCompte($compte);
        $transactionDepot->setMontantDepot($transactionDepot->getMontant() - $transactionDepot->getTTC());
        // dd($transactionDepot);
        $date = $transactionDepot->getDateDepot();
        $compte->setSolde(-$transactionDepot->getMontant() + $transactionDepot->getFraisEnvoi());
        $transactionDepot->setUserDepot($user);
        $manager->persist($clientDepot);
        $manager->persist($clientRetrait);

        $transactionDepot->setClientEnvoi($clientDepot);

        $transactionDepot->setClientRetrait($clientRetrait);

        // initialisation de transaction
        $infoTransaction = new InfoTransaction();
        $infoTransaction->setMontant($transactionDepot->getMontantDepot());
        $infoTransaction->setCompte($compte->getId());
        $infoTransaction->setType("depot");
        $infoTransaction->setUser($this->getUser()->getId());
        $infoTransaction->setDatetransaction($date);
        $infoTransaction->setNomClient($clientDepot->getNom());
        $infoTransaction->setPrenomClient($clientDepot->getPrenom());
        // dd($infoTransaction);
        $infoTransaction->setFrais($transactionDepot->getTTC());
        $infoTransaction->setCodeTransaction($transactionDepot->getCodeTransaction());
        $manager->persist($infoTransaction);

        $manager->persist($transactionDepot);
        $manager->flush();

        return new JsonResponse(["message" => "Dépot effectué!!", Response::HTTP_CREATED]);
    }


    /**
     * @Route(
     *      name="retraitTransaction" ,
     *      path="/api/utilisateur/retrait/" ,
     *     methods={"POST"} ,
     *     defaults={
     *         "__controller"="App\Controller\TransactionController::retraitTransaction",
     *         "_api_resource_class"=Transaction::class,
     *         "_api_collection_operation_name"="TransactionRetrait"
     *     }
     *)
     */
    public function retraitTransaction(
        TokenStorageInterface $tokenStorage,
        Request $request,
        EntityManagerInterface $manager,
        TransactionRepository $transRepo
    ) {
        $transaction = json_decode($request->getContent(), true);
        $user = $tokenStorage->getToken()->getUser();

        $transactionRetrait = $transRepo->findOneBy(['codeTransaction' => $transaction['codeTransaction']]);

        if (!$transactionRetrait) {
            throw new BadRequestHttpException("Code non valide!");
        } elseif ($transactionRetrait->getDateRetrait()) {
            throw new BadRequestHttpException("Le retrait est deja effectue!");
        }
        $transactionRetrait->setUserRetrait($user)
            ->setDateRetrait(new DateTime());
        $date = $transactionRetrait->getDateRetrait();
        $transactionRetrait->getClientRetrait()->setCNI($transaction['clientRetrait']['CNI']);

        $transactionRetrait->getUserRetrait()->getAgence()->getCompte()->setSolde(
            $transactionRetrait->getMontant() + $transactionRetrait->getFraisRetrait()
        );

        // initialisation de transaction
        $infoTransaction = new InfoTransaction();
        $infoTransaction->setMontant($transactionRetrait->getMontantDepot());
        $infoTransaction->setCompte($transactionRetrait->getUserRetrait()->getAgence()->getCompte()->getId());

        $infoTransaction->setType("retrait");
        $infoTransaction->setUser($this->getUser()->getId());
        $infoTransaction->setDatetransaction($date);
        $infoTransaction->setNomClient($transactionRetrait->getClientRetrait()->getNom());
        $infoTransaction->setPrenomClient($transactionRetrait->getClientRetrait()->getPrenom());
        // dd($infoTransaction);
        $infoTransaction->setFrais($transactionRetrait->getTTC());
        $infoTransaction->setCodeTransaction($transactionRetrait->getCodeTransaction());
        $manager->persist($infoTransaction);

        $manager->persist($transactionRetrait);
        $manager->flush();

        return new JsonResponse(["message" => "Retrait effectue avec succes!", Response::HTTP_CREATED]);
    }



    /**
     * @Route(
     *      name="annulerTransaction" ,
     *      path="/api/utilisateur/annuler/{id}" ,
     *     methods={"delete"} ,
     *     defaults={
     *         "__controller"="App\Controller\TransactionController::annulerTransaction",
     *         "_api_resource_class"=Transaction::class,
     *         "_api_collection_operation_name"="TransactionAnnuler"
     *     }
     *)
     */
    public function annulerTransaction(
        TokenStorageInterface $tokenStorage,
        Request $request,
        EntityManagerInterface $manager,
        SerializerInterface $serialize,
        TransactionRepository $transRepo
    ) {
        $transaction = json_decode($request->getContent(), true);
        $user = $tokenStorage->getToken()->getUser();

        $transactionAnnuler = $transRepo->findOneBy(['codeTransaction' => $transaction['codeTransaction']]);

        if (!$transactionAnnuler) {
            throw new BadRequestHttpException("Code non valide!");
        } elseif ($transactionAnnuler->getDateRetrait()) {
            throw new BadRequestHttpException("Le retrait est deja effectue!");
        } elseif ($transactionAnnuler->getDateAnnulation()) {
            throw new BadRequestHttpException("L'annulation est deja effectue!");
        }
        $transactionAnnuler->setUserDepot($user)
            ->setDateAnnulation(new DateTime());

        $transactionAnnuler->getUserDepot()->getAgence()->getCompte()->setSolde($transactionAnnuler->getMontant() - $transactionAnnuler->getFraisRetrait() * 30 / 100);
        // dd($transactionAnnuler);

        $manager->persist($transactionAnnuler);
        $manager->flush();

        return new JsonResponse(["message" => "Annulation effectuée avec succes!", Response::HTTP_CREATED]);
    }

    /**
     * @Route(
     *      name="getFrais" ,
     *      path="/api/adminAgence/frais/" ,
     *     methods={"post"},
     *      
     *)
     */
    public function getFrais(Request $request, TransactionService $transactionService)
    {
        $data = json_decode($request->getContent(), true);
        $montant = $transactionService->calculfrais($data['montant']);
        // dd($var);
        return $this->json($montant, 200);
    }


    /**
     * @Route(
     *      name="getTransaction" ,
     *      path="/api/adminAgence/transaction/" ,
     *      methods={"Post"},
     *      defaults={
     *         "__controller"="App\Controller\TransactionController::getTransaction",
     *         "_api_resource_class"=Transaction::class,
     *         "_api_collection_operation_name"="TransactionList"
     *     }
     *)
     */
    public function listTransaction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $transaction = $this->transRepo->findOneByCodeTransaction($data['code']);
        // dd($transaction);

        if (!$transaction) {
            return new JsonResponse(["message" => "ce code n'existe pas", Response::HTTP_CREATED]);
        }
        return $this->json($transaction, 200);
    }





    /**
     * @Route(
     *      name="transactionByCompte" ,
     *      path="/api/transactionByCompte" ,
     *     methods={"GET"}
     *)
     */
    public function transactionByCompte()
    {
        $compte = array();
        $idCompte = $this->getUser()->getAgence()->getCompte()->getId();
        // dd($idCompte);
        $comptes = $this->infoTransactionRepo->findAll();
        foreach ($comptes as $value) {
            if ($value->getCompte() == $idCompte) {
                array_push($compte, $value);
            }
        }
        return $this->json($compte, 200);
    }


    /**
     * @Route(
     *      name="transactionByUser" ,
     *      path="/api/transactionByUser" ,
     *      methods={"GET"},
     *       defaults={
     *         "__controller"="App\Controller\TransactionController::transactionByUser",
     *         "_api_resource_class"=Transaction::class,
     *         "_api_collection_operation_name"="transactionByUser"
     *     }
     *)
     */
    public function transactionByUser()
    {
        $transaction = array();
        $idUser = $this->getUser()->getId();
        // dd($idUser);
        $comptes = $this->infoTransactionRepo->findAll();
        foreach ($comptes as $value) {
            if ($value->getUser() == $idUser) {
                array_push($transaction, $value);
            }
        }
        return $this->json($transaction, 200);
    }
}
