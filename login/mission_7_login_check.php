<?php
require '../tools/database_connect/database_connect.php';

	session_start();
 
	header("Content-type: text/html; charset=utf-8");
 
	//cookieがオフの場合
	if(!isset($_SESSION)){
		echo "cookieを有効にしてください。";
		exit();
	}

	//POST送信されていなかった場合
	if(empty($_POST)){
		header('Location: mission_7_login.php');
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

	//POSTされたデータを変数に入れる
	$user = isset($_POST['name']) ? $_POST['name'] : NULL;
	$password = isset($_POST['pass']) ? $_POST['pass'] : NULL;

	//前後にある半角全角スペースを削除
	$user = spaceTrim($user);
	$password = spaceTrim($password);

	if(empty($user)){
		$errors['user'] = "ユーザー名が入力されていません。";
	}
	elseif(empty($password)){
		$errors['password'] = "パスワードが入力されていません。";
	}
	//入力されているとき
	else{
		//データベースへの接続
		$pdo = db_connect();

		//ユーザー名とパスワードが一致したら、マイページに飛ぶ
		$stmt = $pdo->prepare("SELECT password FROM usr_list WHERE user=(:user) AND flag =1");
		$stmt->bindValue(':user', $user, PDO::PARAM_STR);
		$stmt->execute();
		$registrated_password = $stmt->fetch();

		if(password_verify($password, $registrated_password['password'])){
			echo "ログインできました。"."<br>";
			//CSRF対策
			session_regenerate_id(true);
		}else{
			$errors['user_check'] = "ユーザー名かパスワードが誤っています。";
		}

		//データベース接続切断
		$pdo = null;
	}

	//エラーが無ければセッションに登録し、マイページに飛ぶ
	if(count($errors) === 0){
		$_SESSION['USERID'] = $user;
		$_SESSION['password'] = $password;

		//POSTデータを保持しながら、ログインページに飛ぶ
		header('Location: mypage.php', true, 307);
		exit();
	}
?>

<!DOCTYPE html>
<html lang = "ja">
<head>
  <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. ここは一旦、いじらなくてOKです。 -->
  <meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
  <link rel="stylesheet" type="text/css" href="../layout/login/mission_7_login_check.css">
  <title>ユーザー処理画面</title>
</head>
<body>
<?php if(count($errors) > 0):?>

<div class = "head_line">

<img src="../images/logo.jpg" class="logo">
<p class="title">ログインチェック</p>

</div>

<div class="message_area">
<?php
foreach($errors as $value){
	echo "<p align='center'><strong>".$value."</strong></p>";
}
?>

<input type="button" value="戻る" onClick="history.back()" style = "width:100px; height: 30px"/>

</div>

</div>

<?php endif; ?>
</body>
</html>
