<?php

namespace App\Enums;

class QuestionType
{

    const SINGLE_SELECT = 'single-select';
    const MULTIPLE_SELECT = 'multiple-select';
    const IMAGE_INPUT = 'image-input';
    const NUMBER_INPUT = 'number-input';
    const NUMBER_SLIDER = 'number-slider';
    const TEXT_INPUT = 'text-input';


    const ALL = [self::SINGLE_SELECT, self::MULTIPLE_SELECT, self::IMAGE_INPUT, self::NUMBER_INPUT, self::NUMBER_SLIDER, self::TEXT_INPUT];
}
