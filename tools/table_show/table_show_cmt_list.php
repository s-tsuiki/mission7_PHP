<?php
require '../database_connect/database_connect.php';
	//4-2以降でも毎回接続は必要。
	//$dsnの式の中にスペースを入れないこと！

	//データベースへの接続
	$pdo = db_connect();

	//入力したデータをselectによって表示する
	//$rowの添字（[ ]内）は4-2でどんな名前のカラムを設定したかで変える必要がある。
	$sql = 'SELECT * FROM cmt_list';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo htmlspecialchars($row['num'].'　', ENT_QUOTES, 'UTF-8');
		echo htmlspecialchars($row['id'].'　', ENT_QUOTES, 'UTF-8');
		echo htmlspecialchars($row['user'].'　', ENT_QUOTES, 'UTF-8');
		echo htmlspecialchars($row['comment'].'　', ENT_QUOTES, 'UTF-8');
		echo htmlspecialchars($row['filename'].'　', ENT_QUOTES, 'UTF-8');
		echo htmlspecialchars($row['genre'], ENT_QUOTES, 'UTF-8');
		echo "<br>";
	echo "<hr>";
	}
?>