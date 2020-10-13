<?php
class Register_Table{
public $mysqli;
    
    public function __construct($mysqli){
        $this->mysqli=$mysqli;
        $sql = 'CREATE TABLE IF NOT EXISTS register(
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        register_name VARCHAR(30),
        password VARCHAR(30)
        ) engine=innodb default charset=utf8';
        $res = $this->mysqli->query($sql);
        print( $this->mysqli->error);
        }

    
    public function insert($name,$password){
        $sql = "INSERT INTO register(
            register_name,password
            )VALUES ('$name','$password')";
        $res = $this->mysqli->query($sql);
        print( $this->mysqli->error);
    }

	public function read($id){
        $sql = 'SELECT * FROM register 
        WHERE id='.$id;
        $res = $this->mysqli->query($sql);
        $temp="";
		if($res){
		$temp = $res->fetch_all(MYSQLI_ASSOC);
		}
		return $temp;
    }
}
?>