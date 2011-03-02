<?php
/**
 * Some functions which are really usefull,
 * but has nothing to do with system's logic ,
 * and it's hard to decide which controller should it be.....
 * So , I just put them here.
 * */
class tools {

	/**
	 * It's setted in wls.php
	 * */
	public $lang = null;

	/**
	 * Based on the secconds count, get a better time reponse,
	 *
	 * @param $sec int
	 * @return $str
	 * */
	public function getTimer($sec){
		$days = 0;
		$hours = 0;
		$minutes = 0;
		$days = floor($sec / (24*3600));
		$sec = $sec % (24*3600);
		$hours = floor($sec / 3600);
		$remainSeconds = $sec % 3600;
		$minutes = floor($remainSeconds / 60);
		$seconds = intval($sec - $hours * 3600 - $minutes * 60);
		$str = '';
		if($days!=0)$str .= "&nbsp;".$days.$this->lang['day'];
		if($hours!=0)$str .= "&nbsp;".$hours.$this->lang['hour'];
		if($minutes!=0)$str .= "&nbsp;".$minutes.$this->lang['minute'];
		if($seconds!=0)$str .= "&nbsp;".$seconds.$this->lang['second'];
		return $str;
	}

	/**
	 * Get the time gap between two times.
	 *
	 * @param $t1 The time want to do the math
	 * @param $t2 If it's null , it will be the current time
	 * @return String
	 * */
	public function getTimeDif($t1,$t2=null){
		$d1=strtotime($t1);
		$d2 = null;
		if($t2==null){
			$d2=strtotime("now");
		}

		$dif = ($d2-$d1);
		if($dif>2592000){
			return round($dif/30/3600/24).$this->lang['month'];
		}
		if($dif>86400){
			return round($dif/3600/24).$this->lang['day'];
		}
		if($dif>3600){
			return round($dif/3600).$this->lang['hour'];
		}
		if($dif>60){
			return round($dif/60).$this->lang['minute'];
		}
		return $dif.$this->lang['second'];
	}

	/**
	 * Cut a long string into  short string , and add 3 '.' at bottom
	 * Like:
	 *   A very long titile that can't display in a narrow div
	 * Change into:
	 *   A very long ...
	 *
	 * @param $title A long string
	 * @param $num Cut into this num's length
	 * @return $title The short string
	 * */
	public function split_title($title,$num){
		$str=strlen($title);
		if($str> $num){
			$title=mb_substr($title,0,$num,'UTF-8');
			$title=$title. "... ";
		}
		return   $title;
	}

	/**
	 * Check if this string is all Chinese or not
	 *
	 * @param $str The Chinse String
	 * @return boolen true or false
	 * */
	public function isgb($str){
		if (strlen($str)>=2){
			$str=strtok($str,"");
			if ((ord($str[0])<161) || (ord($str[0])>247)){
				return false;
			}else{
				if ((ord($str[1])<161)||(ord($str[1])>254)){
					return false;
				}else{
					return true;
				}
			}
		}else{
			return false;
		}
	}

	/**
	 * Handle the Key-Value relationship
	 * If $bool is true ,then will flip the key-value
	 * Poor English En? Haha ,If you can't handle these comments , just email me : wei1224hf@gmal.com
	 *
	 * @param key
	 * @param bool If true ,flip the key-value
	 * */
	public function formatMarkingMethod($key,$bool=false){
		$config = array(
			'0'=>$this->lang['markingByAuto'],
			'1'=>$this->lang['markingByTeacher'],
			'2'=>$this->lang['markingByUser'],
			'3'=>$this->lang['markingByMultiUser'],
		);

		if($bool){
			$config = array_flip($config);
		}
		if($key==''){
			if($bool){
				return $this->lang['markingByAuto'];
			}else{
				return 0;
			}
		}
		return $config[$key];
	}

	public function formatQuesType($key,$bool=false){
		$config = array(
			'1'=>$this->lang['Qes_Choice'],
			'2'=>$this->lang['Qes_MultiChoice'],
			'3'=>$this->lang['Qes_Check'],
			'4'=>$this->lang['Qes_Depict'],
			'5'=>$this->lang['Qes_Big'],
			'6'=>$this->lang['Qes_Mixed'],
			'7'=>$this->lang['Qes_Blank']
		);

		if($bool){
			$config = array_flip($config);
		}

		return $config[$key];
	}

	public function formatLayout($key,$bool=false){
		$config = array(
			'1'=>$this->lang['vertical'],
			'0'=>$this->lang['horizonal'],
		);

		if($bool){
			$config = array_flip($config);
		}

		return $config[$key];
	}

	public function formatApplicationType($key,$bool=false){
		$config = array(
			'0'=>$this->lang['Quiz_Paper'],
			'1'=>$this->lang['Quiz_Random'],
			'2'=>$this->lang['Quiz_QuesType'],
			'3'=>$this->lang['Quiz_Knowledge'],
			'4'=>$this->lang['Quiz_OnlineExam'],
			'5'=>$this->lang['Quiz_Wrongs'],
		);

		if($bool){
			$config = array_flip($config);
		}

		return $config[$key];
	}

