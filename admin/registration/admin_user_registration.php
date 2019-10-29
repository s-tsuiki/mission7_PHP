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
	$sql = "CREATE TABLE IF NOT EXISTS admin_list"
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
		header("Location: admin_user_authentication.php");
		exit();
	}else{
		//GETデータを変数に入れる
		$urltoken = isset($_GET['urltoken']) ? $_GET['urltoken'] : NULL;
		//メール入力判定
		if ($urltoken == ''){
			$errors['urltoken'] = "もう一度登録をやりなおして下さい。";
		}else{
			
			//flagが0の未登録者・仮登録日から24時間以内の場合
			$stmt = $pdo->prepare("SELECT mail, user FROM pre_admin_list WHERE urltoken=(:urltoken) AND flag =0 AND date > now() - interval 24 hour");
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
 <link rel="stylesheet" type="text/css" href="../../layout/admin/registration/admin_user_registration.css">
 <title>管理者登録</title>
</head>

<body>
<div class = "head_line">

<img src="../../images/logo.jpg" class="logo">
 
<?php if (count($errors) === 0): ?>

<p class="title">管理者登録</p>
 
</div>

<div class="table">
<form action="admin_user_check.php" method="post" class = "form">
<table align="center">
 
<tr>
<td>メールアドレス：</td>
<td><?=htmlspecialchars($mail, ENT_QUOTES, 'UTF-8')?></td>
</tr>

<tr>
<td><lavel for="user">ユーザー名:</lavel></td>
<td><?=htmlspecialchars($user, ENT_QUOTES, 'UTF-8')?></td>
</tr>

<tr>
<td><lavel for="admin_password">管理者用パスワード:</lavel></td>
<td><input type="password" name="admin_password" id="password" style = "margin:30px; height: 30px; width: 300px"/></td>
</tr>

<tr>
<td><lavel for="admin_password2">管理者用パスワード(確認用):</lavel></td>
<td><input type="password" name="admin_password2" id="password" style = "margin:30px; height: 30px; width: 300px"/></td>
</tr>
 
<input type="hidden" name="token" value="<?=$token?>">

<tr align="center">
<td colspan="2"><input type="submit" value="確認する" align="center" style = "width:100px; height: 30px"/></td>
</tr>

</table>
</form>

<h2 align="center">注意事項</h2>
<ul>
<li>管理者用パスワードは、<strong>半角英数字記号を合わせた10文字以上30文字以下</strong>で入力して下さい。</li>
<li>使える記号は、<strong>「!　$　%　(　)　=　|　-　@　[　,　>　_　\　?」</strong>の15個です。</li>
<li>必ず、<strong>英字、数字、記号を少なくともそれぞれ1つずつ</strong>入れてください。</li>
</ul>

</div>
 
<?php elseif(count($errors) > 0): ?>
 
</div>
<div class="table">
<?php
foreach($errors as $value){
	echo "<p align='center'><strong>".$value."</strong></p>";
}
?>
</div>
 
<?php endif; ?>
 
</body>
</html>