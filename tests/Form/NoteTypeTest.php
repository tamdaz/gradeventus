<?php

namespace App\Tests\Form;

use App\Entity\Note;
use App\Form\NoteType;
use Symfony\Component\Form\Test\TypeTestCase;

class NoteTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'note' => 17
        ];

        $form = $this->factory->create(NoteType::class);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        $data = $form->getData();

        $this->assertInstanceOf(Note::class, $data);
        $this->assertSame(17.0, $data->getNote());

        $view = $form->createView();
        $children = $view->children;

        $this->assertArrayHasKey('note', $children);
    }
}
