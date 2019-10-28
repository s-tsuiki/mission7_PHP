<?php
 session_start();
 
 header("Content-type: text/html; charset=utf-8");
 
 //クロスサイトリクエストフォージェリ（CSRF）対策
 $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
 $token = $_SESSION['token'];
 
 //クリックジャッキング対策
 header('X-FRAME-OPTIONS: SAMEORIGIN');
 
?>
 
<!DOCTYPE html>
<html lang = "ja">
<head>
 <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. ここは一旦、いじらなくてOKです。 -->
 <meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
 <link rel="stylesheet" type="text/css" href="../layout/registration/mail_registration.css">
 <title>メール登録</title>
</head>
<body>

<div class = "head_line">

<img src="../images/logo.jpg" class="logo">
<p class="title">メール登録</p>

</div>
 
<div class="table">


<form action="mail_check.php" method="post" class = "form">
<table align="center">

<tr align="center"><td colspan="2">アカウントの作成には、<strong>メールアドレスの登録が必要です</strong>。</td></tr>
<tr align="center"><td colspan="2">アカウント登録に用いるメールアドレスを入力してください。</td></tr>

<tr>
<td><lavel for="mail">メールアドレス:</lavel></td>
<td><input type="email" name="mail" id = "mail" placeholder="welcome@example.com" style = "margin:30px; height: 30px; width: 300px"/></td>
</tr>
 
<input type="hidden" name="token" value="<?=$token?>">

<tr align="center">
<td colspan="2"><input type="submit" value="登録する" align="center" style = "width:100px; height: 30px"/></td>
</tr>
 
</table>
</form>
 
</div>

</body>
</html>