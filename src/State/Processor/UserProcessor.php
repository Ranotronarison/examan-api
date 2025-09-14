<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @implements ProcessorInterface<User, User>
 */
class UserProcessor implements ProcessorInterface
{
    /**
     * @var ProcessorInterface<User, User>
     */
    private ProcessorInterface $persistProcessor;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        ProcessorInterface $persistProcessor,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->persistProcessor = $persistProcessor;
        $this->passwordHasher = $passwordHasher;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ?User
    {
        // Hash the password if it's a new user or password has been changed
        if ($data->getPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword($data, $data->getPassword());
            $data->setPassword($hashedPassword);
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
