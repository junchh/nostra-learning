<?php 

require_once 'conf.php';

import('Database.DBManager');
import('Database.ObjectMap.User');
import('Display.Template');
import('Core.Main');
import('Site.Content.Bab');

$template = new Template('adminlte');
$db = new DBManager(Config::host, Config::user, Config::pass, Config::db);
$main = new Main($db);

if(!$main->checkLogin()){
	header('Location: login.php');
}

if(!isset($_GET['id'])){
	exit('unknown id.');
}

$user = new User($main->getUserId(), $db);
$bab = new Bab($db, $user->getValue('Team'));


$template->setVar('site_root', Config::root . 'template/' . $template->getTemplateName() . '/');
$template->setVar('site_title', 'Nostra Learning Center | Bab ');
$template->setVar('site_sidebar', $main->loadSidebar($user->getValue('Team'), 'Materi'));
$template->setVar('user_name', $user->getValue('Name'));
$template->setVar('user_gravatar', $user->getGravatar());
$template->setVar('site_content', $bab->loadContent());
$template->setVar('script', 'bab');
$template->loadFile('base');
echo $template->display();
?>