<?php
	//pre_usr_listを作るツール

	require '../database_connect/database_connect.php';

	//データベースへの接続
	$pdo = db_connect();

	//データベースの作成
	$sql = "CREATE TABLE IF NOT EXISTS pre_usr_list"
	." ("
	."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
	."urltoken VARCHAR(128) NOT NULL,"
	."mail VARCHAR(50) NOT NULL,"
	."date DATETIME NOT NULL,"
	."flag TINYINT(1) NOT NULL DEFAULT 0"
 	.")ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;";
	
	$stmt = $pdo->query($sql);

	//データベース接続切断
	$pdo = null;
?>
