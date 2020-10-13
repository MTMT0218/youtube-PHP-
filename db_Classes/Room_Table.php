<?php

class Room_Table{
    public $mysqli;

    public function __construct($mysqli){
        $this->mysqli=$mysqli;
    }

    
    public function insert($name){
        $now = date('Y-m-d H:i:s');
        $sql = sprintf("INSERT INTO %s.room(
            room_name,time
            )VALUES ('$name','$now')",getenv("DB_DATABASE"));
        $res = $this->mysqli->query($sql);
        if($this->mysqli->error){
            printf("insert failed room: %s\n",$this->mysqli->error);
        }
        #作ったルームのidを取得
        $sql ='SELECT LAST_INSERT_ID()';
        $res = $this->mysqli->query($sql);
        $temp = $res->fetch_all();
        return $temp;

    }

	public function ALL_read(){
        $sql = sprintf('SELECT * FROM %s.room',getenv("DB_DATABASE"));
        $res = $this->mysqli->query($sql);
        $temp="";
		if($res){
		    $temp = $res->fetch_all(MYSQLI_ASSOC);
        }
        if($this->mysqli->error){
            printf("Read failed room: %s\n",$this->mysqli->error);
        }
        return $temp;
    }

    public function read($room_id){
        $sql = sprintf('SELECT * FROM  %s.room WHERE id=%s',getenv("DB_DATABASE"),$room_id);
        $res = $this->mysqli->query($sql);
        $temp="";
		if($res){
		    $temp = $res->fetch_all(MYSQLI_ASSOC);
        }
        if($this->mysqli->error){
            printf("Read failed room: %s\n",$this->mysqli->error);
        }
        return $temp;
    }
    
    //削除
    public function delete($room_id){
        $sql=sprintf("DELETE FROM %s.room WHERE id=".$room_id,getenv("DB_DATABASE"));
        $res = $this->mysqli->query($sql);
        if(!$res){
            printf("delete failed room: %s\n",$this->mysqli->error);
        }
    }
}
?>