<?php

namespace App\Command;

use App\Entity\Course;
use App\Enumeration\CourseColor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\{InputArgument, InputInterface};

#[AsCommand(
    name: 'app:create-course',
    description: 'Allows to create a course',
)]
class CreateCourseCommand extends Command
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the course')
            ->addArgument('professor', InputArgument::OPTIONAL, 'Name of the professor (optional)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $course = (new Course())
            ->setName($input->getArgument('name'))
            ->setProfessor($input->getArgument('professor'))
            ->setColor(CourseColor::BLUE);

        $this->entityManager->persist($course);
        $this->entityManager->flush();

        $io->success("Course has been successfully created (id: " . $course->getId() . ")");

        return Command::SUCCESS;
    }
}
