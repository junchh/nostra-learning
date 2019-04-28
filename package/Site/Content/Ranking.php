<?php 

class Ranking {
	
	private $db;
	
	public function __construct($db){
		$this->db = $db;
	}
	
	public function load(){
		$content = '';
		$query[0] = $this->db->queryFetchAll('SELECT * FROM `nostra_materi`');
		foreach($query[0] as $data[0]){
			$content .= '<div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">' . $data[0]['Name'] . '</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered table-hover">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Nama</th>
                  <th class="text-center" style="width: 70px">Kelas</th>
                  <th class="text-center" style="width: 40px">Points</th>
                </tr>';
			$query[1] = $this->db->queryFetchAll('SELECT `Name`, `Class`, `Points` FROM `nostra_users` WHERE `Team` = ? ORDER BY `Points` DESC', $data[0]['id']);
			foreach($query[1] as $data[1]){
				$content .= '<tr>
                  <td>1.</td>
                  <td><a href="#">' . $data[1]['Name'] . '</a></td>
                  <td class="text-center">' . $data[1]['Class'] . '</td>
                  <td class="text-center">' . $data[1]['Points'] . '</td>
                </tr>';
			}
			$content .= '              </table>
            </div>
          </div>
          <!-- /.box -->
          <!-- /.box -->
        </div>';
		}
		return $content;
	}
}