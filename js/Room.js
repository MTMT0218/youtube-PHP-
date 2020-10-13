
jQuery(function($){


    $("form").submit(function(){
        if(!confirm("現在の部屋を削除しますか？")){
            return false;
        }
        
    })
})