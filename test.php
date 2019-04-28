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
	$soalID = $_GET['id'];
	$data = $db->query('SELECT * FROM `nostra_tests` WHERE `Team` = ? AND `id` = ?', $user->getValue('Team'), $soalID);
	$footer = '<button id="btn-start" name="btn-start" type="submit" class="btn btn-flat bg-purple pull-right">Mulai <i class="fa fa-arrow-circle-right"></i></button>';
	$script = 'test';
	$timelimit = $data['TimeLimit'];
	$id = $data['id'];
	if($db->countRow() < 1){
		$data['Title'] = 'Unknown Test';
		$data['Text'] = 'This test is not available';
		$footer = '';
		$script = 'home';
	}
} else {
	$data['Title'] = 'Unknown Test';
	$data['Text'] = 'This test is not available';
	$footer = '';
	$script = 'home';
}


$template->setVar('site_root', Config::root . 'template/' . $template->getTemplateName() . '/');
$template->setVar('site_title', 'Nostra Learning Center | Home');
$template->setVar('site_sidebar', $main->loadSidebar($user->getValue('Team'), 'Home'));
$template->setVar('user_name', $user->getValue('Name'));
$template->setVar('user_gravatar', $user->getGravatar());
$template->setVar('site_content', '  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Online Test</h1>
	  <div id="timer"></div>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
	    <section id="start-text" class="col-lg-12 connectedSortable">
          <!-- /.nav-tabs-custom -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">{test_title}</h3>
            </div>
			<div class="box-body">
				<p>{test_text}</p>
			</div>
			<div class="box-footer">
			{footer}
			</div>
          </div>
		 
		  
        </section>
        <!-- Left col -->
        <section id="section-soal" class="col-md-8">
        </section>
		<section id="section-nav" class="col-md-4">
		</section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>');
$template->setVar('test_title', $data['Title']);
$template->setVar('test_text', $data['Text']);
$template->setVar('get_id', $id);
$template->setVar('timelimit', $timelimit);
$template->setVar('footer', $footer);
$template->setVar('script', $script);
$template->loadFile('base');
echo $template->display();
?>