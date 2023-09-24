<?php

namespace App\Tests\Entity;

use App\Entity\{Course, Note};
use App\Enumeration\CourseColor;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NoteTest extends KernelTestCase
{
    private function createNote(): Note
    {
        return new Note();
    }

    private function createCourse(): Course
    {
        return (new Course())
            ->setName("Français")
            ->setProfessor("M. Machin")
            ->setColor(CourseColor::BLUE);
    }

    private function assertHasErrors(Course|Note $entity, int $number): void
    {
        self::bootKernel();
        $errors = static::getContainer()->get('validator')->validate($entity);
        $messages = [];

        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testCreateNoteWithNoErrors(): void
    {
        $note = $this->createNote()
            ->setNote(17);

        $this->assertHasErrors($note, 0);
    }

    public function testCreateNoteWithFloat(): void
    {
        $note = $this->createNote()
            ->setNote(14.75);

        $this->assertHasErrors($note, 0);
    }

    public function testCheckCreatedAtKeyIsSet(): void
    {
        $note = $this->createNote()
            ->setNote(14.75);

        $this->assertNotEmpty($note->getCreatedAt());
    }

    public function testDetectErrorOnEmptyNote(): void
    {
        $note = $this->createNote();

        $this->assertHasErrors($note, 1);
    }

    public function testAssignOneNoteToCourse(): void
    {
        $note = $this->createNote()
            ->setNote(14.75);

        $course = $this->createCourse()->addNote($note);
        $findNote = $course->getNotes()->getValues();

        $this->assertHasErrors($course, 0);
        $this->assertEquals(14.75, $findNote[0]->getNote());
    }

    public function testAssignMoreNotesToCourse(): void
    {
        $note1 = $this->createNote()->setNote(14.75);
        $note2 = $this->createNote()->setNote(12.50);
        $note3 = $this->createNote()->setNote(10.25);

        $course = $this->createCourse()
            ->addNote($note1)
            ->addNote($note2)
            ->addNote($note3);

        $notes = $course->getNotes()->getValues();

        $this->assertHasErrors($course, 0);

        $this->assertEquals(14.75, $notes[0]->getNote());
        $this->assertEquals(12.50, $notes[1]->getNote());
        $this->assertEquals(10.25, $notes[2]->getNote());
    }

    public function testAssertAverageIsValid(): void
    {
        $note1 = $this->createNote()->setNote(14.75);
        $note2 = $this->createNote()->setNote(12.50);
        $note3 = $this->createNote()->setNote(10.25);

        $course = $this->createCourse()
            ->addNote($note1)
            ->addNote($note2)
            ->addNote($note3);

        $this->assertEquals(12.50, $course->getAverage());

        $this->assertHasErrors($course, 0);

    }

    public function testGetNoteData(): void
    {
        $note = $this->createNote()
            ->setNote(14.75);

        $this->assertHasErrors($note, 0);
        $this->assertEquals(14.75, $note->jsonSerialize());
    }
}
