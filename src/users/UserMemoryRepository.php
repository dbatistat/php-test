<?php
namespace David\PhpTest\users;

use David\PhpTest\common\MemoryDb;
use Exception;
use PDO;
use PDOException;

class UserMemoryRepository implements UserRepositoryInterface
{
    private $memoryDb;

    public function __construct(MemoryDb $memoryDb)
    {
        $this->memoryDb = $memoryDb;
    }

    public function getUserById($userId): ?User
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->memoryDb->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User($row['id'], $row['username'], $row['name'], $row['email'], $row['password']);
        } else {
            return null;
        }
    }

    public function getUserByIdOrFail($userId): User
    {
        $user = $this->getUserById($userId);

        if ($user != null) {
            return $user;
        } else {
            throw new Exception('User with the ID ' . $userId . ' does not exist');
        }
    }

    public function getAllUsers(): array
    {
        $query = "SELECT * FROM users";
        $stmt = $this->memoryDb->query($query);

        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user = new User($row['id'], $row['username'], $row['name'], $row['email'], $row['password']);
            $users[] = $user;
        }

        return $users;
    }

    public function createUser(User $user): User
    {
        $query = "INSERT INTO users (username, name, email, password) 
                  VALUES (:username, :name, :email, :password)";
        $stmt = $this->memoryDb->prepare($query);

        $stmt->bindParam(':username', $user->username, PDO::PARAM_STR);
        $stmt->bindParam(':name', $user->name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $user->email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $user->password, PDO::PARAM_STR);

        try {
            $this->memoryDb->beginTransaction();

            $stmt->execute();

            $this->memoryDb->commit();

            $lastInsertedId = $this->memoryDb->lastInsertId();
            $userCreated = $this->getUserByIdOrFail($lastInsertedId);

            return $userCreated;
        } catch (Exception $e) {
            $this->memoryDb->rollback();

            throw $e;
        }
    }

    public function updateUser(User $user): User
    {
        $query = "UPDATE users 
                  SET username = :username, name = :name, email = :email, password = :password
                  WHERE id = :id";
        $stmt = $this->memoryDb->prepare($query);

        $stmt->bindParam(':id', $user->id, PDO::PARAM_INT);
        $stmt->bindParam(':username', $user->username, PDO::PARAM_STR);
        $stmt->bindParam(':name', $user->name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $user->email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $user->password, PDO::PARAM_STR);

        try {
            $this->memoryDb->beginTransaction();

            $stmt->execute();

            $this->memoryDb->commit();

            $userUpdated = $this->getUserByIdOrFail($user->id);

            return $userUpdated;
        } catch (Exception $e) {

            $this->memoryDb->rollback();

            throw $e;
        }
    }

    public function deleteUser($userId): void
    {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->memoryDb->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }
}