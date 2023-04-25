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

class ApiGroupController extends AbstractController
{
    #[Route('/group/get', methods: ['GET', 'POST'])]
    public function apiGetGroup(
        Request         $request,
        GroupRepository $groupRepository
    ): JsonResponse
    {
        if ($request->get('name') && is_string($request->get('name'))) {
            $group = $groupRepository->findOneBy([
                'name' => $request->get('name')
            ]);
            if ($group) {
                return new JsonResponse(['success' => true, 'code' => 1, 'group' => $group]);
            } else {
                return new JsonResponse(['success' => false, 'code' => -2, 'error' => 'not found']);
            }
        } else
            return new JsonResponse(['success' => false, 'code' => -1, 'error' => 'empty or incorrect format name']);
    }

    #[Route('/group/add', methods: ['GET', 'POST'])]
    public function apiAddGroup(
        Request            $request,
        GroupRepository    $groupRepository,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $err = [];

        $group = new Group();
        $group->setName($request->get('name'));

        $errors = $validator->validate($group);

        if ($errors->count() > 0) {
            foreach ($errors as $error) {
                $err[] = $error->getMessage();
            }
        }

        if (count($err) > 0) {
            return new JsonResponse(['success' => false, 'code' => -1, 'errors' => $err]);
        } else {
            $groupRepository->save($group, true);
        }

        return new JsonResponse(['success' => true, 'code' => 1]);
    }

    #[Route('/group/del', methods: ['GET', 'POST'])]
    public function apiRemoveGroup(
        Request         $request,
        GroupRepository $groupRepository,
    ): JsonResponse
    {
        if ($request->get('name') && is_string($request->get('name'))) {
            $group = $groupRepository->findOneBy([
                'name' => $request->get('name')
            ]);
            if ($group) {
                $groupRepository->remove($group, true);
                return new JsonResponse(['success' => true, 'code' => 1]);
            } else {
                return new JsonResponse(['success' => false, 'code' => -2, 'errors' => 'group not found']);
            }
        } else
            return new JsonResponse(['success' => false, 'code' => -1, 'errors' => 'name is empty or format error']);
    }

    #[Route('/group/update', methods: ['GET', 'POST'])]
    public function apiUpdateGroup(
        Request            $request,
        GroupRepository    $groupRepository,
        ValidatorInterface $validator
    ): JsonResponse
    {
        if ($request->get('name') && is_string($request->get('name'))) {
            $group = $groupRepository->findOneBy([
                'name' => $request->get('name')
            ]);
            if ($group) {
                $err = [];
                $group
                    ->setName(is_array($request->get('new')) && isset($request->get('new')['name']) && is_string($request->get('new')['name']) ? $request->get('new')['name'] : '')
                ;

                $errors = $validator->validate($group);

                if ($errors->count() > 0) {
                    foreach ($errors as $error) {
                        $err[] = $error->getMessage();
                    }
                }

                if (count($err) > 0)
                    return new JsonResponse(['success' => false, 'code' => -1, 'errors' => $err]);
                else {
                    $groupRepository->save($group, true);
                    return new JsonResponse(['success' => true, 'code' => 1]);
                }

            } else {
                return new JsonResponse(['success' => false, 'code' => -2, 'error' => 'not found']);
            }
        } else {
            return new JsonResponse(['success' => false, 'code' => -1, 'error' => 'empty or incorrect format name']);
        }
    }
}