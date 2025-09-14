<?php

namespace App\DataFixtures;

use App\Entity\Exam;
use App\Entity\User;
use App\Enum\ExamStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Create the first user
        $user = new User();
        $user->setEmail('admin@examan.com');
        $user->setFirstName('Admin');
        $user->setLastName('User');
        $user->setRoles(['ROLE_ADMIN']);

        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
        $user->setPassword($hashedPassword);

        $manager->persist($user);

        // Create 10 exams with different statuses and dates
        $studentNames = [
            'John Doe',
            'Jane Smith',
            'Michael Johnson',
            'Emily Davis',
            'David Wilson',
            'Sarah Brown',
            'Robert Taylor',
            'Lisa Anderson',
            'James Martinez',
            'Jennifer Garcia',
        ];

        $locations = [
            'Main Campus - Room 101',
            'Building A - Room 205',
            'Library - Study Room 3',
            'Science Building - Lab 1',
            'Main Campus - Room 302',
            'Building B - Conference Room',
            'Online - Virtual Room',
            'Main Campus - Auditorium',
            'Building A - Room 110',
            'Training Center - Room 5',
        ];

        $statuses = [
            ExamStatus::CONFIRMED,
            ExamStatus::TO_ORGANIZE,
            ExamStatus::SEARCHING_PLACE,
            ExamStatus::CANCELLED,
        ];

        for ($i = 0; $i < 10; ++$i) {
            $exam = new Exam();
            $exam->setStudentName($studentNames[$i]);
            $exam->setLocation($locations[$i]);

            // Create dates between today and 30 days in the future
            $baseDate = new \DateTimeImmutable();
            $examDate = $baseDate->modify('+'.rand(1, 30).' days');
            $exam->setDate($examDate);

            // Create times between 9:00 and 17:00
            $hour = rand(9, 17);
            $minute = rand(0, 1) * 30; // Either 00 or 30 minutes
            $examTime = new \DateTimeImmutable(sprintf('%02d:%02d', $hour, $minute));
            $exam->setTime($examTime);

            // Assign random status
            $status = $statuses[array_rand($statuses)];
            $exam->setStatus($status);

            $manager->persist($exam);
        }

        $manager->flush();
    }
}
