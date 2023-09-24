<?php

namespace App\Enumeration;

enum CourseColor: string
{
    const __default = self::GRAY;

    case GRAY = "gray";
    case ROSE = "rose";
    case RED = "red";
    case ORANGE = "orange";
    case YELLOW = "yellow";
    case LIME = "lime";
    case GREEN = "green";
    case TEAL = "teal";
    case CYAN = "cyan";
    case BLUE = "blue";
    case PURPLE = "purple";
    case FUCHSIA = "fuchsia";
}
