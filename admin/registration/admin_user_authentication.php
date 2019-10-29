<?php
session_start();

	header("Content-type: text/html; charset=utf-8");
 
	//cookieがオフの場合
	if(!isset($_SESSION['token'])){
		echo "cookieを有効にしてください。";
		exit();
	}

	//クリックジャッキング対策
	header('X-FRAME-OPTIONS: SAMEORIGIN');

	//クロスサイトリクエストフォージェリ（CSRF）対策
 	$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
 	$token = $_SESSION['token'];

?>
 
<!DOCTYPE html>
<html lang = "ja">
<head>
 <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. ここは一旦、いじらなくてOKです。 -->
 <meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
 <link rel="stylesheet" type="text/css" href="../../layout/admin/registration/admin_user_authentication.css">
 <title>管理者ユーザー認証ページ</title>
</head>
<body>

<div class = "head_line">

<img src="../../images/logo.jpg" class="logo">
<p class="title">管理者ユーザー認証ページ</p>
<a href="../../registration/mail_registration.php" class="link">ユーザーアカウントを作成</a>

</div>

<div class="table">

<form action="admin_user_authentication_check.php" method="post">
<table align="center">

<tr align="center"><td colspan="2">まずは、お持ちのアカウントでログインしてください。</td></tr>
<tr>
<td><lavel for="user">ユーザー名:</lavel></td>
<td><input type="text" name="user" id = "username" style = "margin:30px; height: 30px; width: 300px"/></td>
</tr>

<tr>
<td><lavel for="password">パスワード:</lavel></td>
<td><input type="password" name="password" id="password" style = "margin:30px; height: 30px; width: 300px"/></td>
</tr>
 
<input type = "hidden" name = "token" value = <?=htmlspecialchars($token, ENT_QUOTES, 'UTF-8')?> >

<tr align="center">
<td colspan="2"><input type="submit" value="ログイン" align="center" style = "width:100px; height: 30px"/></td>
</tr>
 
</table>
</form>

</div>
</body>
</html>