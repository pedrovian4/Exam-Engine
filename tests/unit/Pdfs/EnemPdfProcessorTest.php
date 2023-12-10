<?php

namespace unit\Pdfs;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Petcha\ExamEngine\Common\Enums\Brazil\EnemCaderno;
use Petcha\ExamEngine\Crawlers\EnemCrawler;
use Petcha\ExamEngine\Pdfs\EnemPdfProcessor;
use PHPUnit\Framework\TestCase;

class EnemPdfProcessorTest extends TestCase
{

    /**
     * @var EnemPdfProcessor
     */
    private readonly EnemPdfProcessor $enemPdfProcessor;
    private readonly int $year;

    /**
     * @return void
     */
    public  function  setUp(): void
    {
        parent::setUp();
        $this->enemPdfProcessor =  new EnemPdfProcessor();

        $this->caderno = EnemCaderno::CADERNO_AMARELO;
        $this->year = 2020;
        $this->enemCrawler = new EnemCrawler($this->year, $this->caderno );
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testShouldExtractData()
    {
       $links =  $this->enemCrawler->findTheExamTestLinks($this->enemCrawler->getCurrentYearPage()->getBody()->getContents());
       foreach ($links as $link){
           if (str_contains($link, $this->year.'_PV')) {
               $this->assertNotEmpty($this->enemPdfProcessor->extractData($link)["text"]);
               break;
           }
       }
    }

    /**
     * @throws GuzzleException
     */
    public  function testShouldGenerateJsonQuestionsSeparated()
    {
        $links =  $this->enemCrawler->findTheExamTestLinks($this->enemCrawler->getCurrentYearPage()->getBody()->getContents());
        foreach ($links as $link){
            if (str_contains($link, $this->year.'_PV')) {
                $json = $this->enemPdfProcessor->jsonConverter($link);
                $this->assertJson(json_encode($json));
                break;
            }
        }
    }

    /**
     * @throws GuzzleException
     */
    public  function  testShouldSaveJsonFileForAnalyze()
    {
        $pathToSave = 'src/data/enem';
        $links =  $this->enemCrawler->findTheExamTestLinks($this->enemCrawler->getCurrentYearPage()->getBody()->getContents());
        foreach ($links as $link){
            if (str_contains($link, $this->year.'_PV')) {
                $json = $this->enemPdfProcessor->jsonConverter($link);
                $file = $this->enemPdfProcessor->saveJsonFile($json, $pathToSave, $this->year.'_PV');
                $this->assertFileExists($file);
                unlink($file);
                break;
            }
        }
    }
}
