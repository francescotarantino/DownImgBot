<?php
require("class-http-request.php");
require("config.php");
require("functions.php");

$content = file_get_contents('php://input');
$update = json_decode($content, true);

$msg = $update["message"]["caption"];
$chatID = $update["chat"]["id"];

if($msg == "#hashtag"){ //Set #hashtag.

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

}
