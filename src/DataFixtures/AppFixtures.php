<?php

namespace App\DataFixtures;

use App\Entity\{Course, Note};
use App\Enumeration\CourseColor;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (CourseColor::cases() as $color) {
            $course = new Course();
            $course
                ->setName(ucfirst(strtolower($color->value)))
                ->setColor($color)
                ->setProfessor("M. Durand");

            for ($i = 0; $i <= 5; $i++) {
                $note = new Note();
                $note->setNote(rand(0, 20));

                $manager->persist($note);
                $course->addNote($note);
            }

            $manager->persist($course);
        }

        $manager->flush();
    }
}
