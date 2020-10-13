
jQuery(function($){
  
  disabled_make_room();
  //ページを読み込んだら、チェックボックスにクッキーの値を反映する  
  if(Cookies.get("get_videos")!=undefined){    
    var load_values=JSON.parse(Cookies.get("get_videos"));
    view_cokkie(load_values);
    check_in_box(load_values);
  }

  //チェックを変えたときにクッキーの中身を変更するイベント
  $("input[type=checkbox][name=video]").change(function(){
    if(Cookies.get("get_videos")=="null"||
      Cookies.get("get_videos")==undefined||
      Cookies.get("get_videos")=={}){//クッキー自体ないなら宣言
        var save_values = [];
        }else{//あるなら持ってくる
          var save_values=Cookies.get("get_videos");
          save_values=JSON.parse(save_values);
        }
      
      //チェックされた動画はクッキーに保存
      $("input[type=checkbox][name=video]").each(function(){
        if(this.checked){
          var encode_value=encodeURIComponent(this.value); 
          var encode_id=encodeURIComponent(this.id);
          var exit_video_FLAG=0;//すでにクッキーにチェックされた動画あるか否か
          var temp={};
          temp={"id":encode_id,"value":encode_value};
          if(save_values.length>5){
            alert("選択できる動画は6個までです");
           this.checked=false; 
          }
          else{
          for (var i=0; i<save_values.length; i++) {
            if(save_values[i]["id"]==encode_id){
                  exit_video_FLAG=1;
                  break;
              }            
          }
          
          if(exit_video_FLAG==0){
              save_values.push(temp);
            }
           }
      }
        
        //チェック外された動画はクッキーから削除
        else{
          var encode_id=encodeURIComponent(this.id);
          for (var i=0; i<save_values.length; i++) {
            if(save_values[i]["id"]==encode_id){
                save_values.splice(i,1);
                break;
              }            
            }
        }
      })

        Cookies.set("get_videos",save_values);
        view_cokkie(save_values);
        disabled_make_room();
  
  });
        
    $("input[type=checkbox][name=del]").change(function(){
          Cookies.remove('get_videos');
    });

    
    $(document).on('click', 'input[name=del_video]', function() {
     if(confirm(this.value+"を指定動画から外しますか？")){
      var load_values=JSON.parse(Cookies.get("get_videos"));
      var save_values=load_values;
      for (var i=0; i<save_values.length; i++) {
        var encode_id=encodeURIComponent(this.id);
        if(save_values[i]["id"]==encode_id){
          save_values.splice(i,1);
            break;
          }            
        }
      Cookies.set("get_videos",save_values); 
      view_cokkie(save_values); 
      check_in_box(save_values);
     }
     else{this.checked=false;}
    });

});

function view_cokkie(load_values){//htmlにクッキーにある動画の名前表示
  $('p').html('');
      for (var i=0; i<load_values.length; i++) {
        $("p").append(decodeURIComponent(
          load_values[i]["value"])+"<br>");
        
        $("p").append('<input type="checkbox" name="del_video" id='
          +load_values[i]["id"]+" value="+
          decodeURIComponent(load_values[i]["value"])+'>'+"<br>");       
    }
}

function disabled_make_room(){//なにも動画が指定されていないときルーム作成ボタンを無効化する
  if(Cookies.get("get_videos")=="[]"
  ||Cookies.get("get_videos")==undefined){
    $('input[type=submit][name=make_room]').prop('disabled', true); 
  }else{
    $("input[type=submit][name=make_room]").prop('disabled', false); }
} 

function check_in_box(load_values){//チェックボックスにクッキー情報反映
  $("input[type=checkbox][name=video]").each(function(){
    for (var i=0; i<load_values.length; i++) {
      this.checked=false;
      if(load_values[i]["id"] === this.id) {
        this.checked=true;
      }
    }
  });


}