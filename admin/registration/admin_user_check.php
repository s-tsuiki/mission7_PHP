<?php
require '../../tools/database_connect/database_connect.php';

	session_start();
 
	header("Content-type: text/html; charset=utf-8");
 
	//cookieがオフの場合
	if(!isset($_SESSION['token'])){
		echo "cookieを有効にしてください。";
		exit();
	}

	if(empty($_POST)) {
		header("Location: admin_user_authentication.php");
		exit();
	}

	//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
	if ($_POST['token'] != $_SESSION['token']){
		//echo "不正アクセスの可能性あり";
		exit();
	}
 
	//クリックジャッキング対策
	header('X-FRAME-OPTIONS: SAMEORIGIN');
 
	//前後にある半角全角スペースを削除する関数
	function spaceTrim ($str) {
		// 行頭
		$str = preg_replace('/^[ 　]+/u', '', $str);
		// 末尾
		$str = preg_replace('/[ 　]+$/u', '', $str);
		return $str;
	}
 
	//エラーメッセージの初期化
	$errors = array();
 
	if(empty($_POST)) {
		header("Location: admin_user_authentication.php");
		exit();
	}else{
		//POSTされたデータを各変数に入れる
		$admin_password = isset($_POST['admin_password']) ? $_POST['admin_password'] : NULL;
		$admin_password2 = isset($_POST['admin_password2']) ? $_POST['admin_password2'] : NULL;
	
		//前後にある半角全角スペースを削除
		$admin_password = spaceTrim($admin_password);
		$admin_password2 = spaceTrim($admin_password2);
	
		//パスワード入力判定
		if ($admin_password == '' || $admin_password2 == ''):
			$errors['password'] = "パスワードが入力されていません。";
		elseif(!preg_match('/^[0-9a-zA-Z!\$%\(\)=\|\-@[,>_\\\?]{10,30}$/', $_POST["admin_password"])):
			$errors['password_length'] = "パスワードは半角英数字記号を合わせた10文字以上30文字以下で入力して下さい。";
		elseif(!preg_match('/\A(?=.*?[0-9])(?=.*?[a-zA-Z])(?=.*?[!\$%\(\)=\|\-@[,>_\\\?])[0-9a-zA-Z!\$%\(\)=\|\-@[,>_\\\?]{10,30}+\z/i', $_POST["admin_password"])):
			$errors['password_condition'] = "必ず、英字、数字、記号を少なくともそれぞれ1つずつ入れてください。";
		elseif($admin_password !== $admin_password2):
			$errors['password_match'] = "パスワードが一致しません。もう一度やり直してください。";
		else:
			//パスワードを伏せる
			$admin_password_hide = str_repeat('*', strlen($admin_password));
		endif;
	
	}
 
	//エラーが無ければセッションに登録
	if(count($errors) === 0){
		$_SESSION['admin_password'] = $admin_password;
	}
 
?>
 
<!DOCTYPE html>
<html lang = "ja">
<head>
  <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. ここは一旦、いじらなくてOKです。 -->
  <meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
  <link rel="stylesheet" type="text/css" href="../../layout/admin/registration/admin_user_check.css">
  <title>管理者登録確認</title>
</head>
<body>
<div class = "head_line">

<img src="../../images/logo.jpg" class="logo">
 
<?php if (count($errors) === 0): ?>
 
<p class="title">管理者登録確認</p>
</div>

<div class="table">
<form action="admin_user_registration_complete.php" method="post" class="form">
<table align="center">
 
<tr>
<td>メールアドレス:</td>
<td><?=htmlspecialchars($_SESSION['mail'], ENT_QUOTES, 'UTF-8')?></td>
</tr>

<tr>
<td>ユーザー名:</td>
<td><?=htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8')?></td>
</tr>

<tr>
<td>管理者パスワード:</td>
<td><?=$admin_password_hide?></td>
</tr>
 
<input type="hidden" name="token" value="<?=$_POST['token']?>">

<tr>
<td><input type="button" value="戻る" onClick="history.back()" align="center" style = "width:100px; height: 30px"/></td>
<td><input type="submit" value="登録する" align="center" style = "width:100px; height: 30px"/></td>
</tr>
 
</table>
</form>
 
<?php elseif(count($errors) > 0): ?>
 
</div>
<div class="table">
<?php
	foreach($errors as $value){
		echo "<p align='center'><strong>".$value."</strong></p>";
	}
?>
 
<div class = "back">
<input type="button" value="戻る" onClick="history.back()" style = "width:100px; height: 30px"/>
</div>
 
<?php endif; ?>

</div>
 
</body>
</html>