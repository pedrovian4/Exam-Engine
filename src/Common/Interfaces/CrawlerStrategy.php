<?php

namespace Petcha\ExamEngine\Common\Interfaces;

use Petcha\ExamEngine\Common\Enums\Difficulties;
use Petcha\ExamEngine\Common\Enums\Subjects;

interface  CrawlerStrategy
{
    public  function  fetchQuestions(Subjects $subject, Difficulties $difficulty, int $amount): array;
    public function examApplicationAvailable(): array;
}