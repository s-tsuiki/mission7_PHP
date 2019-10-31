<?php
	//下の1行は、データベースに接続する際に必須です。ファイルの場所によって、パスを適宜書き換えてください。
	require '../tools/database_connect/database_connect.php';

	//データベースへの接続
	//データベースに接続する際は、これを入れてください。
	$pdo = db_connect();
	
	  //コメント用のテーブル
 	$sql = "CREATE TABLE IF NOT EXISTS cmt_list"
 	."(" 
	."num INT AUTO_INCREMENT PRIMARY KEY,"
	."id INT NOT NULL,"
 	."user VARCHAR(50) NOT NULL,"
	."comment TEXT,"
	."filename VARCHAR(128)," 
	."genre VARCHAR(128) NOT NULL"   //ジャンルの項目を追加 
	.");"; 
	$stmt = $pdo->query($sql);

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
<!-- この部分は、お任せします。画面遷移案のようになるようにお願いします。 -->
</body>

</html>