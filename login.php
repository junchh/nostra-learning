<?php 

require_once 'conf.php';

import('Database.DBManager');
import('Database.ObjectMap.User');
import('Display.Template');

if(isset($_COOKIE['loginSession'])){
	header('Location: index.php');
}

$template = new Template('adminlte');

$template->setVar('site_root', Config::root . 'template/' . $template->getTemplateName() . '/');
$template->setVar('site_title', 'NLC | Log In');
$template->loadFile('login');

echo $template->display();
?>