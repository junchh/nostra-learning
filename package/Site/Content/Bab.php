<?php 

class Bab {
	
	private $db;
	private $team;
	
	public function __construct(DBManager $db, $team){
		$this->db = $db;
		$this->team = $team;
	}
	public function loadContent(){
		$content = '';
		if(isset($_GET['id'])){
			$babID = $_GET['id'];
	
			$data[0] = $this->db->query('SELECT * FROM `nostra_bab` WHERE `id` = ?', $babID);
			$data[2] = $this->db->query('SELECT * FROM `nostra_materi` WHERE `id` = ?', $this->team);
			$xmlObj = new SimpleXMLElement($data[0]['Content']);
	
			$content = '<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>' . $data[2]['Name'] . '</h1>
    </section>
	
	<section class="content">
		<div class="row">
			<div class="col-md-8">
			<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Bab 1 - Pendahuluan</h3>
							</div>
							<div class="box-body">';
	
			
			$n = 1;
			$color = ['purple', 'maroon', 'navy', 'orange', 'olive'];
			foreach($xmlObj->subbab as $child){
				$sub_name = $child->attributes()['title'];
				$content .= '<button type="button" value="1"  id="showmateri' . $n . '" class="btn bg-' . $color[rand(0,4)] . ' btn-flat btn-block materi">' . $n . '. ' . $sub_name . '</button>
								<div class="materi-content" value="' . $n . '" style="display: none;" id="materi' . $n . '">
									<ul class="nav nav-stacked">';
				$postID = explode(',', $child->post);
				for($i = 0;$i < count($postID);$i++){
					$data[1] = $this->db->query('SELECT `Title` FROM `nostra_posts` WHERE `id` = ?', $postID[$i]);
					$content .= '<li><a href="viewpage.php?id=' . $postID[$i] . '">' . ($i+1) . ' ' . $data[1]['Title'] . '</a></li>';
				}
				$content .= '</ul></div>';
				$n++;
			}
	
			$content .= '</div></div>
						</div>
		</div>
	</section>
  </div>';
	
		} else {
			$content = '';
		}
		return $content;
	}
}