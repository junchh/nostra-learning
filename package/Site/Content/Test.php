<?php 

class Test {
	
	private $db;
	private $action;
	private $user;
	
	public function __construct(DBManager $db, $user, $action){
		$this->db = $db;
		$this->action = $action;
		$this->user = $user;
	}
	
	public function load(){
		switch($this->action){
			case 'start':
				$hash[0] = createHash($_POST['id'] . strrev($this->user->getValue('Name') . rand(0, 321) . time()));
				$testID = $_POST['id'];
				$data = $this->db->query('SELECT * FROM `nostra_testsessions` WHERE `TestID` = ? AND `UserID` = ?', $testID, $this->user->getValue('id'));
				if($this->db->countRow() > 0){
					$hash[1] = $data['Hash'];
					$this->db->query('DELETE FROM `nostra_testsessions` WHERE `TestID` = ? AND `UserID` = ?', $testID, $this->user->getValue('id'));
					setcookie("test_sessions", $hash[1], time() - 3600);
				}
				$this->db->query('INSERT INTO `nostra_testsessions`(`Hash`, `TestID`, `UserID`) VALUES (?, ?, ?)', $hash[0], $testID, $this->user->getValue('id'));
				setcookie("test_sessions", $hash[0], time() + 3600*5);
				echo $this->user->getValue('id');
			break;
			
			case 'end':
				if(isset($_POST['id'])){
					$data[0] = $this->db->query('SELECT * FROM `nostra_testsessions` WHERE `Hash` = ?', $_COOKIE['test_sessions']);
					if($this->db->countRow() == 1){
						$soalID = $_POST['id'];
						$data[1] = $this->db->query('SELECT * FROM `nostra_tests` WHERE `id` = ?', $soalID);
						$ans = explode(',', $data[1]['Items']);
						$points = 0;
						for($i = 0; $i < count($ans);$i++){
							$data[2] = $this->db->query('SELECT * FROM `nostra_tests_items` WHERE `id` = ?', $ans[$i]);
							if($data[0]['Text'] == ''){
								break;
							} else {
								$array = $this->createArray($data[0]['Text']);
								if(array_key_exists($i + 1, $array)){
									if($array[$i + 1] == $data[2]['Answer']){
										$points += 1;
									}
								}
							}
						}
					}
					
					$userpoints = $this->user->getValue('Points') + $points;
	
	
					setcookie('test_sessions', null, time() - 3600);
					$this->db->query('DELETE FROM `nostra_testsessions` WHERE `Hash` = ?', $data[0]['Hash']);
					$this->user->IncreaseValue('Points', $points);
					echo 'true';
				} else {
					echo 'false';
				}
				break;
				
			case 'loaddisplay':
				if(isset($_POST['display'])){
					switch($_POST['display']){
						case 'sectsoal':
							echo '
								<div id="body-soal" class="box">
								</div>';
		 
							break;
						case 'navsoal':
							$soalID = $_POST['id'];
							echo '<div class="box">
							<div class="box-header">
							<h3 class="box-title">Navigasi Soal</h3>
							</div>
							<div class="box-body">
							<div class="nav-soal">';
							$data = $this->db->query('SELECT * FROM `nostra_tests` WHERE `id` = ?', $soalID);
							$no_soal = explode(',', $data['Items']);
							for($i = 1;$i <= count($no_soal);$i++){
								echo '<button class="nav-soal-btn" value="' . $i . '">' . $i . '</button>';
							}
							echo '</div>
								</div>
								<div class="box-footer">
									<button id="endtest" type="submit" class="btn btn-flat bg-purple pull-right">Selesai</button>
								</div>
							</div>';
							break;
					}
				}
				break;
				
			case 'load':
				if(isset($_POST['id'])){
					$soalID = $_POST['id'];
					$no = $_POST['no'];
					$data_soal = $this->db->query('SELECT * FROM `nostra_tests` WHERE `id` = ?', $soalID);
					$no_soal = explode(',', $data_soal['Items']);
					if($no <= count($no_soal)){
						$itemID = $no_soal[($no - 1)];
						$data_item = $this->db->query('SELECT * FROM `nostra_tests_items` WHERE `id` = ?', $itemID);
						$answers = '';
						if($data_item['MultipleChoices'] == 1){
							$choices = explode(',', $data_item['Options']);
							for($i = 0; $i < count($choices);$i++){
								$answers .= '<div class="radio">
									<label>
									<input type="radio" name="optSoal" value="' . ($i+1) . '">
									' . $choices[$i] . '
									</label>
								</div>';
							}
						} else {
							$answers = '<input type="text" name="answer" id="answer" class="form-control" placeholder="Masukkan Jawaban...">';
						}
						
						echo '<div class="box-header">
							<h3 class="box-title">' . $data_soal['Title']. '</h3>
							</div>
							<div class="box-body">
								<p><span>' . $no . '.</span>' . $data_item['Text'] . '</p>
								<div class="form-group">
								' . $answers . '
								</div>
							</div>
							<div class="box-footer">
								<button id="btn-save" name="btn-save" type="submit" class="btn btn-flat bg-purple pull-right">Simpan Jawaban</button>
							</div>
							';
					} else {
						exit('off limit');
					}
				} else {
					exit('undefined get request');
				}
				break;
				
			case 'save':
				if(isset($_POST['ans'])){
					$hash = createHash($_POST['soal_id'] . strrev($this->user->getValue('Name') . rand(0, 321) . time()));
					$data = $this->db->query('SELECT * FROM `nostra_testsessions` WHERE `TestID` = ? AND `UserID` = ?', $_POST['soal_id'], $this->user->getValue('id'));
					if($this->db->countRow() == 0){
						$text = $_POST['no'] . ':' . $_POST['ans'];
						$this->db->query('INSERT INTO `nostra_testsessions`(`Hash`, `Text`, `TestID`, `UserID`) VALUES (?,?,?,?)', $hash, $text, $_POST['soal_id'], $this->user->getValue('id'));
						setcookie("test_sessions", $hash, time() + 3600*5);
					} else {
						$text = $data['Text'];
						if($text == ''){
							$text = $_POST['no'] . ':' . $_POST['ans'];
						} else {
							$array = $this->createArray($text);
							if(array_key_exists($_POST['no'], $array)){
								$array[$_POST['no']] = $_POST['ans'];
								$text = $this->ArraytoText($array);
							}  else {
								$text .= ',' . $_POST['no'] . ':' . $_POST['ans'];
							}
						}
						$this->db->query('UPDATE `nostra_testsessions` SET `Text` = ? WHERE `Hash` = ?', $text, $data['Hash']);
					}
					echo 'true';
				}
				break;
		}
	}
	
	private function createArray($text){
		$do1 = explode(',', $text);
		$array = [];
		for($i = 0; $i < count($do1);$i++){
			$do2 = explode(':', $do1[$i]);
			$array[$do2[0]] = $do2[1];
		}
		return $array;
	}

	private function ArraytoText($array){
		$text = '';
		$i = 0;
		foreach($array as $key => $value){
			if($i == 0){
				$text .= $key . ':' . $value;
			} else {
				$text .= ',' . $key . ':' . $value;
			}
			$i++;
		}	
		return $text;
	}
	
	
}