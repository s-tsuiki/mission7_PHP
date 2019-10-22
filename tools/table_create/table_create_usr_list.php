<?php
	//usr_listを作るツール

	require '../database_connect/database_connect.php';

	//データベースへの接続
	$pdo = db_connect();

	//データベースの作成
	$sql = "CREATE TABLE IF NOT EXISTS usr_list"
	." ("
	."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
	."user VARCHAR(50) NOT NULL,"
	."mail VARCHAR(50) NOT NULL,"
	."password VARCHAR(128) NOT NULL,"
	."flag TINYINT(1) NOT NULL DEFAULT 1"
 	.")ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;";
	
	$stmt = $pdo->query($sql);

	//データベース接続切断
	$pdo = null;
?>
