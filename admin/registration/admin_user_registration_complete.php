<?php
require '../../tools/database_connect/database_connect.php';
require '../../phpmailer/send_admin_complete_mail.php';
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
		//echo "不正アクセスの可能性あり";
		exit();
	}
 
	//クリックジャッキング対策
	header('X-FRAME-OPTIONS: SAMEORIGIN');
 
	//データベース接続
	$pdo = db_connect();
 
	//エラーメッセージの初期化
	$errors = array();
 
	$mail = $_SESSION['mail'];
	$user = $_SESSION['user'];
	$admin_password = $_SESSION['admin_password'];
 
	//パスワードのハッシュ化
	$admin_password_hash =  password_hash($admin_password, PASSWORD_DEFAULT);
 
	//ここでデータベースに登録する
	try{
		//トランザクション開始
		$pdo->beginTransaction();
	
		//admin_listテーブルに本登録する
		$stmt = $pdo->prepare("INSERT INTO admin_list (user,mail,password) VALUES (:user,:mail,:admin_password_hash)");
		//プレースホルダへ実際の値を設定する
		$stmt->bindValue(':user', $user, PDO::PARAM_STR);
		$stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
		$stmt->bindValue(':admin_password_hash', $admin_password_hash, PDO::PARAM_STR);
		$stmt->execute();
		
		//admin_check_listのflagを1にする
		$stmt = $pdo->prepare("UPDATE admin_check_list SET flag=1 WHERE mail=(:mail) AND user=(:user)");
		//プレースホルダへ実際の値を設定する
		$stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
		$stmt->bindValue(':user', $user, PDO::PARAM_STR);
		$stmt->execute();

		//pre_admin_listのflagを1にする
		$stmt = $pdo->prepare("UPDATE pre_admin_list SET flag=1 WHERE mail=(:mail) AND user=(:user)");
		//プレースホルダへ実際の値を設定する
		$stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
		$stmt->bindValue(':user', $user, PDO::PARAM_STR);
		$stmt->execute();
	
		// トランザクション完了（コミット）
		$pdo->commit();
		
		//データベース接続切断
		$pdo = null;
	
		//セッション変数を全て解除
		$_SESSION = array();
	
		//セッションクッキーの削除・sessionidとの関係を探れ。つまりはじめのsesssionidを名前でやる
		if (isset($_COOKIE["PHPSESSID"])) {
    			setcookie("PHPSESSID", '', time() - 1800, '/');
		}
	
 		//セッションを破棄する
 		session_destroy();
 	
 		//登録完了のメールを送信
		$admin_password_hide = str_repeat('*', strlen($admin_password));
		$message = send_admin_complete_mail($mail, $user, $admin_password_hide);
 		
		if ($message !== '送信完了！') {
 			$errors['mail_error'] = $message;
	 	}
		
	}catch (PDOException $e){
		//トランザクション取り消し（ロールバック）
		$pdo->rollBack();
		$errors['error'] = "もう一度やりなおして下さい。";
		echo 'Error:'.$e->getMessage();
	}
 
?>
 
<!DOCTYPE html>
<html lang = "ja">
<head>
  <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. ここは一旦、いじらなくてOKです。 -->
  <meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
  <link rel="stylesheet" type="text/css" href="../../layout/admin/registration/admin_user_registration_complete.css">
  <title>管理者登録完了</title>
</head>
<body>

<div class = "head_line">
<img src="../../images/logo.jpg" class="logo">

<?php if (count($errors) === 0): ?>
<p class="title">管理者登録完了</p>
 
</div>

<div class="message_area">
 
<p>登録完了いたしました。</p>
<p>下のログインボタンからログインをしてください。</p>
<p><input type="button" value="ログイン" onclick="location.href='../admin_login.php'" align="center" style = "width:100px; height: 30px"/></p>
 
<?php elseif(count($errors) > 0): ?>
</div>
<div class="message_area">

<?php
foreach($errors as $value){
	echo "<p><strong>".$value."</strong></p>";
}
?>
 
<?php endif; ?>

</div>
 
</body>
</html>