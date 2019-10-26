<?php
	//下の1行は、データベースに接続する際に必須です。ファイルの場所によって、パスを適宜書き換えてください。
	require '../tools/database_connect/database_connect.php';
	//データベースへの接続
	$dsn='';
	$user = '';
	$password = '';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));	
	
	//テーブル作成
	$sql = "CREATE TABLE IF NOT EXISTS usr_list"
	." ("
	."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY," ."user VARCHAR(50) NOT NULL,"
	."mail VARCHAR(50) NOT NULL,"
	."password VARCHAR(128) NOT NULL,"
	."flag TINYINT(1) NOT NULL DEFAULT 1" .")ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;";
	$stmt = $pdo->query($sql);
	
	//データベースに接続する際は、これを入れてください。
	$pdo = db_connect();
	
	//データベース接続切断
	$pdo = null;
?>

<!DOCTYPE html>
<html lang = "ja">
<head>
  <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. スマホ対応にしたいので、他のページでも入れてください。 -->
  <meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
  <link rel="stylesheet" type="text/css" href="../layout/example.css"><!-- レイアウトの部分は分けて、layoutフォルダに入れてください。ファイル名は左のように、「（レイアウトを指定したいファイル名）.css」としてください。 -->
  <title>example</title>
</head>

<body>
<h1>ログイン</h1>
        <form action="mission_7_login.php" method="POST">
            <fieldset>
                <legend>ログインフォーム</legend>
                <div><font color="#ff0000">
                <label for="name">ユーザーID</label><input type="text" name="name" placeholder="ユーザーIDを入力" value="<?php if (!empty($_POST["name"])) {echo htmlspecialchars($_POST["name"], ENT_QUOTES);} ?>">
                <br>
                <label for="pass">パスワード</label><input type="pass" name="pass" value="" placeholder="パスワードを入力">
                <br>
                <input type="submit" value="ログイン">
            </fieldset>
        </form>
        <br>
        <form action="../registration/mail_registration.php">
            <fieldset>          
                <legend>新規登録フォーム</legend>
                <input type="submit" value="新規登録">
            </fieldset>
        </form>

</body>

</html>
