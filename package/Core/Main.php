<?php 
class Main {
	
	private $db;
	
	public function __construct(DBManager $db){
		$this->db = $db;
	}
	
	public function checkLogin(){
		if(!isset($_COOKIE['loginSession'])){
		header('Location: login.php');
		}
		$hash = $_COOKIE['loginSession'];
		$data = $this->db->query('SELECT `UserID` FROM `nostra_loginsessions` WHERE `Hash` = ?', $hash);
		if($this->db->countRow() != 0){
			$UserID = $data['UserID'];
			$data = $this->db->query('SELECT * FROM `nostra_loginsessions` WHERE `UserID` = ?', $UserID);
			if($this->db->countRow() > 1){
				$this->db->query('DELETE FROM `nostra_loginsessions` WHERE `UserID` = ', $UserID);
				header('Location: login.php');
			} else if($this->db->countRow() == 1){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function getUserId(){
		$hash = $_COOKIE['loginSession'];
		$data = $this->db->query('SELECT `UserID` FROM `nostra_loginsessions` WHERE `Hash` = ?', $hash);
		return $data['UserID'];
	}
	
	public function loadSidebar($team, $active){
		$array = $this->db->queryFetchAll('SELECT * FROM `nostra_sidebar`');
		
		$userTeam = $team;
		$data[0] = $this->db->query('SELECT * FROM `nostra_materi` WHERE `id` = ?', $userTeam);
		$dataBab = explode(',', $data[0]['Item']);

		$txt[0] = '<ul class="treeview-menu">';
	
		for($i = 0;$i < count($dataBab);$i++){
			$data[1] = $this->db->query('SELECT * FROM `nostra_bab` WHERE `id` = ?', $dataBab[$i]);
			$txt[0] .= '<li><a href="bab.php?id=' . $data[1]['id'] . '"><i class="fa fa-circle-o"></i> Bab ' . ($i+1) . ' - ' . $data[1]['Name'] . '</a></li>';
		}
	
		$txt[0] .= '</lu>';
	
		$txt[1] = '';
	
		for($i = 0;$i < count($array);$i++){
			if($active == $array[$i]['Name']){
				$li = '<li class="active">';
			} else {
				$li = '<li>';
			}
			$txt[1].= $li . '<a href="' . $array[$i]['Link'] . '"><i class="fa fa-' . $array[$i]['Icon'] . '"></i> <span>' . $array[$i]['Name'] . '</span>';
			if($array[$i]['Dropdown'] == '1'){
				$txt[1] .= '<i class="fa fa-angle-left pull-right"></i>';
			}
			$txt[1] .= '</a>';
			if($array[$i]['Special'] == 'materi'){
				$txt[1] .= $txt[0];
				$txt[1] .= '</ul>';
			}
			$txt[1] .= '</li>';
		}
		
		return $txt[1];
	}
}