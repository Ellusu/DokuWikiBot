<?php
/**
 *  titolo: DokuWikiBot
 *  autore: Matteo Enna (http://matteoenna.it)
 *  licenza: GPL3
 **/

    define('BOT_TOKEN', '[<token-bot>]');
    define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
    
    define('dir_doku', '../../www/wiki/');
    define('doku_data', dir_doku.'data/pages/');

    define('type_error_message', "Formato sbagliato, digita un semplice messaggio");
    define('welcome_message', "Benvenuto nel mio bot");
    define('help_message', "Questa è la lista delle funzionalità");
    
    define('unknown_request',"richiesta sconosciuta");
    define('unknown_page',"pagina inesistente");
    define('unknown_column',"colonna inesistente");
    define('data_null',"nessun valore da cercare");
    define('search_null',"nessun valore trovato");
    
    define('acapo', "\n");
    
?>
