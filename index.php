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


$template->setVar('site_root', Config::root . 'template/' . $template->getTemplateName() . '/');
$template->setVar('site_title', 'Nostra Learning Center | Home');
$template->setVar('site_sidebar', $main->loadSidebar($user->getValue('Team'), 'Home'));
$template->setVar('user_name', $user->getValue('Name'));
$template->setVar('user_gravatar', $user->getGravatar());
$template->setVar('site_content', '  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Home</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 connectedSortable">
          <!-- /.nav-tabs-custom -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Welcome to Nostra Learning Center!</h3>
            </div>
			<div class="box-body">
				<p>Selamat datang di Nostra Learning Center (NLC) disini kamu dapat belajar materi olimpiade dan mengerjakan soal latihan secara online, kami juga menyediakan forum sebagai tempat diskusi, kami juga menyediakan simulasi olimpiade yang diadakan beberapa bulan sekali. bila perlu bantuan atau ada masalah silahkan hubungi admin, line:junchh</p>
			</div>
          </div>
		 
		  
        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>');
$template->setVar('script', 'home');
$template->loadFile('base');
echo $template->display();
?>