<?php
require '../tools/database_connect/database_connect.php';

	session_start();

	header("Content-type: text/html; charset=utf-8");
 
	//cookieがオフの場合
	if(!isset($_SESSION['token'])){
		echo "cookieを有効にしてください。";
		exit();
	}

	//POST送信されていなかった場合
	if(empty($_POST['token'])){
		header("Location: admin_login.php");
		exit();
	}

	//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
	if ($_POST['token'] != $_SESSION['token']){
		echo "不正なリクエスト";
		exit();
	}

	//クリックジャッキング対策
	header('X-FRAME-OPTIONS: SAMEORIGIN');

	// ログイン状態チェック
	if (!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_password'])) {
    		header("Location: admin_login.php");
    		exit();
	}

	//ユーザー名を設定
	$admin_user = $_SESSION['admin_user'];

	//クロスサイトリクエストフォージェリ（CSRF）対策
	$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
	$token = $_SESSION['token'];

?>
<?php
	//投稿ボタンを押したとき
	$user = $_SESSION['admin_user'];
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

<?php
	if($case == 1){
		if(empty($user)){
			echo "<br>";
			echo "<strong>Error: 名前が入っていません。</strong><br>";
			echo "<br>";
		}
	}
	elseif($case == 2){
		if(empty($user)){
			echo "<br>";
			echo "<strong>Error: 名前が入っていません。</strong><br>";
			echo "<br>";
		}
		if(empty($_POST['edit_number'])){
			echo "<br>";
			echo "<strong>Error: 編集番号を入力してください。</strong><br>";
			echo "<br>";
		}
		elseif($_POST['edit_number'] <= 0){
			echo "<br>";
			echo "<strong>Error: 正しい編集番号を入力してください。</strong><br>";
			echo "<br>";
		}
	}
?>

<!DOCTYPE html>
<html lang = "ja">
<head>
  <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. ここは一旦、いじらなくてOKです。 -->
  <meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
  <link rel="stylesheet" type="text/css" href="../layout/admin/admin_post.css">
  <title><?=htmlspecialchars($user, ENT_QUOTES, 'UTF-8')?>さんの投稿・編集ページ</title>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>
<body>
<div class = "head_line">

<img src="../images/logo.jpg" class="logo">
<p class="title">投稿・編集ページ</p>

</div>

<div class="table">

<p>コメントを入力してください。</p>
<p>管理者権限で新規投稿、またはすべてのユーザーの投稿の編集ができます。</p>
<p>アップロードする画像や動画の容量は、<strong>2MBまで</strong>です。</p>
<p>投稿できるファイル形式は、<strong>「.jpg」、「.png」、「.jpeg」、「.gif」、「.mp4」の5つ</strong>です。</p>

<form method = "post" action = "admin_mypage.php" enctype="multipart/form-data">
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

<form method = "post" action = "admin_mypage.php">
<table align="center">
<input type = "hidden" name = "token" value = <?=htmlspecialchars($token, ENT_QUOTES, 'UTF-8')?> >
<tr align="center">
<td colspan="2"><input type="submit" value="戻る" style = "width:100px; height: 30px"/></td>
</tr>
</table>
</form>

</div>
</body>
</html>
