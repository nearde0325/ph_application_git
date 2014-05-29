<?php
class Model_DbTable_Menu extends Zend_Db_Table_Abstract {

	protected $_name = 'menu';
	protected $_adaptor = 'db_real';
	protected $_primary = 'id_menu';
	
	public function getMenu( $idMenu){
		
		$row = $this->fetchRow("`id_menu` = ". $idMenu);
		if(!$row) return false;
		return $row->toArray();		
		
		}
		
	public function addMenu( $idServer , $controller , $department , $link , $useage , $comment , $lastChange){
		
		$data = array(
			
         "id_server" =>  $idServer ,
         "controller" =>  $controller ,
         "department" =>  $department ,
         "link" =>  $link ,
         "useage" =>  $useage ,
         "comment" =>  $comment ,
         "last_change" =>  $lastChange 
	
			);
		$this->insert($data);
		
		}
		
	public function updateMenu(  $idMenu ,  $idServer , $controller , $department , $link , $useage , $comment , $lastChange){
		$data = array(
			
         "id_server" =>  $idServer ,
         "controller" =>  $controller ,
         "department" =>  $department ,
         "link" =>  $link ,
         "useage" =>  $useage ,
         "comment" =>  $comment ,
         "last_change" =>  $lastChange 
	
			);
			
		$this->update($data,"`id_menu` = ". $idMenu);
		}
		
	public function deleteMenu( $idMenu){
		
		$this->delete("`id_menu` = ". $idMenu);
		
		}	
	
	public function listAll(){
		
		$rows =$this->fetchAll("1",array("department ASC","id_menu ASC"));
		if(!$rows) return false;
		return $rows->toArray();			
		
		}	
}

?>