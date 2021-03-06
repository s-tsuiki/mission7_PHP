<?php
	//下の1行は、データベースに接続する際に必須です。ファイルの場所によって、パスを適宜書き換えてください。
	require '../tools/database_connect/database_connect.php';

	//データベースに接続する際は、これを入れてください。
	$pdo = db_connect();	
	
	//テーブル作成
	$sql = "CREATE TABLE IF NOT EXISTS usr_list"
	." ("
	."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY," ."user VARCHAR(50) NOT NULL,"
	."mail VARCHAR(50) NOT NULL,"
	."password VARCHAR(128) NOT NULL,"
	."flag TINYINT(1) NOT NULL DEFAULT 1" .")ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;";
	$stmt = $pdo->query($sql);
	
	//データベース接続切断
	$pdo = null;
?>

<!DOCTYPE html>
<html lang = "ja">
<head>
  <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. スマホ対応にしたいので、他のページでも入れてください。 -->
  <meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
  <link rel="stylesheet" type="text/css" href="../layout/login/mission_7_login.css"><!-- レイアウトの部分は分けて、layoutフォルダに入れてください。ファイル名は左のように、「（レイアウトを指定したいファイル名）.css」としてください。 -->
  <title>ログイン</title>
</head>

<body>
<div class = "head_line">

<img src="../images/logo.jpg" class="logo">
<p class="title">ログイン</p>
<a href="../registration/mail_registration.php" class="link">アカウントを作成</a>

</div>

<div class="table">
<form action="mission_7_login_check.php" method="post">
<table align="center">

<tr>
<td><label for="name">ユーザー名:</lavel></td>
<td><input type="text" name="name" placeholder="ユーザー名を入力" style = "margin:30px; height: 30px; width: 300px"/></td>
</tr>

<tr>
<td><label for="pass">パスワード:</label></td>
<td><input type="password" name="pass" value="" placeholder="パスワードを入力" style = "margin:30px; height: 30px; width: 300px"/></td>
</tr>
 
<input type = "hidden" name = "token" value = "<?=htmlspecialchars($token, ENT_QUOTES, 'UTF-8')?>" >

<tr align="center">
<td colspan="2"><input type="submit" value="ログイン" align="center" style = "width:100px; height: 30px"/></td>
</tr>
</table>
 
</form>

</div>

</body>

</html>
