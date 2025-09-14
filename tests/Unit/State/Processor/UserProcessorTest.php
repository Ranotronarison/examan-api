<?php

namespace App\Tests\Unit\State\Processor;

use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use App\State\Processor\UserProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProcessorTest extends TestCase
{
    private UserProcessor $userProcessor;
    /**
     * @var ProcessorInterface<User, User>&MockObject
     */
    private ProcessorInterface $persistProcessor;
    private UserPasswordHasherInterface&MockObject $passwordHasher;

    protected function setUp(): void
    {
        /** @var ProcessorInterface<User, User>&MockObject $persistProcessor */
        $persistProcessor = $this->createMock(ProcessorInterface::class);
        $this->persistProcessor = $persistProcessor;
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->userProcessor = new UserProcessor($this->persistProcessor, $this->passwordHasher);
    }

    public function testProcessUserWithPasswordSuccessfully(): void
    {
        // Arrange
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setPassword('plainPassword123');

        $hashedPassword = '$2y$13$hashedPasswordExample';

        $this->passwordHasher
            ->expects($this->once())
            ->method('hashPassword')
            ->with($user, 'plainPassword123')
            ->willReturn($hashedPassword);

        $this->persistProcessor
            ->expects($this->once())
            ->method('process')
            ->with($user)
            ->willReturn($user);

        $operation = new Post();

        // Act
        $result = $this->userProcessor->process($user, $operation);

        // Assert
        $this->assertInstanceOf(User::class, $result);
        $this->assertSame($user, $result);
        $this->assertEquals($hashedPassword, $user->getPassword());
    }
}
