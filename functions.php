<?php
function sm($chatID, $text, $rmf = false, $inline = false, $pm = 'Markdown', $dis = false, $replyto = false, $preview = false){
  //Function for send messages.
  global $api;
  global $update;

  if($text){
    if($inline){
      $rm = array('inline_keyboard' => $rmf);
    }else{
      $rm = array('keyboard' => $rmf,
      'resize_keyboard' => true
      );
    }

    $rm = json_encode($rm);

    $args = array(
      'chat_id' => $chatID,
      'text' => $text,
      'disable_notification' => $dis,
      'parse_mode' => $pm
    );

    if($replyto) $args['reply_to_message_id'] = $update["message"]["message_id"];

    if($rmf) $args['reply_markup'] = $rm;

    if($preview) $args['disable_web_page_preview'] = $preview;

    $r = new HttpRequest("post", "https://api.telegram.org/$api/sendMessage", $args);
    $rr = $r->getResponse();
    $ar = json_decode($rr, true);
    $error_code = $ar["error_code"];
    if($error_code == "403"){
        //This user has blocked the bot.
    }
  }
  /*$var=fopen("log.txt","a+");
  fwrite($var, implode($ar) . "\n"); //For log.
  fclose($var);*/
  return $ar;
}
