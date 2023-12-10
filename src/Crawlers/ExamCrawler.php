<?php

namespace Petcha\ExamEngine\Crawlers;

use Exception;
use Petcha\ExamEngine\Common\Enums\Difficulties;
use Petcha\ExamEngine\Common\Enums\Subjects;
use Petcha\ExamEngine\Common\Interfaces\CrawlerStrategy;

class ExamCrawler implements CrawlerStrategy
{
    /**
     * @var CrawlerStrategy|null
     */
    private ?CrawlerStrategy $examCrawler = null;

    /**
     * Sets the strategy for the crawler.
     *
     * @param CrawlerStrategy $examCrawler The crawler strategy to use.
     */
    public function setCrawler(CrawlerStrategy $examCrawler): void
    {
        $this->examCrawler = $examCrawler;
    }

    /**
     * Fetches questions based on the specified criteria.
     *
     * @throws Exception If the crawler strategy is not set.
     */
    public function fetchQuestions(Subjects $subject, Difficulties $difficulty, int $amount): array
    {
        if (is_null($this->examCrawler)) {
            throw new Exception('Crawler strategy was not set');
        }

        return $this->examCrawler->fetchQuestions($subject, $difficulty, $amount);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function examApplicationAvailable(): array
    {
        return $this->examCrawler->examApplicationAvailable();
    }
}
