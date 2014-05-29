<?php 
/**

 */
class CompanydocsController extends Zend_Controller_Action
{



    public function indexAction(){
	
		//echo "shomething";	
    	//$updates = new Model_DbTable_Updatenews();
    	//var_dump($updates->listUpdate());

    
	}
	
	public function updatenewsAction(){

		$updates = new Model_DbTable_Updatenews();
		$this->view->newslist = $updates->listUpdate();
		
	}
	
	public function newsletterAction(){
		
		$docs = new Model_DbTable_Companynews();
		
		$newsletters = $docs->listNewsByType(2);
		
		$this->view->newsletters = $newsletters;
		
		$idCate = 0;
		$idCate = $this->_getParam("cate");
		
		$this->view->idCate = $idCate;
		
	}
	public function promotionsAction(){
		
		$docs = new Model_DbTable_Companynews();
		
		$promotions = $docs->listNewsByType(3);
		
		$this->view->promotions = $promotions;		
		
	}
	
	public function annoAction(){
		
		$docs = new Model_DbTable_Companynews();
		
		$annos = $docs->listNewsByType(1);
		
		$this->view->annos = $annos;		
		
	}

	public function companyDocumentAction(){
		
	}
	public function documentCategoryAction(){
		
		$docCate = new Model_DbTable_Companydocscategory();
		$this->view->categorylist = $docCate->listCategory();
		
	}
	public function addCategoryAction(){

		$docCate = new Model_DbTable_Companydocscategory();
		if($_POST){
			
			$docCate->addCategory($_POST['cate_title']);
			
			echo "Category {$_POST['cate_title']}Add";
			
			$this->_helper->redirector("document-category","companydocs");
		}
	}
	
	public function deleteCateAction(){
		
		$docCate = new Model_DbTable_Companydocscategory();
		
		if($_GET['token'] =="WAXN6BE79H4E"){
				
			$docCate->delCategory(trim($_GET['id']));
			echo "{$_GET['id']} Deleted";	

			$this->_helper->redirector("document-category","companydocs");
			}
		
	}
	
	public function uploadDocAction(){
		
		$uploadFolder = getcwd()."/docs/";
		
		$fileName1 = "";
		
		$sh = new Model_DbTable_Shoplocation();
		$mail = new Model_Emailshandler();
		$shopHeads = $sh->listHead();
		
		$docs = new Model_DbTable_Companydocuments();
		$updates = new Model_DbTable_Updatenews(); //new
		
		
		if(isset($_FILES["file_a"])){
				
			$tmpName  = $_FILES["file_a"]["tmp_name"];
			$realName = $_FILES["file_a"]["name"];
				
			if(move_uploaded_file($tmpName,$uploadFolder.$realName)){
				$fileName1 = $realName;
				$subject = trim($_POST['title_doc'])." has been Upload to our company information Page";
				$mailbody = "We have UPLOAD a new Company Document to our company informatin page , please visit the page for details.";
						$arrMailAdds = array();
			foreach($shopHeads as $shopHead){
				var_dump($shopHead);
				
				echo $shopMail = $sh->getShopMailByHead($shopHead["name_shop_location_head"]);
				echo $shopManMail = $sh->getStoreManMailByHead($shopHead["name_shop_location_head"]);
				echo $areaManMail = $sh->getAreaManMailByHead($shopHead["name_shop_location_head"]);
					
				
				if($shopMail!="" && !in_array(trim($shopMail),$arrMailAdds)){
					$mail->sendNormalEmail($shopMail, $subject, $mailbody);
					$arrMailAdds[] = $shopMail; 
				}
				if($shopManMail!="" && !in_array(trim($shopManMail),$arrMailAdds)){
					$mail->sendNormalEmail($shopManMail, $subject, $mailbody);
					$arrMailAdds[] = $shopManMail;
				}
				if($areaManMail!=""  && !in_array(trim($areaManMail),$arrMailAdds)){
					$mail->sendNormalEmail($areaManMail, $subject, $mailbody);
					$arrMailAdds[] = $areaManMail;
				}
				
			}
				
			}
			else{
		
				$errorFlag = 1;
			}
				
		}		
		$docs->addDoc($_POST['id_cate'],trim($_POST['title_doc']), $fileName1,$_POST['news_must_see']);
		
		$updates->addUpdate(6,$_POST['title_doc'],$_POST['id_cate']);
		
		$this->_helper->redirector("document-category","companydocs");
		
	}
	
	public function listdocsAction(){
		$docCate = new Model_DbTable_Companydocscategory();
		$this->view->categorylist = $docCate->listCategory();
		
	}
	
	public function deleteDocAction(){
		$token = $_GET['token'];
		$id = $_GET['id'];
		
		if($token == "WAXN6BE79H4E"){
				
			$docs = new Model_DbTable_Companydocuments();
			$docs->delDoc($id);
			echo "ID{$id} Deleted";
			$this->_helper->redirector("document-category","companydocs");
		}
		
	}
	public function uploadNewsAction(){
		
	}
	
