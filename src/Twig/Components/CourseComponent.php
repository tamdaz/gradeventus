<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class CourseComponent
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string|null
     */
    public ?string $room;

    /**
     * @var string
     */
    public string $color;

    /**
     * @var float|null
     */
    public ?float $average;

    /**
     * @var string|null
     */
    public ?string $professor;

    /**
     * @var bool
     */
    public bool $is_card = true;
}
