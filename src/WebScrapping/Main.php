<?php

namespace Galoa\ExerciciosPhp2022\WebScrapping;

use DOMDocument;
use DOMXpath;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

/**
 * Runner for the Webscrapping exercice.
 */
class Main {

  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void {
    $dom = new DOMDocument('1.0', 'utf-8');
    $dom->loadHTMLFile(__DIR__ . '/../../webscrapping/origin.html');
    (new Scrapper())->scrap($dom);
    $domXPath = new DOMXPath($dom);
    $content = $domXPath->query("//*[@class='paper-card p-lg bd-gradient-left']");
    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToFile('./webscrap.xlsx');
    
    foreach ($content as $entry) {
      $id = $entry->lastChild->lastChild->nodeValue;
      $title = $entry->firstChild->nodeValue;
      $type = $entry->lastChild->firstChild->nodeValue;
      $authorsString = $entry->firstChild->nextSibling->nodeValue;
      $authorsArray = explode("; ", $authorsString);
      $institution = $entry->firstChild->nextSibling->firstChild->attributes[0]->nodeValue;
      $paper = [$id , $title , $type];
      foreach($authorsArray as $author){
        $paper[] = $author;
        $paper[] = $institution;
      }
      $rowFromValues = WriterEntityFactory::createRowFromArray($paper);
      $writer->addRow($rowFromValues);
    }

    $writer->close();
  }
}
