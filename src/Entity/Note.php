<?php

namespace App\Entity;

use DateTimeImmutable;
use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\NoteRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(cascade: ["persist"], inversedBy: 'notes')]
    private ?Course $note_id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Range(min: 0, max: 20)]
    private ?float $note = null;

    #[ORM\Column]
    private ?DateTimeImmutable $created_at;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoteId(): ?Course
    {
        return $this->note_id;
    }

    public function setNoteId(?Course $note_id): static
    {
        $this->note_id = $note_id;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): static
    {
        $this->note = round($note, 2);

        return $this;
    }

    public function jsonSerialize(): float
    {
        return $this->note;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }
}
