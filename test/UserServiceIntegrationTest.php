<?php
namespace David\Test\PhpTest;

use PDOException;
use PHPUnit\Framework\TestCase;
use David\PhpTest\common\MemoryDb;
use David\PhpTest\users\User;
use David\PhpTest\users\UserMemoryRepository;
use David\PhpTest\users\UserService;

class UserServiceIntegrationTest extends TestCase
{
    public function testCreateNewUser()
    {
        // Create an in-memory database and repositories
        $memoryDb = new MemoryDb();
        $userRepository = new UserMemoryRepository($memoryDb);

        // Create a UserService instance
        $userService = new UserService($userRepository);

        // Create a new user
        $newUser = new User(null, 'john_doe', 'John Doe', 'john@example.com', 'password123');

        // Call the createUser method
        $userCreated = $userService->createUser($newUser);

        // Check if the user is created successfully
        $this->assertInstanceOf(User::class, $userCreated);
        $this->assertEquals(1, $userCreated->id);
    }

    public function testCreateTwoUsersWithTheSameEmail()
    {
        // We expect an exception because two users can't have the same email
        $this->expectException(PDOException::class);
        $this->expectExceptionMessage('SQLSTATE[23000]: Integrity constraint violation: 19 UNIQUE constraint failed: users.email');

        // Create an in-memory database and repositories
        $memoryDb = new MemoryDb();
        $userRepository = new UserMemoryRepository($memoryDb);

        // Create a UserService instance
        $userService = new UserService($userRepository);

        // Create the first user with a unique email
        $newUser = new User(null, 'john_doe', 'John Doe', 'john@example.com', 'password123');
        $userService->createUser($newUser);

        // Attempt to create a second user with the same email, expecting an exception
        $newUser2 = new User(null, 'john_doe', 'John Doe', 'john@example.com', 'password123');
        $userService->createUser($newUser2);
    }

    public function testUpdateUser()
    {
        // Create an in-memory database and repositories
        $memoryDb = new MemoryDb();
        $userRepository = new UserMemoryRepository($memoryDb);

        // Create a UserService instance
        $userService = new UserService($userRepository);

        // Create a new user
        $newUser = new User(null, 'john_doe', 'John Doe', 'john@example.com', 'password123');
        $userCreated = $userService->createUser($newUser);

        // Modify the user's name
        $nameToTest = "David Batista";
        $userCreated->name = $nameToTest;

        // Call the updateUser method
        $userupdated = $userService->updateUser($userCreated);

        // Check if the user is updated successfully
        $this->assertInstanceOf(User::class, $userCreated);
        $this->assertEquals($nameToTest, $userupdated->name);
    }

    public function testUpdateUserWithAnExistingEmail()
    {
        // We expect an exception because two users can't have the same email, including when updating an existing user
        $this->expectException(PDOException::class);
        $this->expectExceptionMessage('SQLSTATE[23000]: Integrity constraint violation: 19 UNIQUE constraint failed: users.email');

        // Create an in-memory database and repositories
        $memoryDb = new MemoryDb();
        $userRepository = new UserMemoryRepository($memoryDb);

        // Create a UserService instance
        $userService = new UserService($userRepository);

        // Create the first user with a unique email
        $newUser = new User(null, 'john_doe', 'John Doe', 'john@example.com', 'password123');
        $userService->createUser($newUser);

        // Create a second user with a different email
        $newUser2 = new User(null, 'juan_perez', 'Juanperez', 'juanperez@example.com', 'password123');
        $userCreated2 = $userService->createUser($newUser2);

        // Update the email of the second user to be the same as the first user, expecting an exception
        $userCreated2->email = 'john@example.com';
        $userService->updateUser($userCreated2);
    }

    public function testDeleteUser()
    {
        // Create an in-memory database and repositories
        $memoryDb = new MemoryDb();
        $userRepository = new UserMemoryRepository($memoryDb);

        // Create a UserService instance
        $userService = new UserService($userRepository);

        // Create a new user
        $newUser = new User(null, 'john_doe', 'John Doe', 'john@example.com', 'password123');
        $userCreated = $userService->createUser($newUser);

        // Call the deleteUser method
        $userService->deleteUser($userCreated->id);

        // Check if the user is deleted successfully
        $existingUser = $userService->getUserById($userCreated->id);
        $this->assertEquals(null, $existingUser);
    }

    public function testGetUserById()
    {
        // Create an in-memory database and repositories
        $memoryDb = new MemoryDb();
        $userRepository = new UserMemoryRepository($memoryDb);

        // Create a UserService instance
        $userService = new UserService($userRepository);

        // Create a new user
        $newUser = new User(null, 'john_doe', 'John Doe', 'john@example.com', 'password123');
        $userCreated = $userService->createUser($newUser);

        // Retrieve the user by ID using the getUserById method
        $existingUser = $userService->getUserById($userCreated->id);

        // Check if the user is retrieved successfully
        $this->assertNotNull($existingUser); // Ensure the user is not null
        $this->assertInstanceOf(User::class, $existingUser); // Ensure the retrieved object is an instance of User
        $this->assertEquals($userCreated->id, $existingUser->id); // Check if the IDs match
        $this->assertEquals($userCreated->username, $existingUser->username); // Check if the usernames match
    }

    public function testGetUserByIdWhenAnUserNotExist()
    {
        // Create an in-memory database and repositories
        $memoryDb = new MemoryDb();
        $userRepository = new UserMemoryRepository($memoryDb);

        // Create a UserService instance
        $userService = new UserService($userRepository);

        // Create a new user
        $newUser = new User(null, 'john_doe', 'John Doe', 'john@example.com', 'password123');
        $userCreated = $userService->createUser($newUser);

        // Attempt to retrieve a user by an ID that doesn't exist (e.g., ID 2)
        $nonExistentUser = $userService->getUserById(2);

        // Check if the result is null, indicating that the user with ID 2 does not exist
        $this->assertNull($nonExistentUser);
    }

    public function testGetAllUsers()
    {
        // Create an in-memory database and repositories
        $memoryDb = new MemoryDb();
        $userRepository = new UserMemoryRepository($memoryDb);

        // Create a UserService instance
        $userService = new UserService($userRepository);

        // Create two new users
        $newUser = new User(null, 'john_doe', 'John Doe', 'john@example.com', 'password123');
        $newUser2 = new User(null, 'juan_perez', 'Juan Perez', 'juanperez@example.com', 'password123');

        // Add the users to the repository using the createUser method
        $userService->createUser($newUser);
        $userService->createUser($newUser2);

        // Call the getAllUsers method to retrieve the list of users
        $users = $userService->getAllUsers();

        // Perform assertions to ensure the expected behavior
        $this->assertNotNull($users); // Ensure the returned array of users is not null
        $this->assertCount(2, $users); // Ensure that the returned array has the expected count of users
        $this->assertInstanceOf(User::class, $users[0]); // Ensure that the first element is an instance of User
        $this->assertInstanceOf(User::class, $users[1]); // Ensure that the second element is an instance of User
    }
}