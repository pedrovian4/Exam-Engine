<?php

namespace unit\Crawlers;

use GuzzleHttp\Exception\GuzzleException;
use Petcha\ExamEngine\Common\Enums\Brazil\EnemCaderno;
use Petcha\ExamEngine\Crawlers\EnemCrawler;
use PHPUnit\Framework\TestCase;

class EnemCrawlerTest extends TestCase
{
    private readonly EnemCrawler $enemCrawler;
    private readonly  EnemCaderno $caderno;
    public  function  setUp(): void
    {
        parent::setUp();
        $this->caderno = EnemCaderno::CADERNO_AMARELO;
        $this->enemCrawler = new EnemCrawler(2020, $this->caderno );
    }
    /**
     * @throws GuzzleException
     */
    public function testShouldReturnAllYearsInRangeOf10YearsExceptNowAndFurther()
    {
        $startYear = strtotime( date('Y') . ' - 1 year');
        $endYear = strtotime( date('Y') . ' - 10 year');

        $randomYear = date('Y',(rand($startYear, $endYear)));
        $yearsAvailable =   $this->enemCrawler->examApplicationAvailable();
        $this->assertContains($randomYear, $yearsAvailable, 'Year not in\n Random year: '
            . $randomYear . PHP_EOL .
            ' Years Available: '
            . implode(',',$yearsAvailable));
    }
    /**
     * @throws GuzzleException
     */
    public  function  testAllYearsShouldBeNumeric()
    {
        $yearsAvailable =   $this->enemCrawler->examApplicationAvailable();

        foreach ($yearsAvailable as $year){
            $this->assertIsNumeric($year, 'This: ', $year  . ' was supposed to be a year');
        }
    }
    /**
     * @throws GuzzleException
     */
    public  function  testShouldGetThe200FromCurrentYearPage()
    {
        $statusCode = $this->enemCrawler->getCurrentYearPage()->getStatusCode();
        $this->assertEquals(200,$statusCode, 'Got wrong status, status: ' . $statusCode);
    }

    /**
     * @throws GuzzleException
     */
    public  function  testShouldFindTheNotebookColorLinks()
    {
        $links = $this->enemCrawler->findTheExamTestLinks($this->enemCrawler->getCurrentYearPage()->getBody()->getContents());
        $this->assertNotEmpty($links, "Exam with the color: {$this->caderno->value}  has no link");
    }
}
