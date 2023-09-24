<?php

namespace App\Entity;

use DateTimeImmutable;
use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;
use App\Enumeration\CourseColor;
use App\Repository\CourseRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\{Collection, ArrayCollection};

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(min: 1, max: 32)]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 0, max: 32)]
    private ?string $professor = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255, enumType: CourseColor::class)]
    private ?CourseColor $color = null;

    #[ORM\Column]
    private ?DateTimeImmutable $created_at;

    #[ORM\OneToMany(mappedBy: 'note_id', targetEntity: Note::class, orphanRemoval: true)]
    private Collection $notes;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->created_at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getProfessor(): ?string
    {
        return $this->professor;
    }

    public function setProfessor(?string $professor): static
    {
        $this->professor = $professor;

        return $this;
    }

    public function getColor(): ?CourseColor
    {
        return $this->color;
    }

    public function getBackgroundColor(): string
    {
        $color = $this->color->value;
        return "bg-gradient-to-r from-$color-200 to-$color-50";
    }

    public function setColor(CourseColor $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setNoteId($this);
        }

        return $this;
    }

    public function removeNote(Note $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getNoteId() === $this) {
                $note->setNoteId(null);
            }
        }

        return $this;
    }

    public function getAverage(): float
    {
        $notes = $this->getNotes()->getValues();
        $sum = 0;

        if (count($this->getNotes()->getValues()) === 0) {
            return 0;
        }

        foreach ($notes as $note) {
            $sum += $note->getNote();
        }

        return round($sum / count($notes), 2);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
            'professor' => $this->professor,
            'notes' => $this->getNotes()->getValues(),
            'avg' => $this->getAverage()
        ];
    }
}
