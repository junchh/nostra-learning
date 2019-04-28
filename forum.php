<?php 

require_once 'conf.php';

import('Database.DBManager');
import('Database.ObjectMap.User');
import('Display.Template');
import('Core.Main');
import('Site.Content.Forum');

$template = new Template('adminlte');
$db = new DBManager(Config::host, Config::user, Config::pass, Config::db);
$main = new Main($db);

if(!$main->checkLogin()){
	header('Location: login.php');
}

$user = new User($main->getUserId(), $db);
$forum = new Forum($db, $user);


$template->setVar('site_root', Config::root . 'template/' . $template->getTemplateName() . '/');
$template->setVar('site_title', 'Nostra Learning Center | Forum');
$template->setVar('site_sidebar', $main->loadSidebar($user->getValue('Team'), 'Home'));
$template->setVar('user_name', $user->getValue('Name'));
$template->setVar('user_gravatar', $user->getGravatar());
$template->setVar('site_content', '  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Forum</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 connectedSortable">
          <!-- /.nav-tabs-custom -->
          <div class="box box-primary">
            <div class="box-body">
			<ol class="breadcrumb">
  <li class="active"><a href="#">Forum</a></li>
</ol>
<table class="table forum table-striped">
    <thead>
      <tr>
        <th style="width: 10px"class="cell-stat"></th>
        <th style="width: 10px" class="cell-stat hidden-xs hidden-sm">Forums</th>
        <th style="width: 40px" class="cell-stat text-center hidden-xs hidden-sm">Topics</th>
        <th style="width: 40px" class="cell-stat text-center hidden-xs hidden-sm">Posts</th>
        <th style="width: 40px" class="cell-stat-2x hidden-xs hidden-sm">Last Post</th>
      </tr>
    </thead>
    <tbody>
	{site_forum}
    </tbody>
  </table>
            </div>
            <!-- /.box-body -->
          </div>
		 
		  
        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>');
$template->setVar('site_forum', $forum->load());
$template->setVar('script', 'home');
$template->loadFile('base');
echo $template->display();
?>