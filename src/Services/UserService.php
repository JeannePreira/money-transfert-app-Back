<?php


namespace App\Services;

use App\Repository\ProfilRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    public function __construct(
        UserPasswordEncoderInterface $encoder,
        SerializerInterface $serializer,
        ProfilRepository $profilRepo,
        ValidatorInterface $validator
    )
    {
        $this->serializer = $serializer;
        $this->encodePassword = $encoder;
        $this->profilRepo = $profilRepo;
        $this->validator = $validator;
    }

    public function givePassword($p="courageous")
    {
        return $p;
    }

    public function addUser($request, $profil=null)
    {
        $data = $request->request->all();
        $profil = $this->profilRepo->findOneBy(['libelle' => $data["profil"]]);
        
        unset($data['profil']);
        $user = $this->serializer->denormalize($data, "App\Entity\User", true);
        $user->setProfil($profil);
        // dd($user);
        $user->setArchivage(0);
        $avatar=$request->files->get("avatar");
        if ($avatar) {
            $avatarConverti= fopen($avatar->getRealPath(), "rb");
            $user->setAvatar($avatarConverti);
        }
        $plainPassword = $this->givePassword();
        $user->setPassword($plainPassword);
        $encoded = $this->encodePassword->encodePassword($user, $plainPassword);
        $user->setPassword($encoded);

        return $user;
    }
}