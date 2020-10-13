<?php
require_once __DIR__ . '/../vendor/autoload.php';

$smarty=new Smarty;
$smarty->template_dir = "../smarty/templates/";
$smarty->compile_dir = "../smarty/templates_c/";

$getvdeos="";
$videos=array();

session_start();
//検索キーワードが入力された場合にapiからデータをもらう
if (isset($_GET['keyword'])){

  $DEVELOPER_KEY=getenv("YOUTUBE_API");;
  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);
  $youtube = new Google_Service_YouTube($client);
  
  $_SESSION["q_word"]=$_GET['keyword'];
  $_SESSION["sort"]=$_GET["sort"];
  $_SESSION["type"]=$_GET["type"];
  //next、prevボタンをされたのなら前回確保したセッションを使い
  //ページ検索
  $page="";
  if(isset($_GET["next"])){
    $page=$_SESSION["next"];
  }else if(isset($_GET["prev"])){
    $page=$_SESSION["prev"];
  }
  
  //APIを使用してデータ取得
  if($_GET["type"]=="live"){
    $_GET["type"]=["completed","live","upcoming"];
  }
  try{
  $searchResponse = 
  $youtube->search->listSearch('id,snippet', array(
    'q' => $_GET["keyword"],
    'order'=>$_GET["sort"],
    'maxResults' =>$_GET["max_results"],
    'eventType'=>$_GET["type"],
    'pageToken'=>$page,
    'type'=>'video',
  ));
  }catch(Exception $e){
    print("エラーが起きました\n");
  }


 
  $_SESSION["next"]=$searchResponse["nextPageToken"];
  $_SESSION["prev"]=$searchResponse["prevPageToken"];
  


  foreach ($searchResponse['items'] as $searchResult) {
    if ($searchResult['id']['kind']=='youtube#video'){
        array_push($videos,
        array("title"=>$searchResult['snippet']['title'],
        "id"=>$searchResult['id']['videoId']));    
    }
    }
}
if(isset($videos)){
$smarty->assign("VIDEOS",$videos);
}
$smarty->display("GetVideo.html");



?>


