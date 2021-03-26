<?php


namespace App\Services;

use App\Repository\UserRepository;
use App\Repository\FraisRepository;
use App\Repository\ClientRepository;

use App\Repository\CompteRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TransactionService
{
    public function __construct(
        UserPasswordEncoderInterface $encoder,
        SerializerInterface $serializer,
        ClientRepository $clientRepo,
        UserRepository $userRepo,
        FraisRepository $fraisRepo,
        CompteRepository $compteRepo,
        ValidatorInterface $validator
    ) {
        $this->serializer = $serializer;
        $this->encodePassword = $encoder;
        $this->clientRepo = $clientRepo;
        $this->userRepo = $userRepo;
        $this->fraisRepo = $fraisRepo;
        $this->compteRepo = $compteRepo;
        $this->validator = $validator;
    }

    public function calculfrais($montant)
    {
        $frais = $this->fraisRepo->findAll();
        $taxe = 0;
        foreach ($frais as $value) {
            switch ($montant) {
                case ($montant >= $value->getMin() && $montant < $value->getMax()):
                    $taxe = $value->getFrais();
                    if ($taxe == 0.02) {
                        $taxe = $montant * 0.02;
                    }
            }
        }
        return $taxe;
    }


    function code()
    {
        $chars = '0123456789';
        $string = '';
        for ($i = 0; $i < 9; $i++) {

            $string .= $chars[rand(0, strlen($chars) - 1)];
            if ($i == 2 || $i == 5) {
                $string .= '-';
            }
        }
        return $string;
    }


    public function addTransaction($request)
    {
        $data = $request->request->all();
        $client = $this->clientRepo->findOneBy(['username' => $data["client"]]);
        $user = $this->userRepo->findOneBy(['username' => $data["user"]]);
        $compte = $this->compteRepo->findOneBy(['numero' => $data["compte"]]);

        unset($data['profil']);
        $transaction = $this->serializer->denormalize($data, "App\Entity\Transaction", true);
        $transaction->setClient($client);
        $transaction->setCompte($compte);
        $transaction->setUser($user);
        // dd($user);
        $transaction->setDateAnnulation(0);


        return $transaction;
    }
}
