<?php

namespace App\Enums;

enum ExamStatus: string
{
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case PASSED = 'passed';
}
