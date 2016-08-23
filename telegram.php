<?php
/**
 *  titolo: DokuWikiBot
 *  autore: Matteo Enna (http://matteoenna.it)
 *  licenza GPL3
 **/

    require_once('config.php');
    require_once('dokuimporterClass.php');
    require_once('telegramClass.php');
    
    $telegram = new dokuimporterClass();
    $telegram->getProcess();
    
?>
