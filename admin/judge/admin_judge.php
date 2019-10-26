<?php
require '../../tools/database_connect/database_connect.php';
	session_start();
 
	header("Content-type: text/html; charset=utf-8");
 
	//クロスサイトリクエストフォージェリ（CSRF）対策
	$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
	$token = $_SESSION['token'];
 
	//クリックジャッキング対策
	header('X-FRAME-OPTIONS: SAMEORIGIN');
 
	//データベース接続
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
 
	//エラーメッセージの初期化
	$errors = array();
 
	if(empty($_GET)) {
		header("Location: ../admin_login.php");
		exit();
	}else{
		//GETデータを変数に入れる
		$urltoken = isset($_GET['urltoken']) ? $_GET['urltoken'] : NULL;
		//メール入力判定
		if ($urltoken == ''){
			$errors['urltoken'] = "もう一度登録をやりなおして下さい。";
		}else{
			
			//flagが0の未登録者・仮登録日から24時間以内の場合
			$stmt = $pdo->prepare("SELECT user, mail FROM admin_check_list WHERE urltoken=(:urltoken) AND flag =0 AND date > now() - interval 24 hour");
			$stmt->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
			$stmt->execute();
			
			//24時間以内に仮登録され、本登録されていないトークンの場合
			if( $stmt->rowCount() == 1){
				$result = $stmt->fetch();
				$mail = $result['mail'];
				$user = $result['user'];

				//SESSION変数の登録
				$_SESSION['mail'] = $mail;
				$_SESSION['user'] = $user;
			}else{
				$errors['urltoken_timeover1'] = "このURLはご利用できません。";
				$errors['urltoken_timeover2'] = "有効期限が過ぎた等の問題があります。";
				$errors['urltoken_timeover3'] = "もう一度登録をやりなおして下さい。";
			}
			
			//データベース接続切断
			$pdo = null;
		}
	}
 
?>
 
<!DOCTYPE html>
<html lang = "ja">
<head>
 <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. ここは一旦、いじらなくてOKです。 -->
 <meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
 <link rel="stylesheet" type="text/css" href="../../layout/user_registration.css">
 <title>新規管理者承認画面</title>
</head>

<body>
<div class = "registration_area">

<h1>おすきにどうぞ！</h1>
 
<?php if (count($errors) === 0): ?>

<h2>管理者リクエストの承認</h2>
 
<p>下記のユーザーの管理者リクエストを承認しますか？</p>
<br>

<form action="admin_judge_check.php" method="post" class = "form">
<p>ユーザー名：</p>
<p><?=htmlspecialchars($user, ENT_QUOTES, 'UTF-8')?></p>
<p>メールアドレス：</p>
<p class = "mail"><?=htmlspecialchars($mail, ENT_QUOTES, 'UTF-8')?></p><!-- text/plainをtext/htmlへ変換 -->
<br>
<input type="hidden" name="token" value="<?=$token?>">
<input type="submit" value="承認する" name="agree"class = "confirm">
<input type="submit" value="承認しない" name="disagree" class = "confirm">
</form>

 
<?php elseif(count($errors) > 0): ?>
 
<?php
foreach($errors as $value){
	echo "<p><strong>".$value."</strong></p>";
}
?>
 
<?php endif; ?>

</div>
 
</body>
</html>