<?php 
include_once dirname(__FILE__).'/../../model/knowledge/log.php';

class knowledge_log extends wls{
	private $m = null;
	
	function knowledge_log(){
		
		$this->m = new m_knowledge_log();		 
	}
	
	/**
	 * Get Single user's knowledge mastery
	 * For client-side's Am Chart
	 * */
	public function getMyRaderSetting(){
		$id = '10';
		if(isset($_REQUEST['id']) && $_REQUEST['id']!=''){
			$id = $_REQUEST['id'];
		}
		$data2 = $this->m->getMyRecentAboutOneSubject($id);
		header('Content-Type:text/xml'); 		
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<settings> 
	<type>stacked</type> 
	<radar>
		<x>40%</x>                                                   
		<y>40%</y>	
		<grow_time>2</grow_time>                                  
		<sequenced_grow>true</sequenced_grow>                    
	</radar>
  <background>
    <color>#EEEEEE</color>
    <alpha>100</alpha>
    <border_color>#999999</border_color>
    <border_alpha>90</border_alpha>
  </background>	
 <legend>
 <enabled>false</enabled>
   </legend>  
  <values>
  	<max>100</max>
  </values>
	<graphs>
		<graph gid="1">                                         
			<title>'.$this->lang['statistic'].'</title>                          
			<color>#999999</color>                                                            
			<fill_alpha>50</fill_alpha>                             
     	</graph>
     </graphs>   
	<data>
		<chart>
			<axes>';
		for($i=0;$i<count($data2);$i++){
			$xml .= '<axis xid="'.$i.'">'.$data2[$i]['name'].'</axis>';
		}
		
		$xml.='		
			</axes>
			<graphs>
				<graph gid="1"> ';
		for($i=0;$i<count($data2);$i++){
			$xml .= '<value xid="'.$i.'">'.floor(($data2[$i]['count_right']*100)/($data2[$i]['count_right'] + $data2[$i]['count_wrong'])).'</value>';
		}	
		$xml.='
				</graph>				          		
			</graphs>
		</chart>
	</data>  
</settings> 	    
';
		echo $xml;
	}
}
?>