<?php
require '../tools/database_connect/database_connect.php';

	session_start();
 
	header("Content-type: text/html; charset=utf-8");
 
	//cookieがオフの場合
	if(!isset($_SESSION['token'])){
		echo "cookieを有効にしてください。";
		exit();
	}

	if(empty($_POST)) {
		header("Location: mail_registration.php");
		exit();
	}

	//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
	if ($_POST['token'] != $_SESSION['token']){
		//echo "不正アクセスの可能性あり";
		exit();
	}
 
	//クリックジャッキング対策
	header('X-FRAME-OPTIONS: SAMEORIGIN');
 
	//前後にある半角全角スペースを削除する関数
	function spaceTrim ($str) {
		// 行頭
		$str = preg_replace('/^[ 　]+/u', '', $str);
		// 末尾
		$str = preg_replace('/[ 　]+$/u', '', $str);
		return $str;
	}
 
	//エラーメッセージの初期化
	$errors = array();
 
	if(empty($_POST)) {
		header("Location: mail_registration.php");
		exit();
	}else{
		//POSTされたデータを各変数に入れる
		$user = isset($_POST['user']) ? $_POST['user'] : NULL;
		$password = isset($_POST['password']) ? $_POST['password'] : NULL;
		$password2 = isset($_POST['password2']) ? $_POST['password2'] : NULL;
	
		//前後にある半角全角スペースを削除
		$user = spaceTrim($user);
		$password = spaceTrim($password);
		$password2 = spaceTrim($password2);
 
		//ユーザー名入力判定
		if ($user == ''){
			$errors['user'] = "ユーザー名が入力されていません。";
		}
		elseif(mb_strlen($user)>10){
			$errors['user_length'] = "ユーザー名は10文字以内で入力して下さい。";
		}
		//ユーザー名がすでに登録されているか確認
		else{
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

			//本登録用のusr_listテーブルにすでに登録されているuserかどうかをチェックする
			$stmt = $pdo->prepare("SELECT mail FROM usr_list WHERE user=(:user) AND flag =1");
			$stmt->bindValue(':user', $user, PDO::PARAM_STR);
			$stmt->execute();

			if( $stmt->rowCount() == 1){
				$errors['user_registration'] = "ユーザー名が既に登録されています。";
			}
			
			//データベース接続切断
			$pdo = null;
		}
	
		//パスワード入力判定
		if ($password == '' || $password2 == ''):
			$errors['password'] = "パスワードが入力されていません。";
		elseif(!preg_match('/^[0-9a-zA-Z]{5,30}$/', $_POST["password"]) || !preg_match('/^[0-9a-zA-Z]{5,30}$/', $_POST["password2"])):
			$errors['password_length'] = "パスワードは半角英数字の5文字以上30文字以下で入力して下さい。";
		elseif($password !== $password2):
			$errors['password_match'] = "パスワードが一致しません。もう一度やり直してください。";
		else:
			//パスワードを伏せる
			$password_hide = str_repeat('*', strlen($password));
		endif;
	
	}
 
	//エラーが無ければセッションに登録
	if(count($errors) === 0){
		$_SESSION['user'] = $user;
		$_SESSION['password'] = $password;
	}
 
?>
 
<!DOCTYPE html>
<html lang = "ja">
<head>
  <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. ここは一旦、いじらなくてOKです。 -->
  <meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
  <link rel="stylesheet" type="text/css" href="../layout/registration/user_check.css">
  <title>ユーザー登録確認</title>
</head>
<body>
<div class = "head_line">

<img src="../images/logo.jpg" class="logo">
 
<?php if (count($errors) === 0): ?>
 
<p class="title">ユーザー登録確認</p>
</div>

<div class="table">
<form action="user_registration_complete.php" method="post" class="form">
<table align="center">
 
<tr>
<td>メールアドレス:</td>
<td><?=htmlspecialchars($_SESSION['mail'], ENT_QUOTES)?></td>
</tr>

<tr>
<td>ユーザー名:</td>
<td><?=htmlspecialchars($user, ENT_QUOTES)?></td>
</tr>

<tr>
<td>パスワード:</td>
<td><?=$password_hide?></td>
</tr>
 
<input type="hidden" name="token" value="<?=$_POST['token']?>">

<tr>
<td><input type="button" value="戻る" onClick="history.back()" align="center" style = "width:100px; height: 30px"/></td>
<td><input type="submit" value="登録する" align="center" style = "width:100px; height: 30px"/></td>
</tr>
 
</table>
</form>
 
<?php elseif(count($errors) > 0): ?>
 
</div>
<div class="table">
<?php
	foreach($errors as $value){
		echo "<p align='center'><strong>".$value."</strong></p>";
	}
?>
 
<div class = "back">
<input type="button" value="戻る" onClick="history.back()" style = "width:100px; height: 30px"/>
</div>
 
<?php endif; ?>

</div>
 
</body>
</html>