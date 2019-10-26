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
 <link rel="stylesheet" type="text/css" href="../../layout/admin_user_authentication.css">
 <title>管理者ユーザー認証画面</title>
</head>
<body>
<div class = "login_area">

<h1>おすきにどうぞ！</h1>
<h1>管理者</h1>
<h2>ユーザー認証</h2>

<p>まずは、お持ちのアカウントでログインしてください。</p>

<form action="admin_user_authentication_check.php" method="post">
 
<p><lavel for="user">ユーザー名:</lavel></p>
<p><input type="text" name="user"></p>
<p><lavel for="password">パスワード:</lavel></p>
<p><input type="password" name="password"></p>
 
<input type = "hidden" name = "token" value = <?=htmlspecialchars($token, ENT_QUOTES, 'UTF-8')?> >
<input type="submit" value="ログイン" class = "login">
 
</form>

</div>
</body>
</html>