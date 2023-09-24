<?php

namespace App\Twig\Components;

use App\Form\NoteType;
use App\Entity\{Note, Course};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\{DefaultActionTrait,ComponentWithFormTrait};
use Symfony\UX\LiveComponent\Attribute\{LiveAction, LiveArg, LiveProp, AsLiveComponent};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;

#[AsLiveComponent]
final class NoteTableComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public int $id;

    #[LiveProp]
    public ?Note $initialFormData = null;

    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {}

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(NoteType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function add(): void
    {
        $this->submitForm();

        /** @var Note $note */
        $note = $this->getForm()->getData();

        $this->entityManager->getRepository(Course::class)->find($this->id)->addNote($note);
        $this->entityManager->persist($note);
        $this->entityManager->flush();

        $this->resetForm();
    }

    #[LiveAction]
    public function remove(#[LiveArg] int $id): void
    {
        $note = $this->entityManager->getRepository(Note::class)->find($id);

        $this->entityManager->getRepository(Course::class)->find($this->id)->removeNote($note);
        $this->entityManager->persist($note);
        $this->entityManager->flush();
    }

    public function getNotes(): array
    {
        $course = $this->entityManager->getRepository(Course::class)->find($this->id);

        return array_reverse($course->getNotes()->getValues());
    }

    public function getAverage(): float
    {
        $course = $this->entityManager->getRepository(Course::class)->find($this->id);

        return $course->getAverage();
    }
}
