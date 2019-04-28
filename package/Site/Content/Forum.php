<?php 

class Forum {
	
	private $db;
	private $user;
	private $totalPage;
	
	public function __construct(DBManager $db, User $user){
		$this->db = $db;
		$this->user = $user;
	}
	
	public function load(){
		$data[0] = $this->db->queryFetchAll('SELECT * FROM `nostra_forum_categories`');
		$content = '';
		foreach($data[0] as $value){
			$CategoryID = $value['id'];
			$title = $value['Name'];
			$subtitle = $value['Description'];
			$data[1] = $this->db->queryFetchAll('SELECT * FROM `nostra_forum_topics` WHERE `CategoryID` = ?', $CategoryID);
			$topics = $this->db->countRow();
			$posts = 0;
			if($topics > 0){
				foreach($data[1] as $value){
					$id = $value['id'];
					$this->db->query('SELECT * FROM `nostra_forum_posts` WHERE `TopicID` = ?', $id);
					$posts += $this->db->countRow();
				}
			}
		
			$extra = $this->calcTime($value['LatestPostID']);
		
			$content .= '<tr>
				<td></td>
					<td>
					<h4><a href="topics.php?id=' . $CategoryID . '">' . $title . '</a><br><small>' . $subtitle . '</small></h4>
					</td>
					<td class="text-center hidden-xs hidden-sm"><a href="#">' . $topics . '</a></td>
					<td class="text-center hidden-xs hidden-sm"><a href="#">' . $posts . '</a></td>
					<td class="hidden-xs hidden-sm">by <a href="#">' . $extra['Name'] . '</a><br><small><i class="fa fa-clock-o"></i> ' . $extra['Time'] . '</small></td>
				</tr>';
		}
		
		return $content;
	}
	
	public function loadTopics($categoryID, $page){
		$this->db->query("SELECT `id` FROM `nostra_forum_topics` WHERE `CategoryID` = '" . $categoryID . "'");
		$this->totalPage = ceil(($this->db->countRow()) / 8);
	
		if($page > $this->totalPage){
			exit('page out of bound');
		}
	
		$start = ($page - 1) * 8;
		$data[0] = $this->db->queryFetchAll('SELECT * FROM `nostra_forum_topics` WHERE `CategoryID` = ? ORDER BY `id` DESC LIMIT ?,8', $categoryID, $start);
		$content = '';
		
		foreach($data[0] as $value){
			$title = $value['Title'];
			$data[1] = $this->db->query('SELECT * FROM `nostra_forum_posts` WHERE `TopicID` = ? AND `TopicPost` = 0', $value['id']);
			$replies = $this->db->countRow();
			$views = $value['Views'];
			$postID = $value['PostID'];
			$data[2] = $this->db->query('SELECT * FROM `nostra_forum_posts` WHERE `TopicID` = ? AND `TopicPost` = 1', $value['id']);
			$authorID = $data[2]['UserID'];
			$data[3] = $this->db->query('SELECT `Name` FROM `nostra_users` WHERE `id` = ?', $authorID);
			$author = $data[3]['Name'];
			$date = $data[2]['TimePosted'];
			
			$extra = $this->calcTime($value['LatestPostID']);
			
			$content .= '	<tr>
	<td></td>
		<td>
          <h4><a href="#">' . $title . '</a><br><small>By <a href="#">' . $author . ' . </a> on ' . $date . '</small></h4>
        </td>
        <td class="text-center hidden-xs hidden-sm"><a href="#">' . $replies . '</a></td>
        <td class="text-center hidden-xs hidden-sm"><a href="#">' . $views . '</a></td>
        <td class="hidden-xs hidden-sm">by <a href="#">' . $extra['Name'] . '</a><br><small><i class="fa fa-clock-o"></i> ' . $extra['Time'] . '</small></td>
      </tr>';
		}
		return $content;
	}
	
	public function loadPagination($categoryID, $page){
		$pagination = '';
		for($i = 1; $i <= $this->totalPage; $i++){
		if($i == $page){
			$pagination .= '<li class="active"><a href="topics.php?id=' . $categoryID . '&page=' . $i . '">' . $i . '</a></li>';
		} else {
			$pagination .= '<li><a href="topics.php?id=' . $categoryID . '&page=' . $i . '">' . $i . '</a></li>';
		}
	}
	}
	
	private function calcTime($id){
		$time = time();
		$data[0] = $this->db->query('SELECT `Time`, `TimePosted`, `UserID` FROM `nostra_forum_posts` WHERE `id` = ?', $id);
		$userid = $data[0]['UserID'];
		$posttime = $data[0]['Time'];
		$data[1] = $this->db->query('SELECT `Name` FROM `nostra_users` WHERE `id` = ?', $userid);
		$output['Name'] = $data[1]['Name'];
		$difference = $time - $posttime;
		if($difference < 18000){
			if($difference < 60){
				$output['Time'] = $difference . ' seconds ago';
			} else if($difference > 59 && $difference < 3600){
				$var = floor($difference / 60);
				$output['Time'] = $var . ' minutes ago';
				
			} else if($difference > 3599){
				$var = floor($difference / 3600);
				$output['Time'] = $var . ' hours ago';
			}
		} else {
			$output['Time'] = $data[0]['TimePosted'];
		}
		
		return $output;
	}
}