<?php

namespace App\Tests\Unit\State\Processor;

use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Exam;
use App\Enum\ExamStatus;
use App\State\Processor\ExamProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ExamProcessorTest extends TestCase
{
    private ExamProcessor $examProcessor;
    /**
     * @var ProcessorInterface<Exam, Exam>&MockObject
     */
    private ProcessorInterface $persistProcessor;

    protected function setUp(): void
    {
        /** @var ProcessorInterface<Exam, Exam>&MockObject $persistProcessor */
        $persistProcessor = $this->createMock(ProcessorInterface::class);
        $this->persistProcessor = $persistProcessor;
        $this->examProcessor = new ExamProcessor($this->persistProcessor);
    }

    public function testProcessValidExamSuccessfully(): void
    {
        // Arrange
        $futureDate = new \DateTimeImmutable('+1 day');
        $futureTime = new \DateTimeImmutable('14:00:00');

        $exam = new Exam();
        $exam->setStudentName('John Doe');
        $exam->setDate($futureDate);
        $exam->setTime($futureTime);
        $exam->setStatus(ExamStatus::TO_ORGANIZE);
        $exam->setLocation('Room 101');

        $this->persistProcessor
            ->expects($this->once())
            ->method('process')
            ->with($exam)
            ->willReturn($exam);

        $operation = new Post();

        // Act
        $result = $this->examProcessor->process($exam, $operation);

        // Assert
        $this->assertInstanceOf(Exam::class, $result);
        $this->assertSame($exam, $result);
    }

    public function testProcessThrowsExceptionWhenExamDateIsInPast(): void
    {
        // Arrange
        $pastDate = new \DateTimeImmutable('-1 day');
        $pastTime = new \DateTimeImmutable('14:00:00');

        $exam = new Exam();
        $exam->setStudentName('John Doe');
        $exam->setDate($pastDate);
        $exam->setTime($pastTime);
        $exam->setStatus(ExamStatus::TO_ORGANIZE);

        $this->persistProcessor
            ->expects($this->never())
            ->method('process');

        $operation = new Post();

        // Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Exam date and time cannot be in the past');

        // Act
        $this->examProcessor->process($exam, $operation);
    }
}
