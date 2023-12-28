<?php
namespace David\PhpTest\users;

interface UserRepositoryInterface
{
    public function getUserById($userId): ?User;
    public function getUserByIdOrFail($userId): User;
    public function getAllUsers(): array;
    public function createUser(User $user): User;
    public function updateUser(User $user): User;
    public function deleteUser($userId): void;
}
