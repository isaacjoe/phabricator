<?php

abstract class PhabricatorCalendarICSImportEngine
  extends PhabricatorCalendarImportEngine {

  final protected function importICSData(
    PhabricatorUser $viewer,
    PhabricatorCalendarImport $import,
    $data) {

    $parser = new PhutilICSParser();

    try {
      $document = $parser->parseICSData($data);
    } catch (PhutilICSParserException $ex) {
      // TODO: In theory, it would be nice to store these in a fully abstract
      // form so they can be translated at display time. As-is, we'll store the
      // error messages in whatever language we were using when the parser
      // failure occurred.

      $import->newLogMessage(
        PhabricatorCalendarImportICSLogType::LOGTYPE,
        array(
          'ics.code' => $ex->getParserFailureCode(),
          'ics.message' => $ex->getMessage(),
        ));

      $document = null;
    }

    return $this->importEventDocument($viewer, $import, $document);
  }

}
