<?php 

require_once 'conf.php';

import('Database.DBManager');
import('Database.ObjectMap.User');
import('Display.Template');
import('Core.Main');
import('Site.Content.Topic');

$template = new Template('adminlte');
$db = new DBManager(Config::host, Config::user, Config::pass, Config::db);
$main = new Main($db);

if(!$main->checkLogin()){
	header('Location: login.php');
}

$user = new User($main->getUserId(), $db);

if(!isset($_GET['id'])){
	header('Location: forum.php');
}

if(isset($_GET['page'])){
	$page = $_GET['page'];
} else {
	$page = 1;
}

$topic = new Topic($_GET['id'], $db, $user);


$template->setVar('site_root', Config::root . 'template/' . $template->getTemplateName() . '/');
$template->setVar('site_title', $topic->title());
$template->setVar('site_sidebar', $main->loadSidebar($user->getValue('Team'), 'Home'));
$template->setVar('user_name', $user->getValue('Name'));
$template->setVar('user_gravatar', $user->getGravatar());
$template->setVar('site_content', '    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Forum</h1>
    </section>
	
    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-body">
	<ol class="breadcrumb">
  <li><a href="#">Forum</a></li>
  <li><a href="#">{category}</a></li>
  <li class="active">{site_title}</li>
</ol>
<h3>{site_title}</h3>
<hr>
			<?php 
				echo $content;
			?>
            </div>
            <!-- /.box-body -->
			<div class="box-footer">
				<ul class="pagination pull-right">
				{pagination}
				</ul>
			</div>
          </div>
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Reply to this thread</h3>
              <!-- /. tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body pad">
              <form>
                <textarea  id="replyText" class="textarea" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
              </form>
            </div>
			<div class="box-footer clearfix no-border">
              <button id="btn-reply" type="button" class="btn btn-primary btn-flat pull-right">Post Reply</button>
            </div>
          </div>

          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>');
$template->setVar('site_topics', $topic->load($page));
$template->setVar('pagination', $topic->loadPagination($page));
$template->setVar('script', 'home');
$template->loadFile('base');
echo $template->display();
?>