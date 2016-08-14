<?php
require("class-http-request.php");
require("config.php");
require("functions.php");

$content = file_get_contents('php://input');
$update = json_decode($content, true);

$msg = $update["message"]["caption"];
$chatID = $update["chat"]["id"];

if($msg == "#hashtag" && $chatID < 0/*If the ChatID is lower than 0 it's a group.*/){ //TODO: Set #hashtag.

  $file_id_array = $update["message"]["photo"];
  foreach ($file_id_array as $value) {
    $file_id = $value["file_id"];
  }

  $args = array(
    'file_id' => $file_id
  );

  //Get File.
  $r = new HttpRequest("post", "https://api.telegram.org/$api/getFile", $args);
  $rr = $r->getResponse();
  $ar = json_decode($rr, true);
  $file_path = $ar["file_path"];

  $file_url = "https://api.telegram.org/file/" . $api . "/" . $file_path; //File URL.
  $name = explode("/", $file_path);
  $name = $name[1]; //Filename.
  file_put_contents($name, fopen($file_url, "r")); //Download.

  //sm($chatID, "*Media saved!*"); //Uncomment this line if you want the bot notifies the rescuing.

} else if ($chatID > 0) { //If someone send a private message to the Bot.
  sm($chatID, "Hi! I work only in the groups!\nI'm an Open Source bot, you can see my code on [GitHub](https://github.com/franci22/DownImgBot)!");
}
