<?if(session_id() == '') {session_start();}?>
<?
	$protect="PROTECT";
	define("D_PROTECT",$protect);
	define(D_PROTECT,true);
	define("ROOT_DIR",dirname(__FILE__).'/'); # /home/users/a/ameon/domains/site.ru/
?>
<?require_once(ROOT_DIR."class/config.php");?>
<?require_once(ROOT_DIR."class/db.php");?>
<?require_once(ROOT_DIR."class/functions.php");?>
<?require_once(ROOT_DIR."class/router_class.php");?>