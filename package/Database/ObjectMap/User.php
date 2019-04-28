<?php 
class User {
	
	private $id;
	private $db;
	
	public function __construct($id, DBManager $db){
		$this->id = $id;
		$this->db = $db;
	}
	
	public function getValue($column){
		$queryString = "SELECT `" . $column . "` FROM `nostra_users` WHERE `id` = ?";
		$data = $this->db->query($queryString, $this->id);
		
		return $data[$column];
	}
	
	public function getValues(){
		$args = func_get_args();
		if(count($args) == 0){
			$queryString = 'SELECT * FROM `nostra_users` WHERE `id` = ?';
			$data = $this->db->query($queryString, $this->id);
		} else {
			$queryString = "SELECT `";
			for($i = 0; $i < count($args); $i++){
				if($i == count($args) - 1){
					$queryString .= $args[$i] . "`";
				} else {
					$queryString .= $value . "`,";
				}
			}
			$queryString .= ' FROM `nostra_users` WHERE `id` = ?';
			$data = $this->db->query($queryString, $this->id);
		}
		return $data;
	}
	
	public function updateValue($column, $value){
		$queryString = "UPDATE `nostra_users` SET `" . $column  . "` = '" . $value . "' WHERE `id` = ?";
		$this->db->query($queryString, $this->id);
	}
	
	public function increaseValue($column, $value){
		$queryString = "UPDATE `nostra_users` SET `" . $column  . "` = `" . $column . "` + " . $value . " WHERE `id` = ?";
		$this->db->query($queryString, $this->id);
	}
	
	public function decreaseValue($column, $value){
		$queryString = "UPDATE `nostra_users` SET `" . $column  . "` = `" . $column . "` - " . $value . " WHERE `id` = ?";
		$this->db->query($queryString);
	}
	
	public function getGravatar($size = 100){
		$email = $this->getValue('Email');
		$url = 'https://www.gravatar.com/avatar/';
		$url .= md5(strtolower(trim($email))) . '?s=' . $size;
		
		return $url;
	}
}