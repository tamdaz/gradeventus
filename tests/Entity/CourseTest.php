<?php

namespace App\Tests\Entity;

use App\Entity\Course;
use App\Enumeration\CourseColor;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CourseTest extends KernelTestCase
{
    private function createCourse(): Course
    {
        return new Course();
    }

    private function assertHasErrors(Course $course, int $number): void
    {
        self::bootKernel();
        $errors = static::getContainer()->get('validator')->validate($course);
        $messages = [];

        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testCreateCourseWithNoErrors(): void
    {
        $course = $this->createCourse()
            ->setName("Français")
            ->setProfessor("M. Machin")
            ->setRoom("205")
            ->setColor(CourseColor::BLUE);

        $this->assertHasErrors($course, 0);
    }

    public function testCreateCourseWithNoProfessor(): void
    {
        $course = $this->createCourse()
            ->setName("Français")
            ->setProfessor("")
            ->setRoom("205")
            ->setColor(CourseColor::BLUE);

        $this->assertHasErrors($course, 0);
    }

    public function testCreateCourseWithNoRoom(): void
    {
        $course = $this->createCourse()
            ->setName("Français")
            ->setProfessor("")
            ->setColor(CourseColor::BLUE);

        $this->assertHasErrors($course, 0);
    }

    public function testDetectErrorOnEmptyCourseName(): void
    {
        $course = $this->createCourse()
            ->setName("")
            ->setProfessor("M. Machin")
            ->setRoom("205")
            ->setColor(CourseColor::BLUE);

        $this->assertHasErrors($course, 1);
    }

    public function testDetectErrorOnEmptyCourseColor(): void
    {
        $course = $this->createCourse()
            ->setName("Français")
            ->setRoom("205")
            ->setProfessor("M. Machin");

        $this->assertHasErrors($course, 1);
    }

    public function testDetectErrorOnExcessiveCharacterOnProfessor(): void
    {
        $course = $this->createCourse()
            ->setName("Français")
            ->setProfessor("M. abcdefghijklmnopqrstuvwxyz0123456789")
            ->setRoom("205")
            ->setColor(CourseColor::BLUE);

        $this->assertHasErrors($course, 1);
    }

    public function testCheckCreatedAtKeyIsSet(): void
    {
        $course = $this->createCourse()
            ->setName("Français")
            ->setProfessor("M. Machin")
            ->setRoom("205")
            ->setColor(CourseColor::BLUE);

        self::assertNotEmpty($course->getCreatedAt());
    }

    public function testGetCourseData(): void
    {
        $course = $this->createCourse()
            ->setName("Français")
            ->setProfessor("M. Machin")
            ->setColor(CourseColor::BLUE);

        $json = $course->jsonSerialize();

        self::assertArrayHasKey("id", $json);
        self::assertArrayHasKey("avg", $json);
        self::assertArrayHasKey("name", $json);
        self::assertArrayHasKey("room", $json);
        self::assertArrayHasKey("color", $json);
        self::assertArrayHasKey("notes", $json);
        self::assertArrayHasKey("professor", $json);
    }
}