	public function uploadResultAction(){
		$uploadFolder = getcwd()."/docs/";
		
		$fileName1 = "";
		$fileName2 = "";
		$fileName3 = "";
		$errorFlag = 0;
		
		$docs = new Model_DbTable_Companynews();
		
		if($_POST){
		//check password 
		
			if($_POST["password"]=="calico"){
				
				//handling upload file 
				if(isset($_FILES["file_a"])){
					
					$tmpName  = $_FILES["file_a"]["tmp_name"];
					$realName = $_FILES["file_a"]["name"];
					
					if(move_uploaded_file($tmpName,$uploadFolder.$realName)){
						$fileName1 = $realName;
					}
					else{
						
						$errorFlag = 1;
					}
					
				}
				
				if(isset($_FILES["file_b"])){
						
					$tmpName  = $_FILES["file_b"]["tmp_name"];
					$realName = $_FILES["file_b"]["name"];
						
					if(move_uploaded_file($tmpName,$uploadFolder.$realName)){
						$fileName1 = $realName;
					}
					else{
				
						$errorFlag = 2;
					}
						
				}				
				
				if(isset($_FILES["file_c"])){
						
					$tmpName  = $_FILES["file_c"]["tmp_name"];
					$realName = $_FILES["file_c"]["name"];
						
					if(move_uploaded_file($tmpName,$uploadFolder.$realName)){
						$fileName1 = $realName;
					}
					else{
				
						$errorFlag = 3;
					}
						
				}
				$idNewsCate = NULL;
				if($_POST['news_type']==2){
					
					$idNewsCate = $_POST['news_category'];
				}
				//$docs->addNews($newsType, $title, $content, $fileName1, $fileName2, $fileName3, $staffName, $dept)
				$docs->addNews($_POST['news_type'],$_POST['title'],$_POST['content'],$fileName1,$fileName2,$fileName3,$_POST['staff_name'],$_POST['department'],$idNewsCate,$_POST['news_must_see']);
			
			}
		
		}	
		
	}
	
	public function activeNewsAction(){
		
		$docs = new Model_DbTable_Companynews();
		
		$rowsNews = $docs->listUnActivedByType(1);
		
		$this->view->rowsnews = $rowsNews;
		
		$rowsNewsLetter = $docs->listUnActivedByType(2);
		
		$this->view->rowsnewslettr = $rowsNewsLetter;
		
		$rowsPromotion = $docs->listUnActivedByType(3);
		
		$this->view->rowspromotion = $rowsPromotion;
		
		
	}
	public function makeActiveAction(){
		$token = $_GET['token'];
		$id = $_GET['id'];	
		$updates = new Model_DbTable_Updatenews();

		$sh = new Model_DbTable_Shoplocation();
		$mail = new Model_Emailshandler();
		$shopHeads = $sh->listHead();
		
		if($token == "P3JVF4DU5EDN8S"){
				
			$docs = new Model_DbTable_Companynews();
			$docs->activeNews($id);
			echo "ID{$id} Actived";
			
			$row = $docs->getNews($id);
						
			$updates->addUpdate($row['id_news_type'],$row['title_news'],$row['id_news_cate']);	
			
			$subject = $row['title_news'];
			$mailbody = "We have updated a new Message on the company information page, please visit the page for details.";
			$arrMailAdds = array();
			foreach($shopHeads as $shopHead){
				var_dump($shopHead);
				
				echo $shopMail = $sh->getShopMailByHead($shopHead["name_shop_location_head"]);
				echo $shopManMail = $sh->getStoreManMailByHead($shopHead["name_shop_location_head"]);
				echo $areaManMail = $sh->getAreaManMailByHead($shopHead["name_shop_location_head"]);
					
				
				if($shopMail!="" && !in_array(trim($shopMail),$arrMailAdds)){
					$mail->sendNormalEmail($shopMail, $subject, $mailbody);
					$arrMailAdds[] = $shopMail; 
				}
				if($shopManMail!="" && !in_array(trim($shopManMail),$arrMailAdds)){
					$mail->sendNormalEmail($shopManMail, $subject, $mailbody);
					$arrMailAdds[] = $shopManMail;
				}
				if($areaManMail!=""  && !in_array(trim($areaManMail),$arrMailAdds)){
					$mail->sendNormalEmail($areaManMail, $subject, $mailbody);
					$arrMailAdds[] = $areaManMail;
				}
				
			}
						
		}

	}
	public function manageNewsAction(){

		$news = new Model_DbTable_Companynews();
		
		$this->view->nList1 = $news->listNewsByType(1);
		$this->view->nList2 = $news->listNewsByType(2);
		$this->view->nList3 = $news->listNewsByType(3);
		
	}
	public function deleteNewsAction(){
		
		$token = $_GET['token'];
		$id = $_GET['id'];
		
		if($token == "WAXN6BE79H4E"){
			
			$docs = new Model_DbTable_Companynews();
			$row = $docs->getNews($id);
			$newsTitle = $row['title_news'];
			$docs->deleteNews($id);
			echo "ID{$id} Deleted";	

			$updates = new Model_DbTable_Updatenews();
			$idUpdate = $updates -> getUpdateTitle($newsTitle);
			$updates->deleteUpdate($idUpdate);
		}
		
		
	}
	
