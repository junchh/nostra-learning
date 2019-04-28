<?php 

require_once 'conf.php';

import('Database.DBManager');
import('Database.ObjectMap.User');
import('Display.Template');
import('Core.Main');
import('Site.Content.Ranking');

$template = new Template('adminlte');
$db = new DBManager(Config::host, Config::user, Config::pass, Config::db);
$main = new Main($db);

if(!$main->checkLogin()){
	header('Location: login.php');
}

$user = new User($main->getUserId(), $db);
$ranking = new Ranking($db);


$template->setVar('site_root', Config::root . 'template/' . $template->getTemplateName() . '/');
$template->setVar('site_title', 'Nostra Learning Center | Ranking');
$template->setVar('site_sidebar', $main->loadSidebar($user->getValue('Team'), 'Top Users'));
$template->setVar('user_name', $user->getValue('Name'));
$template->setVar('user_gravatar', $user->getGravatar());
$template->setVar('site_content', '  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Ranking</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
	  {ranking}
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>');
  $template->setVar('ranking', $ranking->load());
$template->setVar('script', 'home');
$template->loadFile('base');
echo $template->display();
?>