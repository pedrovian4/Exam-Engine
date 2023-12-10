<?php
namespace Petcha\ExamEngine\Pdfs;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Smalot\PdfParser\Parser;
use GuzzleHttp\Client;

class EnemPdfProcessor extends PdfProcessorTemplate
{

    /**
     * @throws GuzzleException
     */
    public  function  jsonConverter($pdfUrl): array
    {
        $text = $this->extractData($pdfUrl)["text"];

        $pattern = '/Questão (\d+)(.*?)\n(A) (.*?)\n(B) (.*?)\n(C) (.*?)\n(D) (.*?)\n(E) (.*?)(?=\nQuestão|\Z)/s';
        preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);

        $questions = [];

        foreach ($matches as $match) {
            $questionNumber = $match[1];
            $questionText = trim($match[2]);
            $alternatives = [
                'a' => trim($match[4]),
                'b' => trim($match[6]),
                'c' => trim($match[8]),
                'd' => trim($match[10]),
                'e' => trim($match[12]),
            ];

            $questions["Question $questionNumber"] = [
                'text' => $questionText,
                'alternatives' => $alternatives,
            ];
        }

        return $questions;
    }
}
