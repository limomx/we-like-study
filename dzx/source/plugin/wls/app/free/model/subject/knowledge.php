<?php
include_once dirname(__FILE__).'/../../../../libs/phpexcel/Classes/PHPExcel.php';
include_once dirname(__FILE__).'/../../../../libs/phpexcel/Classes/PHPExcel/IOFactory.php';
require_once dirname(__FILE__).'/../../../../libs/phpexcel/Classes/PHPExcel/Writer/Excel5.php';

class m_subject_knowledge extends wls implements dbtable,fileLoad{

	public $phpexcel = null;

	public function insert($data){
		$pfx = $this->c->dbprefix;
		$conn = $this->conn();

		if(!isset($data['description'])){
			$data['description'] = ' ';
		}
		$keys = array_keys($data);
		$keys = implode(",",$keys);
		$values = array_values($data);
		$values = implode("','",$values);
		$sql = "insert into ".$pfx."wls_subject_knowledge (".$keys.") values ('".$values."')";

		mysql_query($sql,$conn);
		return mysql_insert_id($conn);
	}

	public function delete($ids){
		$pfx = $this->c->dbprefix;
		$conn = $this->conn();
		$sql = "delete from ".$pfx."wls_subject_knowledge where id  in (".$ids.");";
		try{
			mysql_query($sql,$conn);
			return true;
		}catch (Exception $ex){
			return false;
		}
	}

	public function update($data){
		$pfx = $this->c->dbprefix;
		$conn = $this->conn();

		$id = $data['id'];
		unset($data['id']);
		$keys = array_keys($data);

		$sql = "update ".$pfx."wls_subject_knowledge set ";
		for($i=0;$i<count($keys);$i++){
			$sql.= $keys[$i]."='".$data[$keys[$i]]."',";
		}
		$sql = substr($sql,0,strlen($sql)-1);
		$sql .= " where id =".$id;
		try{
			mysql_query($sql,$conn);
			return true;
		}catch (Exception $ex){
			return false;
		}
	}

	public function create(){
		$conn = $this->conn();
		$pfx = $this->c->dbprefix;

		$sql = "drop table if exists ".$pfx."wls_subject_knowledge;";
		mysql_query($sql,$conn);
		$sql = "
			create table ".$pfx."wls_subject_knowledge(
				 id int primary key auto_increment	
				,id_level varchar(200) unique		
				,name varchar(200) default '' 			
				,ordering int default 0				
				
				,weight int default 0 				
				,description text 					
							
			) DEFAULT CHARSET=utf8;
			";
		mysql_query($sql,$conn);
		return true;
	}

	public function importAll($path){
		$objPHPExcel = new PHPExcel();
		$PHPReader = PHPExcel_IOFactory::createReader('Excel5');
		$PHPReader->setReadDataOnly(true);
		$this->phpexcel = $PHPReader->load($path);

		$currentSheet = $this->phpexcel->getSheetByName($this->lang['knowledge']);
		$allRow = array($currentSheet->getHighestRow());
		$allColmun = $currentSheet->getHighestColumn();

		$keys = array();
		for($i='A';$i<=$allColmun;$i++){
			if($currentSheet->getCell($i."2")->getValue()==$this->lang['id_level']){
				$keys['id_level'] = $i;
			}
			if($currentSheet->getCell($i."2")->getValue()==$this->lang['description']){
				$keys['description'] = $i;
			}
			if($currentSheet->getCell($i."2")->getValue()==$this->lang['name']){
				$keys['name'] = $i;
			}
			if($currentSheet->getCell($i."2")->getValue()==$this->lang['weight']){
				$keys['weight'] = $i;
			}
			if($currentSheet->getCell($i."2")->getValue()==$this->lang['ordering']){
				$keys['ordering'] = $i;
			}
		}

		$data = array();
		for($i=3;$i<=$allRow[0];$i++){
			$data = array(
				'id_level'=>$currentSheet->getCell($keys['id_level'].$i)->getValue(),
				'name'=>$this->t->formatTitle($currentSheet->getCell($keys['name'].$i)->getValue()),
			);
			if(isset($keys['ordering'])){
				$data['ordering'] = $currentSheet->getCell($keys['ordering'].$i)->getValue();
			}
			if(isset($keys['description'])){
				$data['description'] = $currentSheet->getCell($keys['description'].$i)->getValue();
			}
			if(isset($keys['weight'])){
				$data['weight'] = $currentSheet->getCell($keys['weight'].$i)->getValue();
			}
			$this->insert($data);
		}
	}

