<?php 

require_once 'conf.php';

import('Database.DBManager');

$db = new DBManager(Config::host, Config::user, Config::pass, Config::db);

if(isset($_GET['type'])){
	$type = $_GET['type'];
	switch($type){
		case 'login':
			if(isset($_POST['Email'])){
				$email = $_POST['Email'];
				$password = createHash($_POST['Password']);
				$checked = $_POST['Checked'];
				
				$data[0] = $db->query('SELECT * FROM `nostra_users` WHERE `Email` = ? AND `Password` = ?', $email, $password);
				if($db->countRow() != 1){
					echo 'false';
				} else {
					$data[1] = $db->query('SELECT * FROM `nostra_loginsessions` WHERE `UserID` = ?', $data[0]['id']);
					if($db->countRow() > 0){
						$db->query('DELETE FROM `nostra_loginsessions` WHERE `UserID` = ?', $data[0]['id']);
					}
					
					$hash = createHash(strrev($data[0]['Password'] . rand(0,321) . time()));
					$db->query('INSERT INTO `nostra_loginsessions`(`Hash`, `UserID`) VALUES (?, ?)', $hash, $data[0]['id']);
					
					if($checked == 1){
						$time = new DateTime('+1 year');
						setcookie('loginSession', $hash, $time->getTimestamp());
					} else {
						setcookie('loginSession', $hash, 0);
					}
					echo 'true';
				}
			} else {
				echo 'false';
			}
			break;
			case 'test':
				import('Site.Content.Test');
				import('Database.ObjectMap.User');
				$data = $db->query('SELECT * FROM `nostra_loginsessions` WHERE `Hash` = ?', $_COOKIE['loginSession']);
				$user = new User($data['UserID'], $db);
				$test = new Test($db , $user, $_GET['action']);
				$test->load();
	}
} else {
	echo 'false';
}