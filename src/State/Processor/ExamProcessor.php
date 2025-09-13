<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Exam;

/**
 * @implements ProcessorInterface<Exam, Exam>
 */
class ExamProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ?Exam
    {
        if (! $data instanceof Exam) {
            throw new \InvalidArgumentException('Expected Exam entity');
        }

        $examDateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data->getDate()->format('Y-m-d').' '.$data->getTime()->format('H:i:s'));
        if ($examDateTime < new \DateTimeImmutable()) {
            throw new \InvalidArgumentException('Exam date and time cannot be in the past');
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
