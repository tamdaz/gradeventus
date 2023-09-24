<?php

namespace App\Tests\Form;

use App\Entity\Course;
use App\Form\CourseType;
use App\Enumeration\CourseColor;
use Symfony\Component\Form\Test\TypeTestCase;

class CourseTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'Nom du cours',
            'professor' => 'Nom du professeur',
            'color' => CourseColor::GRAY->value
        ];

        $form = $this->factory->create(CourseType::class);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        $data = $form->getData();

        $this->assertInstanceOf(Course::class, $data);
        $this->assertSame('Nom du cours', $data->getName());
        $this->assertSame('Nom du professeur', $data->getProfessor());
        $this->assertSame('gray', $data->getColor()->value);

        $view = $form->createView();
        $children = $view->children;

        $this->assertArrayHasKey('name', $children);
        $this->assertArrayHasKey('professor', $children);
        $this->assertArrayHasKey('color', $children);
        $this->assertArrayHasKey('submit', $children);
    }
}
