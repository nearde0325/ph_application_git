<?php
//namespace application\models\DbTable\Apos;

class Model_DbTable_Apos_Invoice_Products_Wlic extends Model_DbTable_Apos_Invprods {

	protected $_name = 'dbo.CI_DATA_WLIC';
	protected $_adaptor = 'db_oldapos';
	protected $_primary = 'INV_NO';
	public static $_tbNames = 'CI_DATA_WLIC';
}

?>