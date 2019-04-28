<?php 

class Topic {
	
	private $id;
	private $db;
	private $user;
	
	public function __construct($id, DBManager $db, User $user){
		$this->id = $id;
		$this->db = $db;
		$this->user = $user;
	}
}