<?php

namespace David\Test\PhpTest;

use PHPUnit\Framework\TestCase;
use David\PhpTest\common\MemoryDb;
use David\PhpTest\users\User;
use David\PhpTest\users\UserMemoryRepository;
use David\PhpTest\users\UserService;

class UserServiceTest extends TestCase
{
    public function testGetAllUsers()
    {
        // Create a mock for the in-memory database connection
        $connectionMock = $this->getMockBuilder(MemoryDb::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Create a mock for the UserMemoryRepository, passing the mock connection
        $userRepositoryMock = $this->getMockBuilder(UserMemoryRepository::class)
            ->setConstructorArgs([$connectionMock])
            ->getMock();

        // Configure the mock to return test data when the getAllUsers method is called
        $userRepositoryMock->expects($this->once())
            ->method('getAllUsers')
            ->willReturn([
                new User(1, 'john_doe', 'John Doe', 'john@example.com', 'password123'),
                new User(2, 'jane_smith', 'Jane Smith', 'jane@example.com', 'pass456')
            ]);

        // Create a UserService instance, injecting the mock repository
        $userService = new UserService($userRepositoryMock);

        // Call the getAllUsers method to retrieve the list of users
        $users = $userService->getAllUsers();

        // Perform assertions to ensure the expected behavior
        $this->assertCount(2, $users); // Ensure that the returned array has the expected count of users
        $this->assertInstanceOf(User::class, $users[0]); // Ensure that the first element is an instance of User
        $this->assertInstanceOf(User::class, $users[1]); // Ensure that the second element is an instance of User
    }

    public function testCreateUser()
    {
        // Create a mock for the in-memory database connection
        $connectionMock = $this->getMockBuilder(MemoryDb::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Create a mock for the UserMemoryRepository, passing the mock connection
        $userRepositoryMock = $this->getMockBuilder(UserMemoryRepository::class)
            ->setConstructorArgs([$connectionMock])
            ->getMock();

        // Configure the mock to return test data when the createUser method is called
        $userRepositoryMock->expects($this->once())
            ->method('createUser')
            ->willReturn(new User(1, 'john_doe', 'John Doe', 'john@example.com', 'password123'));

        // Create a UserService instance, injecting the mock repository
        $userService = new UserService($userRepositoryMock);

        // Call the createUser method to create a user
        $user = $userService->createUser(new User(null, 'john_doe', 'John Doe', 'john@example.com', 'password123'));

        // Perform assertions to ensure the expected behavior
        $this->assertNotNull($user); // Ensure that the returned user is not null
    }

    public function testGetUserById()
    {
        // Create a mock for the in-memory database connection
        $connectionMock = $this->getMockBuilder(MemoryDb::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Create a mock for the UserMemoryRepository, passing the mock connection
        $userRepositoryMock = $this->getMockBuilder(UserMemoryRepository::class)
            ->setConstructorArgs([$connectionMock])
            ->getMock();

        // Configure the mock to return test data when the getUserById method is called
        $userRepositoryMock->expects($this->once())
            ->method('getUserById')
            ->willReturn(new User(1, 'john_doe', 'John Doe', 'john@example.com', 'password123'));

        // Create a UserService instance, injecting the mock repository
        $userService = new UserService($userRepositoryMock);

        // Call the getUserById method to retrieve a user by ID
        $user = $userService->getUserById(1);

        // Perform assertions to ensure the expected behavior
        $this->assertNotNull($user); // Ensure that the returned user is not null
    }
}