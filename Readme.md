

# ENEM Exam Web Crawler

## Overview
The ENEM Exam Web Crawler is part of the `petcha/exam-engine` library, designed to extract data from Brazil's ENEM exam portals. This tool is built to navigate efficiently and retrieve exam-related information.

## Installation
Ensure you have PHP and Composer installed on your system. To use the ENEM Exam Web Crawler, include it in your project using Composer:

```bash
composer require petcha/exam-engine
```

## Usage

### Initializing the Crawler
Create an instance of the `EnemCrawler` by specifying the year and the caderno (notebook color) you are interested in:

```php
use Petcha\ExamEngine\Common\Enums\Brazil\EnemCaderno;
use Petcha\ExamEngine\Crawlers\EnemCrawler;

$caderno = EnemCaderno::CADERNO_AMARELO; // Choose the desired caderno color
$year = 2020; // Specify the year

$enemCrawler = new EnemCrawler($year, $caderno);
```

### Fetching Exam Application Years
To get the list of available years for the exam applications:

```php
$yearsAvailable = $enemCrawler->examApplicationAvailable();
```

### Getting Exam Links
To retrieve links to the exam and answer sheets for the specified caderno and year:

```php
$links = $enemCrawler->findTheExamTestLinks();
```

## Tests
This crawler includes PHPUnit tests to ensure reliability. The tests cover checking available years, verifying the numeric format of years, ensuring successful page access, and finding relevant links based on the notebook color.

---

### Note
- The `petcha/exam-engine` library is currently under active development, and this documentation will be updated as new features are added and enhancements are made.
- For any questions or contributions, please contact the author at `pedrovianaasking@gmail.com`.

This README provides a concise guide for users to quickly understand how to integrate and use the ENEM exam web crawler in their projects.