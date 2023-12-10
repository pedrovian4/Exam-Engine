<?php

namespace Petcha\ExamEngine\Crawlers;

use DOMXPath;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Petcha\ExamEngine\Common\Enums\Brazil\EnemCaderno;
use Petcha\ExamEngine\Common\Enums\Difficulties;
use Petcha\ExamEngine\Common\Enums\Subjects;
use Petcha\ExamEngine\Common\Interfaces\CrawlerStrategy;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

class EnemCrawler implements CrawlerStrategy
{
    /**
     * @var Client
     */
    private readonly Client $client;
    /**
     * @var int
     */
    private readonly int $year;
    /**
     * @var EnemCaderno
     */
    private readonly EnemCaderno $caderno;
    private readonly \DOMDocument $doc;

    public function __construct(int $year, EnemCaderno $caderno)
    {
        $this->client = new Client([
            'base_uri' => 'https://www.gov.br/inep/pt-br/areas-de-atuacao/avaliacao-e-exames-educacionais/enem/provas-e-gabaritos/',
        ]);
        $this->year = $year;
        $this->caderno = $caderno;
        $this->doc = new \DOMDocument();

    }

    /**
     * @throws GuzzleException
     */
    public function fetchQuestions(Subjects $subject, Difficulties $difficulty, int $amount): array
    {
        $content = $this->getCurrentYearPage()->getBody()->getContents();
        $testsLinks = $this->findTheExamTestLinks($content);
        return [];
    }


    /**
     * @param $htmlContent
     * @return array
     */
    public function findTheExamTestLinks($htmlContent): array
    {
        $crawler = new Crawler($htmlContent);
        $links = [];

        $crawler->filter('p.callout')->each(function (Crawler $node) use (&$links) {
            $siblingUl = $node->siblings()->filter('ul')->first();
            $extractedLinks = $this->getLinksFromCard($siblingUl);
            $links = array_merge($links, $extractedLinks);
        });

        return $links;
    }

    /**
     * @param Crawler $ul
     * @return array
     */
    private function getLinksFromCard(Crawler $ul): array
    {
        $links = $ul->filter('li a')->each(closure: function (Crawler $node) {
            $href = $node->attr('href');
            if (preg_match('/\.pdf$/', $href)) {
                return $href;
            }
        });
        return array_filter($links);
    }


    /**
     * @throws GuzzleException
     */
    public function examApplicationAvailable(): array
    {
        $html = $this->client->get('')->getBody()->getContents();

        @$this->doc->loadHTML($html);

        $xpath = new DOMXPath(@$this->doc);
        $nodes = $xpath->query('//div[contains(@class, "tabs")]//a[@data-id]');

        $years = [];
        foreach ($nodes as $node) {
            if(($year =  $node->getAttribute('data-id')) && is_numeric($year)){
                $years[] = $node->getAttribute('data-id');
            }
        }

        return $years;
    }

    /**
     * @throws GuzzleException
     */
    public function getCurrentYearPage(): ResponseInterface
    {
        return $this->client->get(  "$this->year");
    }

}
