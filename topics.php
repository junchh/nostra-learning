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

if(!isset($_GET['id'])){
	header('Location: forum.php');
}

if(isset($_GET['page'])){
	$page = $_GET['page'];
} else {
	$page = 1;
}


$template->setVar('site_root', Config::root . 'template/' . $template->getTemplateName() . '/');
$template->setVar('site_title', 'Nostra Learning Center | Topics');
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
        <div class="box-header with-border">
					<ol class="breadcrumb pull-left">
  <li><a href="forum.php">Forum</a></li>
  <li class="active"><a href="#">Olimpiade Sains</a></li>
</ol>
          <a href="#" class="btn btn-primary btn-flat pull-right">Create a new Topic</a>
        </div>
            <div class="box-body">
<table class="table forum table-striped">
    <thead>
      <tr>
        <th style="width: 10px"class="cell-stat"></th>
        <th style="width: 10px" class="cell-stat hidden-xs hidden-sm">Topics</th>
        <th style="width: 40px" class="cell-stat text-center hidden-xs hidden-sm">Replies</th>
        <th style="width: 40px" class="cell-stat text-center hidden-xs hidden-sm">Views</th>
        <th style="width: 40px" class="cell-stat-2x hidden-xs hidden-sm">Last Post</th>
      </tr>
    </thead>
    <tbody>
	{site_topics}
    </tbody>
  </table>
            </div>
            <!-- /.box-body -->
			<div class="box-footer">
				<ul class="pagination pull-right">
				{pagination}
				</ul>
			</div>
          </div>
		 
		  
        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>');
$template->setVar('site_topics', $forum->loadTopics($_GET['id'], $page));
$template->setVar('pagination', $forum->loadPagination($_GET['id'], $page));
$template->setVar('script', 'home');
$template->loadFile('base');
echo $template->display();
?>