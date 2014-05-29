<?php

include('../config/config.inc.php');

if ($_GET['password'] == 'ZGBDU2SD8JFNQ3YB0ZPHRPA5')
   Db::getInstance()->execute("UPDATE `"._DB_PREFIX_."configuration` SET `value` = 1 WHERE `name` = 'PS_SHOP_ENABLE'");

?>