	public function getPasswordAction(){
		
		$shopHead = $this->_getParam('shop');
		$token = $this->_getParam('token');
		$this->view->shophead = $shopHead;
		
		$uploadFolder = getcwd()."/docs/";
		$visitLog = "acclog.csv";
		
		if($_POST and (strlen($_POST['staff_name']) >3)){
		
		$fl = fopen($uploadFolder.$visitLog,"a");
		fwrite($fl,$_POST['ip_address'].",".$_POST['shop_code'].",".$_POST['staff_name']);
		fclose($fl);	
			
			
		$shopHeadLocation = new Model_DbTable_Shoplocation();
		$this->view->passwords = $shopHeadLocation->retrievePassword($shopHead, $token);
		
		}
		else{
			
			$this->view->passwords = "ERROR";
		}
		
	}
	
	public function newsMustSeeAction(){
		
		$idStaff = base64_decode($this->_getParam("is"));
		$this->view->is = $this->_getParam("is");
		
		//$idStaff = 1;
		if($idStaff == ""){
			
			$idStaff = 1;
		}
		//$this->view->is = base64_encode(1);
		$viewer = $this->_getParam("pv"); // Page Viwe
		
		//different rendering 
		//vs 
		//highlight  (pop up)
		
		// Full 
		
		$cNews = new Model_DbTable_Companynews();
		$cDocs = new Model_DbTable_Companydocuments();
		$newsAccLog = new Model_Message_Cdocacclog();
		
		
		$mNewsList = $cNews->listAllNeedSeeNews();
		$mDocsList = $cDocs->listAllNeedSeeDocs();
		
		$arrUnreadNewsList = array();
		$arrUnopenDocsList = array();
		
		foreach($mNewsList as $key => $v){
			
			if(!$newsAccLog->ifNewsRead($v['id'], $idStaff)){
				
				$arrUnreadNewsList[] = array($v['id'],$v['title_news']);
			}
		}
		
		foreach($mDocsList as $k2 => $v2){
			if(!$newsAccLog->ifDocOpened($v2['id_docs'], $idStaff)){
				$arrUnopenDocsList[] = array($v2['id_docs'],$v2['title']);
			}
			
		}
		$this->view->totalCount = count($arrUnopenDocsList) + count($arrUnreadNewsList);
		$this->view->arrUNews = $arrUnreadNewsList;
		$this->view->arrUDocs = $arrUnopenDocsList;
		$this->view->mNewsList = $mNewsList;
		$this->view->mDocsList = $mDocsList;
		
		
		switch($viewer){
			case('highlight'):{$this->renderScript( '/companydocs/news-must-see-hl.phtml' ); break; }
			case('full'):{$this->renderScript( '/companydocs/news-must-see-full.phtml' ); break; }
			default:{$this->renderScript( '/companydocs/news-must-see-ol.phtml' ); break; }
		}
		//var_dump($mNewsList,$mDocsList);
		
	}
	public function showNewsDetailAction(){
		$idNews = $this->_getParam("id");
		$idStaff = base64_decode($this->_getParam("is"));
		
		$this->view->idNews = $idNews;
		$this->view->idStaff = $idStaff;
		
		$cNews = new Model_DbTable_Companynews();
		$newsRow = $cNews->getNews($idNews);
		//var_dump($newsRow);
		$this->view->newsRow = $newsRow;
		if($_POST){
			
			$newsLog = new Model_Message_Cdocacclog();
			$newsLog->addCdocacclog($_POST['id_news'],0,$_POST['id_staff'],Model_DatetimeHelper::dateToday(),120);
			echo '<script language="javascript">alert("Thanks For Reading The Article,You May Close The Window Now");</script>';
			
		}
	
	}
	public function showDocDetailAction(){
		
		$idDoc = $this->_getParam("id");
		$idStaff = base64_decode($this->_getParam("is"));
		//$idStaff = 1;
		$cDocs = new Model_DbTable_Companydocuments();
		$docRow = $cDocs->getDoc($idDoc);
			$newsLog = new Model_Message_Cdocacclog();
			$newsLog->addCdocacclog(0,$docRow['id_docs'],$idStaff,Model_DatetimeHelper::dateToday(),120);
			
			$this->_redirect("/docs/".$docRow['attach_1_name']);
		
	}
	
				
}
?>