	/**
	 * When import some data from .html files , have to handle some html tags
	 *
	 * @param title
	 * @param bool If true,< br/ > to \n
	 * */
	public function formatTitle($title,$bool=false){
		if($bool){
			$title = str_replace("&acute;","'",$title);
			$title = str_replace('&quot;','"',$title);
			$title = str_replace('&nbsp;',' ',$title);
			$title = str_replace('<br>',"\n",$title);
			$title = str_replace('<br/>',"\n",$title);
			$title = str_replace("<input width=\"100\" class=\"w_blank\" index=\"","[___",$title);
			$title = str_replace("\"/>","___]",$title);
			return $title;
		}else{
			$title = str_replace("<img src=\"","TEMP1",$title);
			$title = str_replace(".gif\" />","TEMP2",$title);
			$title = str_replace(".png\">","TEMP2",$title);
			$title = str_replace(".gif\">","TEMP2",$title);

			$title = str_replace("'","&acute;",$title);
			$title = str_replace('"','&quot;',$title);
			$title = str_replace("\n","<br/>&nbsp;&nbsp;",$title);

			$title = str_replace("TEMP1","<img src=\"",$title);
			$title = str_replace("TEMP2",".gif\" />",$title);
			$title = str_replace("=","&#61;",$title);

			$title = str_replace("[___","<input width=\"100\" class=\"w_blank\" index=\"",$title);
			$title = str_replace("___]","\"/>",$title);
			$title = trim($title);
			return $title;
		}
	}

	//Store all the qWikiOffice-Menu here,it's used in the function below
	public $desktopMenu = array();

	/**
	 * To fit the qWikiOffice's menu rule.
	 * It's recursion
	 * */
	public function treeMenuToDesktopMenu($id=null,$data_all){
		if($id==null){
			$len = 2;
			//Level 1
			for($i=0;$i<count($data_all);$i++){
				if(strlen($data_all[$i]['id_level'])==$len){
					//It contains submenus
					if(isset($data_all[$i]['children'])){
						$data_all[$i]['menupath'] = '/';
						for($i2=0;$i2<count($data_all[$i]['children']);$i2++){
							$data_all[$i]['children'][$i2]['menupath'] = $data_all[$i]['menupath'].$data_all[$i]['text']."/";
						}
						//Get the submenus
						$this->treeMenuToDesktopMenu($data_all[$i]['id_level'],$data_all[$i]['children']);

						$data_all[$i]['startmenu'] = $data_all[$i]['menupath'].$data_all[$i]['text']."/";
						unset($data_all[$i]['children']);
						unset($data_all[$i]['menupath']);
						//Add this menu to qWikiOffice-Menu
						$this->desktopMenu[] = $data_all[$i];
					}else{//It's the root menu
						$data_all[$i]['startmenu'] = "/";
						unset($data_all[$i]['menupath']);
						$this->desktopMenu[] = $data_all[$i];
					}
				}
			}
		}else{
			$len = strlen($id)+2;
			for($i=0;$i<count($data_all);$i++){
				if(!isset($data_all[$i]['id_level'])){

				}else{
					if(strlen($data_all[$i]['id_level'])==$len){
						//If it contains submenus
						if(isset($data_all[$i]['children'])){
							for($i2=0;$i2<count($data_all[$i]['children']);$i2++){
								//Add all the submenus to the current menuitem
								$data_all[$i]['children'][$i2]['menupath'] =  $data_all[$i]['menupath'].$data_all[$i]['text']."/";
							}
							$this->treeMenuToDesktopMenu($data_all[$i]['id_level'],$data_all[$i]['children']);

							$data_all[$i]['startmenu'] = $data_all[$i]['menupath'].$data_all[$i]['text']."/";
							unset($data_all[$i]['children']);
							unset($data_all[$i]['menupath']);
							if($data_all[$i]['type']!='subject'){
								$this->desktopMenu[] = $data_all[$i];
							}
						}else{
							$data_all[$i]['startmenu'] = $data_all[$i]['menupath'];
							unset($data_all[$i]['menupath']);
							$this->desktopMenu[] = $data_all[$i];
						}
					}
				}
			}
		}
	}