	public function exportAll($path=null){
		$objPHPExcel = new PHPExcel();
		$data = $this->getList(1,1000);
		$data = $data['data'];

		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle($this->lang['knowledge']);

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $this->lang['id_level']);
		$objPHPExcel->getActiveSheet()->setCellValue('B1', $this->lang['name']);
		$objPHPExcel->getActiveSheet()->setCellValue('C1', $this->lang['ordering']);
		$objPHPExcel->getActiveSheet()->setCellValue('D1', $this->lang['weight']);
		$objPHPExcel->getActiveSheet()->setCellValue('E1', $this->lang['description']);

		$index = 2;
		for($i=0;$i<count($data);$i++){
			$index ++;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$index, $data[$i]['id_level']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$index, $data[$i]['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$index, $data[$i]['ordering']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$index, $data[$i]['weight']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$index, $data[$i]['description']);
		}
		$objStyle = $objPHPExcel->getActiveSheet()->getStyle('A1');
		$objStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->duplicateStyle($objStyle, 'A1:A'.(count($data)+1));
		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		$file =  "download/".date('YmdHis').".xls";
		$objWriter->save($this->c->filePath.$file);
		return $file;
	}
	
	public function importOne($path){}
	
	public function exportOne($path=null){}

	public function getList($page=null,$pagesize=null,$search=null,$orderby=null,$columns="*"){
		$pfx = $this->c->dbprefix;
		$conn = $this->conn();

		$where = " where 1 =1  ";
		if($search!=null){
			$keys = array_keys($search);
			for($i=0;$i<count($keys);$i++){
				if($keys[$i]=='type'){
					$where .= " and type in (".$search[$keys[$i]].") ";
				}
			}
		}
		if($orderby==null)$orderby = " order by id";
		$sql = "select ".$columns." from ".$pfx."wls_subject_knowledge ".$where." ".$orderby;
		$sql .= " limit ".($pagesize*($page-1)).",".$pagesize." ";

		$res = mysql_query($sql,$conn);
		$arr = array();
		while($temp = mysql_fetch_assoc($res)){
			$arr[] = $temp;
		}

		$sql2 = "select count(*) as total from ".$pfx."wls_subject_knowledge ".$where;
		$res = mysql_query($sql2,$conn);
		$temp = mysql_fetch_assoc($res);
		$total = $temp['total'];

		return array(
			'page'=>$page,
			'data'=>$arr,
			'sql'=>$sql,
			'total'=>$total,
			'pagesize'=>$pagesize,
		);
	}

	public function getListForGroup($id_level_group){
		$pfx = $this->c->dbprefix;
		$conn = $this->conn();

		$sql = "
		SELECT *,(id_level in (
			select id_level_knowledge from ".$pfx."wls_user_group2knowledge where id_level_group = '".$id_level_group."' 
		))as checked FROM ".$pfx."wls_subject_knowledge order by id;
		";
		$res = mysql_query($sql,$conn);
		$data = array();
		while($temp = mysql_fetch_assoc($res)){
			$data[] = $temp;
		}
		return $data;
	}

	public function getListForUser($username){
		$pfx = $this->c->dbprefix;
		$conn = $this->conn();

		$sql = "
		select *,id_level in ( 
			select id_level_knowledge from wls_user_group2knowledge where id_level_group in ( 
				select id_level_group from wls_user_group2user where username = '".$username."'
			)
		) as checked from wls_subject_knowledge;";

		$res = mysql_query($sql,$conn);
		$data = array();
		while($temp = mysql_fetch_assoc($res)){
			$data[] = $temp;
		}
		return $data;
	}
}
?>