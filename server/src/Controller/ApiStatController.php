<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiStatController extends AbstractController
{
    #[Route('/stat/groups')]
    public function apiGetGroup(
        Request         $request,
        GroupRepository $groupRepository
    ): JsonResponse
    {
        $groups = $groupRepository->findAll();
        return new JsonResponse(['success' => true, 'code' => 1, 'groups' => $groups]);
    }

    #[Route('/stat/users')]
    public function apiGetUsers(
        Request        $request,
        UserRepository $userRepository
    ): JsonResponse
    {
        $users = $userRepository->findAll();

        return new JsonResponse(['success' => true, 'code' => 1, 'users' => $users]);
    }
}