	/**
	 * Format an array have id_level attribues into a tree structure
	 * It's recursion
	 *
	 * @param $id The root id_level
	 * @param $data_all
	 * @return $data
	 * */
	public function getTreeData($id=null,$data_all,$addRootToSub=true){
		if($id==null){
			//Level 1
			$len = 2;
			$data = array();
			for($i=0;$i<count($data_all);$i++){
				//If this item is level 1, check by it's id_level's length
				if(strlen($data_all[$i]['id_level'])==$len){
					//Get this item's sub level tree
					$data_all[$i]['children'] = $this->getTreeData($data_all[$i]['id_level'],$data_all);
					//If this item has no sub level tree,set the attribute 'leaf=true'
					//Or ,set the attribute 'expanded=true'
					if(count($data_all[$i]['children'])==0){
						unset($data_all[$i]['children']);
						$data_all[$i]['leaf'] = true;
					}else{
						$data_all[$i]['expanded'] = true;
					}
						
					//Modify the item's each attribute,delete the useless stuff
					if(isset($data_all[$i]['name'])){
						$data_all[$i]['text'] = $data_all[$i]['name'];
						unset($data_all[$i]['name']);
					}
					if(isset($data_all[$i]['money']))unset($data_all[$i]['money']);
					if(isset($data_all[$i]['id']))unset($data_all[$i]['id']);
					if($data_all[$i]['checked']==0||$data_all[$i]['checked']=='false'){
						$data_all[$i]['checked'] = false;
					}else{
						$data_all[$i]['checked'] = true;
					}
						
					$data[] = $data_all[$i];
				}
			}

			return $data;
		}else{
			$data = array();
			$len = strlen($id)+2;
			for($i=0;$i<count($data_all);$i++){
				if(strlen($data_all[$i]['id_level'])==$len && substr($data_all[$i]['id_level'],0,$len-2)==$id){
					$data_all[$i]['children'] = $this->getTreeData($data_all[$i]['id_level'],$data_all);
					if(count($data_all[$i]['children'])==0){
						unset($data_all[$i]['children']);
						$data_all[$i]['leaf'] = true;
					}else{
						$data_all[$i]['expanded'] = true;
					}
					$data_all[$i]['text'] = $data_all[$i]['name'];
					unset($data_all[$i]['name']);
					if(isset($data_all[$i]['money']))unset($data_all[$i]['money']);
					if(isset($data_all[$i]['id']))unset($data_all[$i]['id']);
					if($data_all[$i]['checked']==0){
						$data_all[$i]['checked'] = false;
					}else{
						$data_all[$i]['checked'] = true;
					}
					$data[] = $data_all[$i];
				}
			}

			return $data;
		}
	}

	/**
	 * The system request phpExcel
	 * And the Excel have the datetime problem to handle.
	 *
	 * @param $date Excel Date
	 * @param $time if true , return Y-m-d H:i:s
	 * @return $date
	 * */
	public function excelTime($date, $time=false){
		if(is_numeric($date)){
			$jd = GregorianToJD(1, 1, 1970);
			$gregorian = JDToGregorian($jd+intval($date)-25569);
			$date = explode('/',$gregorian);
			$date_str = str_pad($date[2],4,'0', STR_PAD_LEFT)
			."-".str_pad($date[0],2,'0', STR_PAD_LEFT)
			."-".str_pad($date[1],2,'0', STR_PAD_LEFT)
			.($time?" 00:00:00":'');
			return $date_str;
		}
		return $date;
	}

	public function listFile($dir,$withFollder=false){
		$fileArray = array();
		$cFileNameArray = array();
		if($handle = opendir($dir)){
			while(($file = readdir($handle)) !== false){
				if($file !="." && $file !=".."){
					if(is_dir($dir."\\".$file) && $withFollder){
						$cFileNameArray = $this->listFile($dir."\\".$file);
						for($i=0;$i<count($cFileNameArray);$i++){
							$fileArray[] = $cFileNameArray[$i];
						}
					}else{
						$fileArray[] = $file;
					}
				}
			}
			return $fileArray;
		}else{
			echo "111";
		}
	}

	public function getAllFiles($filedir) {
		$allfiles = array(); //文件名数组
		$tempArr = array(); //临时文件名数组
		if (is_dir($filedir)) {//判断要遍历的是否是目录
			if ($dh = opendir($filedir)) {//打开目录并赋值一个目录句柄(directory handle)
				while (FALSE !== ($filestring = readdir($dh))) {//读取目录中的文件名
					if ($filestring != '.' && $filestring != '..') {//如果不是.和..(每个目录下都默认有.和..)
						if (is_dir($filedir . $filestring)) {//该文件名是一个目录时
							$tempArr = $this->getAllFiles($filedir . $filestring . '/');//继续遍历该子目录
							$allfiles = array_merge($allfiles, $tempArr); //把临时文件名和临时文件名组合
						} else if (is_file($filedir . $filestring)) {
							$allfiles[] = $filedir . $filestring; //如果该文件名是一个文件不是目录,直接赋值给文件名数组
						}
					}
				}
			} else {//打开目录失败
				exit('Open the directory failed');
			}
			closedir($dh);//关闭目录句柄
			return $allfiles;//返回文件名数组
		} else {//目录不存在
			exit('The directory is not exist');
		}

	}

	/**
	 * Check a folder or a file is writeable or not
	 *
	 * @return boolen
	 * */
	public function iswriteable($file){
		if(is_dir($file)){
			$dir=$file;
			if($fp = @fopen("$dir/test.txt", 'w')){
				@fclose($fp);
				@unlink("$dir/test.txt");
				$writeable = 1;
			}else{
				$writeable = 0;
			}
		}else{
			if($fp = @fopen($file, 'a+')){
				@fclose($fp);
				$writeable = 1;
			}else{
				$writeable = 0;
			}
		}
		return $writeable;
	}
}
?>