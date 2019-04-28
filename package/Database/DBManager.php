<?php 

class DBManager {
	
	private $pdo;
	private $num_rows;	
	public function __construct($host, $user, $pass, $db){
		try {
			$this->pdo = new PDO('mysql:host=' . $host . ';dbname=' . $db, $user, $pass);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		}
		catch(PDOException $e){
			echo $e->getMessage();
			exit();
		}
	}
	
	public function query(){
		$args = func_get_args();
		$len = count($args);
		$queryString = $args[0];
		if(substr(strtolower($queryString), 0, 6) == 'select'){ //SELECT
			$return = true;
		} else {
			$return = false;
		}
		if($len == 1){
			$query = $this->pdo->prepare($args[0]);
			$query->execute();
			
			$this->num_rows = $query->rowCount();
			
			if($return){
				return $query->fetch(PDO::FETCH_ASSOC);
			}
		} else if($len > 1){
			array_shift($args);
			$query = $this->pdo->prepare($queryString);
			$query->execute($args);
			
			$this->num_rows = $query->rowCount();
			
			if($return){
				return $query->fetch(PDO::FETCH_ASSOC);
			}
		} else {
			return false;
		}
	}
	
	public function queryFetchAll(){
		$args = func_get_args();
		$len = count($args);
		$queryString = $args[0];
		if($len == 1){
			$query = $this->pdo->prepare($args[0]);
			$query->execute();
			$this->num_rows = $query->rowCount();
			return $query->fetchAll(PDO::FETCH_ASSOC);
		} else if($len > 1){
			array_shift($args);
			$query = $this->pdo->prepare($queryString);
			$query->execute($args);
			$this->num_rows = $query->rowCount();
			return $query->fetchAll(PDO::FETCH_ASSOC);
		} else {
			return false;
		}
	}
	
	public function countRow(){
		return $this->num_rows;
	}
}