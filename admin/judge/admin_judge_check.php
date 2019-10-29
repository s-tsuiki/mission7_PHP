<?php
require '../../phpmailer/send_admin_test_mail.php';
require '../../tools/database_connect/database_connect.php';
require '../../tools/make_url/make_url.php';


	session_start();
	
	header("Content-type: text/html; charset=utf-8");
 	
	//cookieがオフの場合
	if(!isset($_SESSION['token'])){
		echo "cookieを有効にしてください。";
		exit();
	}elseif(!isset($_SESSION['mail']) || !isset($_SESSION['user'])){
		header("Location: ../admin_login.php");
		exit();
	}else {
		//変数の登録
		$mail = $_SESSION['mail'];
		$user = $_SESSION['user'];
	}

	if(empty($_POST)) {
		header("Location: ../admin_login.php");
		exit();
	}elseif(!empty($_POST['disagree'])) {
		//「承認しない」を押した場合
		$errors['judge_error'] = htmlspecialchars($user, ENT_QUOTES, 'UTF-8')."さんを承認しませんでした。";
		
	}

 	//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
 	if ($_POST['token'] != $_SESSION['token']){
		echo "不正なリクエスト";
		exit();
 	}
 	
	//クリックジャッキング対策
	header('X-FRAME-OPTIONS: SAMEORIGIN');
 	
	//「承認する」を押した場合
	if(!empty($_POST['agree'])) {
		//データベースへの接続
		$pdo = db_connect();

		//データベースの作成
		$sql = "CREATE TABLE IF NOT EXISTS pre_admin_list"
		." ("
		."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
		."urltoken VARCHAR(128) NOT NULL,"
		."user VARCHAR(50) NOT NULL,"
		."mail VARCHAR(50) NOT NULL,"
		."date DATETIME NOT NULL,"
		."flag TINYINT(1) NOT NULL DEFAULT 0"
 		.")ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;";
		
		$stmt = $pdo->query($sql);
 
		//エラーメッセージの初期化
		$errors = array();
		
		//データベースの作成
		$sql = "CREATE TABLE IF NOT EXISTS admin_list"
		." ("
		."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
		."user VARCHAR(50) NOT NULL,"
		."mail VARCHAR(50) NOT NULL,"
		."password VARCHAR(128) NOT NULL,"
		."flag TINYINT(1) NOT NULL DEFAULT 1"
 		.")ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;";
	
		$stmt = $pdo->query($sql);
				
		//本登録用のadmin_listテーブルにすでに登録されているmailかどうかをチェックする
		$stmt = $pdo->prepare("SELECT mail FROM admin_list WHERE mail=(:mail) AND flag =1");
		$stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
		$stmt->execute();

		if( $stmt->rowCount() == 1){
			$errors['mail_check'] = "このメールアドレスはすでに利用されています。";
		}

		//本登録用のadmin_listテーブルにすでに登録されているuserかどうかをチェックする
		$stmt = $pdo->prepare("SELECT user FROM admin_list WHERE user=(:user) AND flag =1");
		$stmt->bindValue(':user', $user, PDO::PARAM_STR);
		$stmt->execute();

		if( $stmt->rowCount() == 1){
			$errors['user_check'] = "このユーザー名はすでに利用されています。";
		}
	}
 
	if (count($errors) === 0){
	
		$urltoken = hash('sha256',uniqid(rand(),1));
		$url = make_url()."/mission7_PHP/admin/registration/admin_user_registration.php"."?urltoken=".$urltoken;
		
		//ここでデータベースに登録する
		try{
			$sql = $pdo->prepare("INSERT INTO pre_admin_list (urltoken, user, mail, date) VALUES (:urltoken, :user, :mail, now() )");
		
			$sql->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
			$sql->bindValue(':user', $user, PDO::PARAM_STR);
			$sql->bindValue(':mail', $mail, PDO::PARAM_STR);
			$sql->execute();
		
			//データベース接続切断
			$pdo = null;
		
		}catch (PDOException $e){
			echo 'Error:'.$e->getMessage();
			die();
		}

		//メール送信
		$message = send_admin_test_mail($mail, $user, $url);
		
		if ($message == '送信完了！') {
	
	 		//セッション変数を全て解除
			$_SESSION = array();
	
			//クッキーの削除
			if (isset($_COOKIE["PHPSESSID"])) {
				setcookie("PHPSESSID", '', time() - 1800, '/');
			}
	
 			//セッションを破棄する
 			session_destroy();
 	
 			$message = "<p>".htmlspecialchars($user, ENT_QUOTES, 'UTF-8')."さんにメールをお送りしました。</p>";
 	
	 	} else {
			$errors['mail_error'] = $message;
		}	
	}
 
?>
 
<!DOCTYPE html>
<html lang = "ja">
<head>
 <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. ここは一旦、いじらなくてOKです。 -->
 <meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
 <link rel="stylesheet" type="text/css" href="../../layout/admin/judge/admin_judge_check.css">
 <title>管理者承認結果</title>
</head>
<body>
<div class = "head_line">

<img src="../../images/logo.jpg" class="logo">
 
<?php if (count($errors) === 0): ?>
<p class="title">承認完了</p>
</div>
 
<div class = "message_area">
<?=$message?>
 
<?php elseif(count($errors) > 0): ?>
 
<div class = "message_area">
<?php
foreach($errors as $value){
	echo "<p align='center'><strong>".$value."</strong></p>";
}
?>
 
<input type="button" value="戻る" onClick="history.back()"  style = "width:100px; height: 30px"/>
 
<?php endif; ?>
 
</div>
</body>
</html>