<?php
//データベース接続
require '../tools/database_connect/database_connect.php';
$pdo = db_connect();
  //コメント用のテーブル
 $sql = "CREATE TABLE IF NOT EXISTS cmt_list"
 	."(" 
	."num INT AUTO_INCREMENT PRIMARY KEY,"
	."id INT NOT NULL,"
 	."user VARCHAR(50) NOT NULL,"
	."comment TEXT,"
	."filename VARCHAR(128)," 
	."genre VARCHAR(128) NOT NULL"   //ジャンルの項目を追加 
	.");"; 
	$stmt = $pdo->query($sql);

	session_start();

	header("Content-type: text/html; charset=utf-8");
 
	//cookieがオフの場合
	if(!isset($_SESSION)){
		echo "cookieを有効にしてください。";
		exit();
	}

	//POST送信されていなかった場合
	if(empty($_POST['token'])){
		header("Location: mission_7_login.php");
		exit();
	}

	//クリックジャッキング対策
	header('X-FRAME-OPTIONS: SAMEORIGIN');

	// ログイン状態チェック
	if (!isset($_SESSION['user']) || !isset($_SESSION['password'])) {
    		header("Location: mission_7_login.php");
    		exit();
	}
  
	//クロスサイトリクエストフォージェリ（CSRF）対策
	$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
	$token = $_SESSION['token'];
?>

<?php
	//投稿ボタンを押したとき
	$user = $_SESSION['user'];
	$case = 1;

	//<br>を改行コードに変換
	function br2nl($string) {
		return preg_replace('/<br[[:space:]]*\/?[[:space:]]*>/i', "", $string);
	}

	//編集ボタンを押したとき
	if(isset($_POST['edit_number'])){
		$case = 2;
		if(!empty($_POST['edit_number'])){
			//編集番号を格納
			$num = $_POST['edit_number'];
			//$password = $_POST['edit_password'];
			
			if($num > 0){
				//$_FILESの初期化
				$_FILES = array();

				//データベースへの接続
				$pdo = db_connect();
				
				$sql = 'SELECT * FROM cmt_list';
				$stmt = $pdo->query($sql);
				$results = $stmt->fetchAll();
				//編集対象番号のコメントを取得する
				foreach($results as $row){
					if($row['num'] === $num){
						//編集番号をセット
						$e_number = $row['num'];
						//名前とコメントを取得
						$user = $row['user'];
						$comment = $row['comment'];
						$comment = br2nl($comment);	//<br>を改行コードに変換
						$filename = $row['filename'];
						$genre = $row['genre'];
						//$is_correct = 1;
					}/*elseif($row['id'] === $id){
						$is_correct = 2;
					}*/
				}
				
				//データベース接続切断
				$pdo = null;
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
<title><?=htmlspecialchars($user, ENT_QUOTES, 'UTF-8')?>さんの投稿・編集ページ</title>
<meta charset="utf-8" meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimumscale=1.0, maximum-scale=2.0, user-scalable=yes">
<!-- for smartphone. スマホ対応にしたいので、 他のページでも入れてください。 -->
<link rel="stylesheet" type="text/css" href="../layout/login/toukou.css">
</head>

<body>

<div class = "head_line">

<img src="../images/logo.jpg" class="logo">
<p class="title">投稿・編集フォーム</p>

</div>

<div class="table">

<p>※アップロードする画像や動画の容量は、2MBまでです。</p>
<p>※画像はjpeg方式，png方式，gif方式に対応しています。動画はmp4方式のみ対応しています。</p>

<form method = "post" action = "mypage.php" enctype="multipart/form-data">
<table align="center">

 <input type = "hidden" name = "user" value = <?php if(!empty($user)){echo "'$user'";}?> >

 <?php 
	if(!empty($filename)){
		$ext = strtolower(pathinfo($filename,PATHINFO_EXTENSION));
		$imgext = ['jpg','png','jpeg','gif'];
		if(in_array($ext,$imgext)){
			echo "<div class='pic' id='pic'><img src='../upload/".$filename."'></div>";
    		}else{
			echo "<div class='pic' id='pic'><video width='440px' src='../upload/".$filename."' controls></video></div>";
		}
		//echo "<img src='../upload/".$filename."'>"."<br>";
	}
 ?>
 <tr>
   <td><lavel for="upfile">画像や動画:</lavel></td>
   <td><input type='file' name='upfile' style = 'margin: 30px; height: 30px; width: 300px'></td>
 </tr>

 <tr>
   <td><lavel for="genre">ジャンル：</lavel></td>
   <td>
   <select name="genre" id="genre">
     <option value="食べ物">食べ物</option>
     <option value="芸能人">芸能人</option>
     <option value="ネット有名人">ネット有名人</option>
     <option value="アニメ" >アニメ</option>
     <option value="映画">映画</option>
     <option value="音楽">音楽</option>
     <option value="舞台">舞台</option>
     <option value="スポーツ">スポーツ</option>
     <option value="機械">機械</option>
     <option value="その他">その他</option>
   </select>
   </td>
 </tr>

   <script type="text/javascript">
     var genre = <?php if(!empty($genre)){echo $genre;}?>;
     document.write(genre);
     if(!genre){
       document.getElementById('genre').value = genre;
     }
   </script>

 <tr>
   <td><lavel for="comment">コメント:</lavel></td>
   <td><textarea name="comment" cols="40" rows="5"><?php if(!empty($comment)){echo htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');}?></textarea></td>
 </tr>

 <tr align="center">
   <td colspan="2"><input type = "submit" value = "送信" style = "width:100px; height: 30px"/></td>
 </tr>

 <input type = "hidden" name = "e_number" value = <?php if(!empty($e_number)){echo $e_number;}?> >
 <input type = "hidden" name = "token" value = <?=htmlspecialchars($token, ENT_QUOTES, 'UTF-8')?> >
</table>
</form>

<br>

<form method = "post" action = "mypage.php">
<table align="center">
<input type = "hidden" name = "token" value = <?=htmlspecialchars($token, ENT_QUOTES, 'UTF-8')?> >
<tr align="center">
<td colspan="2"><input type="submit" value="戻る" style = "width:100px; height: 30px"/></td>
</tr>
</table>
</form>

</div>

<br>
</body>
</hrml>