<?php
 session_start();
 
 header("Content-type: text/html; charset=utf-8");
 
 //cookieがオフの場合
 if(!isset($_SESSION['token'])){
    echo "cookieを有効にしてください。";
    exit();
 }

 //POST送信されていなかった場合
 if(empty($_POST)){
    header('Location: admin_user_authentication.php');
    exit();
 }

 //クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
 if ($_POST['token'] != $_SESSION['token']){
    echo "不正なリクエスト";
    exit();
 }

 // ログイン状態チェック
 if (!isset($_SESSION['user']) || !isset($_SESSION['password'])) {
    header("Location: admin_user_authentication.php");
    exit();
 }
 
 //クリックジャッキング対策
 header('X-FRAME-OPTIONS: SAMEORIGIN');

 //クロスサイトリクエストフォージェリ（CSRF）対策
 $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
 $token = $_SESSION['token'];
 
?>
 
<!DOCTYPE html>
<html lang = "ja">
<head>
 <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. ここは一旦、いじらなくてOKです。 -->
 <meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
 <link rel="stylesheet" type="text/css" href="../../layout/admin/registration/admin_mail_send.css">
 <title>管理者メール送信</title>
</head>
<body>
<div class = "head_line">

<img src="../../images/logo.jpg" class="logo">
<p class="title">>管理者リクエスト要求</p>

</div>

<div class = "message_area">

<h2>ようこそ、<strong><?=htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8')?></strong>さん</h2>
<p>アカウントの作成には、<strong>管理人からの承認が必要です</strong>。</p>
<p>管理人にリクエストメールを送信しますか？</p>
<br>

<form action="../admin_logout.php" method="post" class = "form">
<input type="hidden" name="token" value="<?=$token?>">
<input type="submit" value="ログアウト" class = "back">
</form>

<form action="admin_mail_check.php" method="post" class = "form">
<input type="hidden" name="token" value="<?=$token?>">
<input type="submit" value="送信する" class = "registrate">
</form>

</div>

</body>
</html>