<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiUserController extends AbstractController
{
    #[Route('/user/get', methods: ['GET', 'POST'])]
    public function apiGetUser(
        Request        $request,
        UserRepository $userRepository
    ): JsonResponse
    {
        if ($request->get('email') && is_string($request->get('email'))) {
            $user = $userRepository->findOneBy([
                'email' => $request->get('email')
            ]);
            if ($user) {
                return new JsonResponse(['success' => true, 'code' => 1, 'user' => $user]);
            } else {
                return new JsonResponse(['success' => false, 'code' => -2, 'error' => 'not found']);
            }
        } else
            return new JsonResponse(['success' => false, 'code' => -1, 'error' => 'empty or incorrect format email']);
    }

    #[Route('/user/add', methods: ['GET', 'POST'])]
    public function apiAddUser(
        Request            $request,
        UserRepository     $userRepository,
        GroupRepository    $groupRepository,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $err = [];

        $user = new User();
        $user
            ->setName($request->get('name'))
            ->setEmail($request->get('email'));

        if ($request->get('groups') != '' && !is_array($request->get('groups'))) {
            $err[] = '`groups` must be array';
        } elseif (is_array($request->get('groups'))) {
            foreach ($request->get('groups') as $group) {
                if (is_string($group)) {
                    $groupAdd = $groupRepository->findOneBy(['name' => $group]);
                    if ($groupAdd) {
                        $user->addMyGroup($groupAdd);
                    } else {
                        $err[] = 'group ' . $group . ' not found';
                    }
                } else {
                    $err[] = 'incorrect format of groups';
                }
            }
        }

        $errors = $validator->validate($user);

        if ($errors->count() > 0) {
            foreach ($errors as $error) {
                $err[] = $error->getMessage();
            }
        }

        if (count($err) > 0) {
            return new JsonResponse(['success' => false, 'code' => -1, 'errors' => $err]);
        } else {
            $userRepository->save($user, true);
        }

        return new JsonResponse(['success' => true, 'code' => 1]);
    }

    #[Route('/user/del', methods: ['GET', 'POST'])]
    public function apiRemoveUser(
        Request        $request,
        UserRepository $userRepository
    ): JsonResponse
    {
        if ($request->get('email') && is_string($request->get('email'))) {
            $user = $userRepository->findOneBy([
                'email' => $request->get('email')
            ]);
            if ($user) {
                $userRepository->remove($user, true);
                return new JsonResponse(['success' => true, 'code' => 1]);
            } else {
                return new JsonResponse(['success' => false, 'code' => -2, 'errors' => 'user not found']);
            }
        } else
            return new JsonResponse(['success' => false, 'code' => -1, 'errors' => 'email is empty or format error']);
    }

    #[Route('/user/update', methods: ['GET', 'POST'])]
    public function apiUpdateUser(
        Request            $request,
        UserRepository     $userRepository,
        ValidatorInterface $validator
    ): JsonResponse
    {
        if ($request->get('email') && is_string($request->get('email'))) {
            $user = $userRepository->findOneBy([
                'email' => $request->get('email')
            ]);
            if ($user) {
                $err = [];
                $user
                    ->setName(is_array($request->get('new')) && isset($request->get('new')['name']) && is_string($request->get('new')['name']) ? $request->get('new')['name'] : '')
                    ->setEmail(is_array($request->get('new')) && isset($request->get('new')['email']) && is_string($request->get('new')['email']) ? $request->get('new')['email'] : '');

                $errors = $validator->validate($user);

                if ($errors->count() > 0) {
                    foreach ($errors as $error) {
                        $err[] = $error->getMessage();
                    }
                }

                if (count($err) > 0)
                    return new JsonResponse(['success' => false, 'code' => -1, 'errors' => $err]);
                else {
                    $userRepository->save($user, true);
                    return new JsonResponse(['success' => true, 'code' => 1]);
                }

            } else {
                return new JsonResponse(['success' => false, 'code' => -2, 'error' => 'not found']);
            }
        } else {
            return new JsonResponse(['success' => false, 'code' => -1, 'error' => 'empty or incorrect format email']);
        }
    }

    #[Route('/user/group/add', methods: ['GET', 'POST'])]
    public function apiUserGroupAdd(
        Request            $request,
        UserRepository     $userRepository,
        GroupRepository    $groupRepository
    ): JsonResponse
    {
        if ($request->get('email') && is_string($request->get('email'))) {
            $user = $userRepository->findOneBy([
                'email' => $request->get('email')
            ]);
            if ($user) {
                if ($request->get('group') && is_string($request->get('group'))) {
                    $group = $groupRepository->findOneBy([
                        'name' => $request->get('group')
                    ]);

                    if ($group) {
                        $user->addMyGroup($group);
                        $userRepository->save($user, true);
                        return new JsonResponse(['success' => true, 'code' => 1]);
                    } else {
                        return new JsonResponse(['success' => false, 'code' => -4, 'error' => 'group not found']);
                    }
                } else {
                    return new JsonResponse(['success' => false, 'code' => -3, 'error' => 'group is empty']);
                }


            } else {
                return new JsonResponse(['success' => false, 'code' => -2, 'error' => 'user not found']);
            }
        } else {
            return new JsonResponse(['success' => false, 'code' => -1, 'error' => 'empty or incorrect format email']);
        }
    }

    #[Route('/user/group/del', methods: ['GET', 'POST'])]
    public function apiUserGroupDel(
        Request            $request,
        UserRepository     $userRepository,
        GroupRepository    $groupRepository
    ): JsonResponse
    {
        if ($request->get('email') && is_string($request->get('email'))) {
            $user = $userRepository->findOneBy([
                'email' => $request->get('email')
            ]);
            if ($user) {
                if ($request->get('group') && is_string($request->get('group'))) {
                    $group = $groupRepository->findOneBy([
                        'name' => $request->get('group')
                    ]);

                    if ($group) {
                        $user->removeMyGroup($group);
                        $userRepository->save($user, true);
                        return new JsonResponse(['success' => true, 'code' => 1]);
                    } else {
                        return new JsonResponse(['success' => false, 'code' => -4, 'error' => 'group not found']);
                    }
                } else {
                    return new JsonResponse(['success' => false, 'code' => -3, 'error' => 'group is empty']);
                }
            } else {
                return new JsonResponse(['success' => false, 'code' => -2, 'error' => 'user not found']);
            }
        } else {
            return new JsonResponse(['success' => false, 'code' => -1, 'error' => 'empty or incorrect format email']);
        }
    }
}