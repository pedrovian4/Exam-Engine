<?php

namespace Petcha\ExamEngine\Pdfs;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Petcha\ExamEngine\Common\Interfaces\ExamPDFProcessor;
use Smalot\PdfParser\Parser;

abstract class PdfProcessorTemplate implements ExamPDFProcessor
{

    /**
     * Extracts data from a PDF available at a given URL.
     *
     * @param string $pdfUrl URL of the PDF to process.
     * @return array Extracted data from the PDF.
     * @throws Exception
     * @throws GuzzleException
     */
    public function extractData(string $pdfUrl): array
    {
        $client = new Client([
            'verify' => false
        ]);
        $response = $client->get($pdfUrl);
        $pdfContent = $response->getBody();

        $tempFilePath = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($tempFilePath, $pdfContent);

        $parser = new Parser();
        $pdf = $parser->parseFile($tempFilePath);

        $text = $pdf->getText();

        unlink($tempFilePath);

        return ['text' => $text];
    }

    /**
     * Save Json file filtered
     *
     * @param array $jsonFilter
     * @param string $folder
     * @param string $name
     * @return string
     */
    public  function saveJsonFile(array $jsonFilter, string $folder, string $name): string
    {
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $jsonData = json_encode($jsonFilter, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $filePath = $folder . "/$name.json";

        file_put_contents($filePath, $jsonData);

        return $filePath;
    }
}