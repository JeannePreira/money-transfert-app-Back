<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route(
     *      name="addUser" ,
     *      path="/api/adminSystem/user" ,
     *     methods={"POST"} ,
     *     defaults={
     *         "__controller"="App\Controller\UserController::addUser",
     *         "_api_resource_class"=User::class,
     *         "_api_collection_operation_name"="adUser"
     *     }
     *)
     */

    public function addUser(Request $request, UserService $userService, EntityManagerInterface $manager)
    {
        if (!($this->isGranted('ROLE_ADMIN-SYSTEM'))) {
            return $this->json(["message" => "Acces Refuse"], Response::HTTP_BAD_REQUEST);
        }

        $user = $userService->addUser($request);
        if (!($user instanceof User)) {
            return new JsonResponse($user, Response::HTTP_BAD_REQUEST);
        }
        $manager->persist($user);
        $manager->flush();

        return new JsonResponse(["message" => "User Add", Response::HTTP_CREATED]);
    }
}
