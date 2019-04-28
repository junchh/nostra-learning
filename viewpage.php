<?php 

require_once 'conf.php';

import('Database.DBManager');
import('Database.ObjectMap.User');
import('Display.Template');
import('Core.Main');

$template = new Template('adminlte');
$db = new DBManager(Config::host, Config::user, Config::pass, Config::db);
$main = new Main($db);

if(!$main->checkLogin()){
	header('Location: login.php');
}

$user = new User($main->getUserId(), $db);

if(isset($_GET['id'])){
	$data = $db->query('SELECT `Name` FROM `nostra_materi` WHERE `id` = ?', $user->getValue('Team'));
	$team = $data['Name'];
	
	$page = $db->query('SELECT * FROM `nostra_posts` WHERE `id` = ?', $_GET['id']);
} else {
	$content = '';
}
$template->setVar('site_root', Config::root . 'template/' . $template->getTemplateName() . '/');
$template->setVar('site_title', 'Nostra Learning Center | Home');
$template->setVar('site_sidebar', $main->loadSidebar($user->getValue('Team'), 'Home'));
$template->setVar('user_name', $user->getValue('Name'));
$template->setVar('user_gravatar', $user->getGravatar());
$template->setVar('site_content', '    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>{user_team}</h1>
    </section>
	
	<section class="content">
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">{page_title}</h3>
					</div>
					
					<div class="box-body">
						<div class="materi-content">
						{page_content}
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
  </div>');
$template->setVar('user_team', $team);
$template->setVar('page_title', $page['Title']);
$template->setVar('page_content', $page['Content']);
$template->setVar('script', 'home');
$template->loadFile('base');
echo $template->display();
?>