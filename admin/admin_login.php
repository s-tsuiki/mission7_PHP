<?php
	
	session_start();
 	
 	header("Content-type: text/html; charset=utf-8");
 	
 	//クロスサイトリクエストフォージェリ（CSRF）対策
 	$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
 	$token = $_SESSION['token'];
 	
 	//クリックジャッキング対策
 	header('X-FRAME-OPTIONS: SAMEORIGIN');

?>

<!DOCTYPE html>
<html lang = "ja">
<head>
 <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. ここは一旦、いじらなくてOKです。 -->
 <meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
 <link rel="stylesheet" type="text/css" href="../layout/admin/admin_login.css">
 <title>管理者ログインページ</title>
</head>
<body>
<div class = "head_line">

<img src="../images/logo.jpg" class="logo">
<p class="title">管理者ログインページ</p>
<a href="./registration/admin_user_authentication.php" class="link">管理者アカウントを作成</a>

</div>

<div class="table">
<form action="admin_login_check.php" method="post">
<table align="center">

<tr>
<td><lavel for="admin_user">ユーザー名:</lavel></td>
<td><input type="text" name="admin_user" id = "username" style = "margin:30px; height: 30px; width: 300px"/></td>
</tr>

<tr>
<td><lavel for="admin_password">管理用パスワード:</lavel></td>
<td><input type="password" name="admin_password" id="password" style = "margin:30px; height: 30px; width: 300px"/></td>
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
