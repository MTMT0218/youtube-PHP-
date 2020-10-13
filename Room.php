<?php
require_once __DIR__ . '/../vendor/autoload.php';

require("db_Classes/Mysql.php");

$smarty=new Smarty;
$smarty->template_dir = "../smarty/templates/";
$smarty->compile_dir = "../smarty/templates_c/";

$mysql=new Mysql();
$mysql->connect_mysqli();
$mysql->create_room_table();
$mysql->create_video_table();
$room_table=$mysql->room_table;
$video_table=$mysql->video_table;
session_start();
$videos="";
$room="";

 //video取得ページから移行されたときvideotableにvideo情報格納
if(isset($_POST["make_room"])){
    //クッキーに動画がない時
    if(!isset($_COOKIE["get_videos"])){
        echo "動画を指定してください<br />";
        echo'<a href= "Home.php" target="_blank">
        ホームに戻る </a><br>';
        exit();
    }
    $room_id=$room_table->insert($_POST["room_name"])[0][0];
    if(isset($_COOKIE["get_videos"])){
        $json_cookie_videos=json_decode($_COOKIE["get_videos"]);
        foreach($json_cookie_videos as $J){ 
            $video_table->insert($room_id,
            $mysql->Escape(urldecode ( $J->value)),$mysql->Escape($J->id));
        }
        $_SESSION["room_id"]=$room_id;
    setcookie("get_videos", "", time() - 30, '/');
    header("Location:Room.php");
    }
}

//ホームページから移行されたとき
else if(isset($_GET["room_id"])){
    $room_id=$_GET["room_id"];
    $_SESSION["room_id"]=$room_id;
}

if(isset($_POST["del_room"])){
    $room_id=$_SESSION["room_id"];
    $room_table->delete($room_id);

    $video_table->delete($room_id);
    header("Location:./Home.php");
}

if(isset($_SESSION["room_id"])){
$room=$room_table->read($_SESSION["room_id"]);
$videos=$video_table->read($_SESSION["room_id"]);
}
else{//不正に移行（url直打ち）してきたときはhomeに移行
    header("Location:./Home.php");
    exit;
}

$smarty->assign("VIDEOS",$videos);
$smarty->assign("ROOM",$room[0]);
$smarty->display("Room.html")



?>

