<?php
//下の1行は、データベースに接続する際に必須です。ファイルの場所によって、パスを適宜書き換えてください。
require 'tools/database_connect/database_connect.php';

	//データベースへの接続
	//データベースに接続する際は、これを入れてください。
	$pdo = db_connect();

	//テーブルの作成
	//コメント等を格納するテーブルは、画像投稿付き掲示板で作ったものをベースにお願いします。
	//コメント等を格納するテーブルの名前は、「cmt_list」でお願いします。
	
?>

<!DOCTYPE html>
<html lang = "ja">
<head>
  <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. スマホ対応にしたいので、他のページでも入れてください。 -->
  <meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
  <link rel="stylesheet" type="text/css" href="/layout/mission_7.css"><!-- レイアウトの部分は分けて、layoutフォルダに入れてください。ファイル名は左のように、「（レイアウトを指定したいファイル名）.css」としてください。 -->
  <title>おすきにどうぞ！</title>
</head>

<body>
<!-- この部分は、お任せします。画面遷移案のようになるようにお願いします。 -->
</body>

</html>