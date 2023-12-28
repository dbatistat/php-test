<?php
namespace David\PhpTest\users;

class UserService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserById($userId): ?User
    {
        return $this->userRepository->getUserById($userId);
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->getAllUsers();
    }

    public function createUser(User $user): User
    {
        return $this->userRepository->createUser($user);
    }

    public function updateUser(User $user): User
    {
        return $this->userRepository->updateUser($user);
    }

    public function deleteUser($userId): void
    {
        $this->userRepository->deleteUser($userId);
    }
}