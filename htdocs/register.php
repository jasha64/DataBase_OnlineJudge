<?php 
require_once("./include/db_info.inc.php");
if(isset($OJ_REGISTER)&&!$OJ_REGISTER) exit(0);
require_once("./include/my_func.inc.php");
$err_str="";
$err_cnt=0;
$len;
$user_id=trim($_POST['user_id']);
$len=strlen($user_id);
$email=trim($_POST['email']);
$school=trim($_POST['school']);
$vcode=trim($_POST['vcode']);
if($OJ_VCODE&&($vcode!= $_SESSION[$OJ_NAME.'_'."vcode"]||$vcode==""||$vcode==null) ){
	$_SESSION[$OJ_NAME.'_'."vcode"]=null;
	$err_str=$err_str."Verification Code Wrong!\\n";
	$err_cnt++;
}
if($OJ_LOGIN_MOD!="hustoj"){
	$err_str=$err_str."System do not allow register.\\n";
	$err_cnt++;
}

if($len>20){
	$err_str=$err_str."User ID Too Long!\\n";
	$err_cnt++;
}else if ($len<3){
	$err_str=$err_str."User ID Too Short!\\n";
	$err_cnt++;
}
if (!is_valid_user_name($user_id)){
	$err_str=$err_str."User ID can only contain NUMBERs & LETTERs!\\n";
	$err_cnt++;
}
$nick=trim($_POST['nick']);
$len=strlen($nick);
if ($len>100){
	$err_str=$err_str."Nick Name Too Long!\\n";
	$err_cnt++;
}else if ($len==0) $nick=$user_id;
if (strcmp($_POST['password'],$_POST['rptpassword'])!=0){
	$err_str=$err_str."Password Not Same!\\n";
	$err_cnt++;
}
if (strlen($_POST['password'])<6){
	$err_cnt++;
	$err_str=$err_str."Password should be Longer than 6!\\n";
}
$len=strlen($_POST['school']);
if ($len>100){
	$err_str=$err_str."School Name Too Long!\\n";
	$err_cnt++;
}
$len=strlen($_POST['email']);
if ($len>100){
	$err_str=$err_str."Email Too Long!\\n";
	$err_cnt++;
}
if ($err_cnt>0){
	print "<script language='javascript'>\n";
	print "alert('";
	print $err_str;
	print "');\n history.go(-1);\n</script>";
	exit(0);
	
}
$password=pwGen($_POST['password']);
$sql = "select * from user where username = ?";
$result = pdo_query($sql, $user_id); //对于用户输入的用户名，先查询是否有同名用户存在
$rows_cnt=count($result);
if ($rows_cnt == 1){
	print "<script language='javascript'>\n";
	print "alert('User Existed!\\n');\n";
	print "history.go(-1);\n</script>";
	exit(0);
}
$nick=(htmlentities ($nick,ENT_QUOTES,"UTF-8"));
$school=(htmlentities ($school,ENT_QUOTES,"UTF-8"));
$email=(htmlentities ($email,ENT_QUOTES,"UTF-8"));
$ip = ($_SERVER['REMOTE_ADDR']);
if( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ){
    $REMOTE_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR'];
    $tmp_ip=explode(',',$REMOTE_ADDR);
    $ip =(htmlentities($tmp_ip[0],ENT_QUOTES,"UTF-8"));
}
$sql = "insert into user values(?, ?, 0, 0, 0, ?, ?, ?)";
$rows = pdo_query($sql, $user_id, $password, $nick, $school, $email);

if(!isset($OJ_REG_NEED_CONFIRM)||!$OJ_REG_NEED_CONFIRM){
		$_SESSION[$OJ_NAME.'_'.'user_id']=$user_id;
		$_SESSION[$OJ_NAME.'_'.'ac']=Array();
		$_SESSION[$OJ_NAME.'_'.'sub']=Array();
}
?>
<script>history.go(-2);</script>
