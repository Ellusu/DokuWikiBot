<?php
/**
 *  titolo: DokuWikiBot
 *  autore: Matteo Enna
 *  licenza GPL3
 **/

    class dokuimporterClass {
                
        public function __construct(){
            $this->telegram = new telegramClass();
            
        }
        
        public function getProcess(){
            $message = $this->telegram->getMessage();
                
            $this->page = $this->getDokusPages(true);
            
            if($message=="/start"){
                $this->telegram->message(welcome_message);
                die;
            }
            if($message=="/help"){
                $this->telegram->message(help_message);
                die;
            }
            if($message=="page"){
                $this->telegram->message(implode(acapo, $this->page));
                die;
            }
            $message_type = $this->messageType($message);
            if(!$message_type){
                $this->telegram->message(unknown_request);
                die;
            }
        }
        
        public function messageType($req){        
            $rev = explode("-", $req);
            if(count($rev)==1)
                $page = $req;
            else
                $page = $rev[0];  
            if($page===""){
                return FALSE;
            }
            if(!in_array($page, $this->page)){
                $this->telegram->message(unknown_page);
                die;
            }
            $this->column = $this->getDokusColumnsName($page);
            if(count($this->column) == 1) {
                $this->telegram->message(search_null);
                die;
            }
            
            if($page==$req || !$rev[1]){
                $this->telegram->message(implode(acapo, $this->column));
                die;
            }
            
            $res = explode(":",$rev[1]);
            $column = $res[0];
            
            if(!in_array($page, $this->page)){
                $this->telegram->message(unknown_column);
                die;
            }            
            $data = $res[1];
            
            if(!$data){
                $this->telegram->message(data_null);
                die;                
            }else{
                $richiesta = $this->findData($req);
                if(!$richiesta){
                    $this->telegram->message(search_null);
                    die;
                }else{
                    if (count($richiesta)>1) $this->telegram->message(count($richiesta)." risultati!");
                    else $this->telegram->message(count($richiesta)." risultato!");
                    
                    foreach($richiesta as $row){
                        $this->telegram->message(implode(acapo,$row));
                    }
                    
                    die;
                    
                }
            }
            
            return TRUE;
            
        }
        
        public function getDokusPages($key=FALSE){
            $page_list = array();
            $pages = scandir(doku_data);
            foreach($pages as $k => $page){
                if(!strpos($page,'.txt') || strpos("v".$page,'profile')){
                    unset($pages[$k]);
                }else{                    
                    $page_list[str_replace(".txt","",$page)] = $page;
                }
            }
            if($key) return array_keys($page_list);
            return $page_list;
        }
        
        public function getDokusColumnsName($page){            
            if($file=$this->getDokusFile($page)){
                return $this->scanPage($file);
            }else{
               return FALSE;
            }
        }
        
        public function getDokusColumnsContent($page){            
            if($file=$this->getDokusFile($page)){
                return $this->createTable($file);
            }else{
               return FALSE;
            }
        }
        
        public function getDokusFile($page){
            $page_list = $this->getDokusPages();
            $file = file_get_contents(doku_data.$page_list[$page]);
            if(!$file) return FALSE;
            return $file;
        }
        
        public function scanPage($file){
            $rows = explode(chr(10), $file);
            foreach($rows as $k => $row){
                if($row[0]=="^"){
                    $col = explode("^", $row);
                    unset($col[0]);
                    return $col;
                }
            }
            return FALSE;
        }
        
        public function createTable($file){
            $check = FALSE;
            $table = array();
            $rows = explode(chr(10), $file);
            foreach($rows as $k => $row){
                if($row[0]=="^" && !$check){
                    $cols = explode("^", $row);
                    unset($cols[0]);
                    $check = TRUE;
                }
                if($row[0]=="|" && $check){
                    $content = explode("|", $row);
                    $vars = array();
                    foreach($cols as $k => $col){
                        $vars[trim($col)]=$content[$k];
                    }
                    $table[] = $vars;
                }
            }
            if(!empty($table)) return $table;
            return FALSE;            
        }
        
        public function findData($req){
            $rev = explode("-",$req);
            $page = $rev[0];
            $res = explode(":",$rev[1]);
            $column = $res[0];
            $data = $res[1];
            $response = Array();
            if($table = $this->getDokusColumnsContent($page)){
                foreach ($table as $rows){
                    if($rows[$column]==$data) {                        
                        $response[] = $rows;
                    }
                }   
            }else{
                return FALSE;
            }
            if(!empty($response)) {
                $text_array = array();
                foreach($response as $k => $row){
                    $value = array();
                    $keys = array_keys($row);
                    foreach($keys as $key){
                        if($key) $value[] = $key.": ".$row[$key]; 
                    }
                    $text_array[] = $value;
                }
                return $text_array;
            }
            return FALSE;  
            
        }
        
    }
?>
