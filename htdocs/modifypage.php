<?php	$cache_time=10;
	$OJ_CACHE_SHARE=false;
	require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	$view_title= "Welcome To Online Judge";
	if (!isset($_SESSION[$OJ_NAME.'_'.'user_id'])){
		$view_errors= "<a href=./loginpage.php>$MSG_Login</a>";
		
		require("template/".$OJ_TEMPLATE."/error.php");
		exit(0);
	}

$sql = "select school, nick, email from user where username = ?";
$result = pdo_query($sql, $_SESSION[$OJ_NAME.'_'.'user_id']); //查询用户现在的资料
 $row=$result[0];


/////////////////////////Template
require("template/".$OJ_TEMPLATE."/modifypage.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>

