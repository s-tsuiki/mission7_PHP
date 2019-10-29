<?php
require '../../phpmailer/send_admin_request_mail.php';
require '../../tools/database_connect/database_connect.php';
require '../../tools/make_url/make_url.php';

	session_start();
	
	header("Content-type: text/html; charset=utf-8");
 	
	//cookieがオフの場合
	if(!isset($_SESSION['token'])){
		echo "cookieを有効にしてください。";
		exit();
	}

	if(empty($_POST)) {
		header("Location: admin_user_authentication.php");
		exit();
	}

 	//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
 	if ($_POST['token'] != $_SESSION['token']){
		echo "不正なリクエスト";
		exit();
 	}
 	
	//クリックジャッキング対策
	header('X-FRAME-OPTIONS: SAMEORIGIN');
 	
	//データベースへの接続
	$pdo = db_connect();

	//データベースの作成
	$sql = "CREATE TABLE IF NOT EXISTS admin_check_list"
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
 	
	//SESSIONデータを変数に入れる
	$user = $_SESSION['user'];
	$password = $_SESSION['password'];
	
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

	//ユーザー名とパスワードが一致したら、メールを送る
	$stmt = $pdo->prepare("SELECT password FROM usr_list WHERE user=(:user) AND flag =1");
	$stmt->bindValue(':user', $user, PDO::PARAM_STR);
	$stmt->execute();
	$registrated_password = $stmt->fetch();

	if(password_verify($password, $registrated_password['password'])){
		//本登録用のusr_listテーブルから該当するユーザーのmailをとってくる
		$stmt = $pdo->prepare("SELECT mail FROM usr_list WHERE user=(:user) AND flag =1");
		$stmt->bindValue(':user', $user, PDO::PARAM_STR);
		$stmt->execute();

		if( $stmt->rowCount() == 1){
			$mail_array = $stmt->fetch();
			$mail = $mail_array['mail'];
		}else{
			$errors['mail_error'] = "メールアドレスが登録されていません。";
		}
	}else{
		$errors['member_check'] = "ユーザー名かパスワードが誤っています。";
	}
 
	if (count($errors) === 0){
	
		$urltoken = hash('sha256',uniqid(rand(),1));
		$url = make_url()."/mission7_PHP/admin/judge/admin_judge.php"."?urltoken=".$urltoken;
		
		//ここでデータベースに登録する
		try{
			$sql = $pdo->prepare("INSERT INTO admin_check_list (urltoken, user, mail, date) VALUES (:urltoken, :user, :mail, now() )");
		
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
		$message = send_admin_request_mail($user, $mail, $url);
		
		if ($message == '送信完了！') {
	
	 		//セッション変数を全て解除
			$_SESSION = array();
	
			//クッキーの削除
			if (isset($_COOKIE["PHPSESSID"])) {
				setcookie("PHPSESSID", '', time() - 1800, '/');
			}
	
 			//セッションを破棄する
 			session_destroy();
 	
 			$message = "<p>管理者にメールをお送りしました。</p>";
			$message = $message."<p>管理人が承認するとメールが送信されますので、メールに記載されたURLから<strong>メールの送信後24時間以内</strong>にご登録下さい。</p>";
			$message = $message."<p><strong>24時間以内に承認メールが来ない場合は、もう一度この操作を行ってください。</strong></p>";
 	
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
 <link rel="stylesheet" type="text/css" href="../../layout/admin/registration/admin_mail_check.css">
 <title>管理者メール確認</title>
</head>
<body>
<div class = "head_line">

<img src="../../images/logo.jpg" class="logo">

</div>
 
<div class = "message_area">

<?php if (count($errors) === 0): ?>
 
<?=$message?>
 
<?php elseif(count($errors) > 0): ?>
 
<?php
foreach($errors as $value){
	echo "<p><strong>".$value."</strong></p>";
}
?>
 
<a href="../admin_login.php">トップページに戻る</a>
 
<?php endif; ?>

</div>

</body>
</html>