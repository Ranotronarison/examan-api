<?php

namespace App\Tests\Unit\State\Provider;

use ApiPlatform\Metadata\Get;
use App\Entity\User;
use App\State\Provider\MeProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MeProviderTest extends TestCase
{
    private MeProvider $meProvider;
    private Security&MockObject $security;

    protected function setUp(): void
    {
        $this->security = $this->createMock(Security::class);
        $this->meProvider = new MeProvider($this->security);
    }

    public function testProvideReturnsUserWhenAuthenticated(): void
    {
        // Arrange
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setFirstName('John');
        $user->setLastName('Doe');

        $this->security
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $operation = new Get();

        // Act
        $result = $this->meProvider->provide($operation);

        // Assert
        $this->assertInstanceOf(User::class, $result);
        $this->assertSame($user, $result);
    }

    public function testProvideThrowsExceptionWhenUserNotAuthenticated(): void
    {
        // Arrange
        $this->security
            ->expects($this->once())
            ->method('getUser')
            ->willReturn(null);

        $operation = new Get();

        // Assert
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('You must be authenticated to access this resource.');

        // Act
        $this->meProvider->provide($operation);
    }
}
