<?php
/**
 *  titolo: DokuWikiBot
 *  autore: Matteo Enna (http://matteoenna.it)
 *  licenza GPL3
 **/

    class telegramClass {
        
        public $chatID = 0;
        public $message_text = "";
        
        function __construct(){
            $content = file_get_contents("php://input");
            $update = json_decode($content, true);
            $this->chatID = $update["message"]["chat"]["id"];
            if(!array_key_exists('text', $update["message"])){
                $this->message(type_error_message);
                die;
            }else{
                $this->message_text = $update["message"]["text"];
            }
        }
        
        function message($message){
            $sendto =API_URL."sendmessage?chat_id=".$this->chatID."&text=".urlencode($message);
            file_get_contents($sendto);
        }
        
        function getMessage(){
            return $this->message_text;
        }
        
    }
?>
