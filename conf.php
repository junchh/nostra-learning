<?php 

interface Config {

	const host = 'localhost';
	const user = 'root';
	const pass = 'root';
	const db = 'nostralearning';
	
	const root = '/Nostra/';
}

function import($string){
	$arr = explode('.', $string);                                 
	$num = count($arr);
	$res = '';		                                     
	for($i = 0; $i < $num - 1; $i++){
		$res .= '/' . $arr[$i];
	}
	try {
		$file = '..' . Config::root . 'package' . $res . '/' . $arr[$num - 1] . '.php';
		if(!file_exists($file)){
			throw new Exception('Cannot import package. file not found, file name: ' . $file);
		}
		require_once $file;
	} 
	catch(Exception $e){
		echo $e->getMessage();
		exit();
	}
}

function createHash($string){
	$salt = 'BIOSAIU92U0U90QUW9QGH2091Z';
	$len = $string % 26;
	$var = substr($salt, $len);
	$string = strrev(md5($var . $string));
	
	return strtoupper(substr($string, 0, 18));
}