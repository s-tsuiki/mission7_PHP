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

?>

<?php
	//フォーム処理部分

	//必要な値を設定
	$id = 1;
	$date_time = date("Y/m/d H:i:s");
	$user = $admin_user;
	//$admin_password = $_SESSION['admin_password'];
	//パスワードが正しいかの確認 0:初期状態 1:正しい 2:正しくない
	$is_correct = 0;
	//どの状況かを判断
	$case = 0;

	//データベースへの接続
	$pdo = db_connect();

	//データベースの作成
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

	
	//入力フォームの処理
	if(!empty($_POST['e_number'])){
		//編集モード
		$case = 1;

		if(isset($_POST['user']) || isset($_POST['comment'])){
			
			if(!empty($_POST['user']) && !empty($_POST['comment'])){
				//POSTで各値を受け取る
				$num = $_POST['e_number'];
				$user = $_POST['user'];
				$comment = $_POST['comment']."<br>"."（この投稿は管理者によって編集されました）"."<br>".$date_time;
				$genre = $_POST['genre'];
				//$admin_password = $_POST['admin_password'];

				//パスワードのハッシュ化
				//$admin_password_hash =  password_hash($admin_password, PASSWORD_DEFAULT);

				//画像・動画投稿
				if(!empty($_FILES['upfile'])){
    					$upfile = $_FILES['upfile'];
    					$path = "../upload";
					$imagesExt=['jpg','png','jpeg','gif','mp4'];
					$upfile_name = "";
    
    					//エラーかどうか
    					if ($upfile['error'] == 00) {
        					$ext = strtolower(pathinfo($upfile['name'],PATHINFO_EXTENSION));
        					//拡張子判定
        					if (!in_array($ext,$imagesExt)){
            						echo "<script> alert('対応するファイル形式にしてください。');</script>";
        					}
        					//パスがあるか
        					if (!is_dir($path)){
            						mkdir($path,0777,true);  
        					}
        					$filename = md5(uniqid(microtime(true),true)).'.'.$ext; //ユニークidをつける
        					$destname = $path."/".$filename; //
        					// アップロード成功か
        					if (!move_uploaded_file($upfile['tmp_name'],$destname)){
            						echo "<script> alert('アップロード失敗しました。');</script>";
        					}
        					else{
							$upfile_name = $filename;
            						//echo "<script> alert('アップロード成功しました！');location.href='admin_mypage.php';</script>";
        					}
    					}
					
					//アップロード成功
					if(!empty($upfile_name)){
						//以前の画像、動画の削除
						$sql = 'select filename from cmt_list where num=:num and user=:user';
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':num', $num, PDO::PARAM_INT);
						$stmt->bindParam(':user', $user, PDO::PARAM_STR);
						$stmt->execute();
						if($stmt->rowCount() == 1){
							$result = $stmt->fetch();
							$delete_filename = $result['filename'];
							if(!empty($delete_filename)){
								unlink($path."/".$delete_filename);
							}
						}

						//filename情報を追加
						$sql = 'update cmt_list set comment=:comment,filename=:filename,genre=:genre where num=:num and user=:user';
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
						$stmt->bindParam(':filename', $filename, PDO::PARAM_STR);
						$stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
						//$stmt->bindParam(':password_hash', $admin_password_hash, PDO::PARAM_STR);
						$stmt->bindParam(':num', $num, PDO::PARAM_INT);
						$stmt->bindParam(':user', $user, PDO::PARAM_STR);
						
                				$stmt->execute();
					}else{
						//入力したデータをupdateによって編集する
						$sql = 'update cmt_list set comment=:comment,genre=:genre where num=:num and user=:user';
						$stmt = $pdo->prepare($sql);
						$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
						$stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
						//$stmt->bindParam(':password_hash', $admin_password_hash, PDO::PARAM_STR);
						$stmt->bindParam(':num', $num, PDO::PARAM_INT);
						$stmt->bindParam(':user', $user, PDO::PARAM_STR);
						$stmt->execute();
					}
    				}else {
					//入力したデータをupdateによって編集する
					$sql = 'update cmt_list set comment=:comment,genre=:genre where num=:num and user=:user';
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
					$stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
					//$stmt->bindParam(':password_hash', $admin_password_hash, PDO::PARAM_STR);
					$stmt->bindParam(':num', $num, PDO::PARAM_INT);
					$stmt->bindParam(':user', $user, PDO::PARAM_STR);
					$stmt->execute();
				}


				//初期化
				//$name = "";
				$comment = "";
				$e_number = NULL;
			}
		}
	}
	elseif(isset($_POST['user']) || isset($_POST['comment'])){
		//入力モード
		$case = 2;
		if(!empty($_POST['user']) && !empty($_POST['comment'])){
			//POSTで各値を受け取る
			$user = $_POST['user'];
			$comment = $_POST['comment'];
			$genre = $_POST['genre'];
			//$admin_password = $_POST['admin_password'];

			//usr_listテーブルからidを取得
			$sql = "SELECT id FROM usr_list WHERE user=:user";
			$stmt = $pdo -> prepare($sql);
			$stmt -> bindParam(':user', $user, PDO::PARAM_STR);
			$stmt -> execute();
			
			if( $stmt->rowCount() == 1){
				$result = $stmt->fetch();
				$id = $result['id'];
			}else{
				echo "ユーザー取得エラー";
				exit();
			}

			//パスワードのハッシュ化
			//$admin_password_hash =  password_hash($admin_password, PASSWORD_DEFAULT);

			//画像・動画投稿
			if(!empty($_FILES['upfile'])){
    				$upfile = $_FILES['upfile'];
    				$path = "../upload";
				$imagesExt=['jpg','png','jpeg','gif','mp4'];
				$upfile_name = "";
    
    				//エラーかどうか
    				if ($upfile['error'] == 00) {
        				$ext = strtolower(pathinfo($upfile['name'],PATHINFO_EXTENSION));
        				//拡張子判定
        				if (!in_array($ext,$imagesExt)){
            					echo "<script> alert('対応するファイル形式にしてください。');</script>";
        				}
        				//パスがあるか
        				if (!is_dir($path)){
            					mkdir($path,0777,true);  
        				}
        				$filename = md5(uniqid(microtime(true),true)).'.'.$ext; //ユニークidをつける
        				$destname = $path."/".$filename; //
        				// アップロード成功か
        				if (!move_uploaded_file($upfile['tmp_name'],$destname)){
            					echo "<script> alert('アップロード失敗しました。');</script>";
        				}
        				else{
						$upfile_name = $filename;
            					//echo "<script> alert('アップロード成功しました！');location.href='admin_mypage.php';</script>";
        				}
    				}
    				
				//アップロード成功
				if(!empty($upfile_name)){
					//insertを行ってデータを入力
					$sql = $pdo -> prepare("INSERT INTO cmt_list (id, user, comment, filename, genre) VALUES (:id, :user, :comment, :filename, :genre)");
					$sql -> bindParam(':id', $id, PDO::PARAM_INT);
					$sql -> bindParam(':user', $user, PDO::PARAM_STR);
					$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
					$sql -> bindParam(':filename', $filename, PDO::PARAM_STR);
					$sql -> bindParam(':genre', $genre, PDO::PARAM_STR);
					//$sql -> bindParam(':password_hash', $password_hash, PDO::PARAM_STR);
					$sql -> execute();

					//echo "<script> alert('アップロード成功しました！');location.href='admin_mypage.php';</script>";
				}else {
					//insertを行ってデータを入力
					$sql = $pdo -> prepare("INSERT INTO cmt_list (id, user, comment, genre) VALUES (:id, :user, :comment, :genre)");
					$sql -> bindParam(':id', $id, PDO::PARAM_INT);
					$sql -> bindParam(':user', $user, PDO::PARAM_STR);
					$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
					$sql -> bindParam(':genre', $genre, PDO::PARAM_STR);
					//$sql -> bindParam(':password_hash', $password_hash, PDO::PARAM_STR);
					$sql -> execute();
				}
    			}else{
				//insertを行ってデータを入力
				$sql = $pdo -> prepare("INSERT INTO cmt_list (id, user, comment, genre) VALUES (:id, :user, :comment, :genre)");
				$sql -> bindParam(':id', $id, PDO::PARAM_INT);
				$sql -> bindParam(':user', $user, PDO::PARAM_STR);
				$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
				$sql -> bindParam(':genre', $genre, PDO::PARAM_STR);
				//$sql -> bindParam(':password_hash', $password_hash, PDO::PARAM_STR);
				$sql -> execute();
			}

			//初期化
			//$name = "";
			$comment = "";
			$e_number = NULL;
		}
	}

	//削除フォームの処理
	elseif(isset($_POST['delete_number'])){
		$case = 3;
		if(!empty($_POST['delete_number'])){
			//削除番号を格納
			$num = $_POST['delete_number'];
			//$password = $_POST['delete_password'];
			
			//入力したデータをdeleteによって削除する
			if($num > 0){
				//以前の画像、動画の削除
				$path = "../upload";
				$sql = 'select filename from cmt_list where num=:num and user=:user';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':num', $num, PDO::PARAM_INT);
				$stmt->bindParam(':user', $user, PDO::PARAM_STR);
				$stmt->execute();
				if($stmt->rowCount() == 1){
					$result = $stmt->fetch();
					$delete_filename = $result['filename'];
					if(!empty($delete_filename)){
						unlink($path."/".$delete_filename);
					}
				}

				$sql = 'delete from cmt_list where num=:num';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':num', $num, PDO::PARAM_INT);
				$stmt->execute();
				if($stmt->rowCount() == 1){
					//削除成功
					$is_correct = 1;
				}else{
					//ユーザー名が一致しないとき、削除しない
					$is_correct = 2;
				}
			}else{
				//パスワードが一致しないとき、削除しない
				$is_correct = 2;
			}
		}
	}

	//クロスサイトリクエストフォージェリ（CSRF）対策
	$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
	$token = $_SESSION['token'];
?>

<!DOCTYPE html>
<html lang = "ja">
<head>
  <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. ここは一旦、いじらなくてOKです。 -->
  <meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
  <link rel="stylesheet" type="text/css" href="../layout/admin/admin_mypage.css">
  <title><?=htmlspecialchars($user, ENT_QUOTES, 'UTF-8')?>さんの管理者ページ</title>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <!--
  <script>
	$(document).ready(function(){
		$(".pic").on("load",function(){
			var iw,ih;
			var cw=250;
			var ch=250;
			iw=($(this).width()-cw)/2;
			ih=($(this).height()-ch)/2;
			$(this).css("top","-"+ih+"px");
			$(this).css("left","-"+iw+"px");
		});
	});
  </script>
  <script type="text/javascript">
	window.onload=function(){
        changeImgSize();
    }
    function changeImgSize(){
        var getContainer=document.getElementById('pic');//父元素div
        var getIMG=getContainer.getElementsByTagName('img')[0];
        var fw=getContainer.offsetWidth-(2*getContainer.clientLeft);
        var fh=getContainer.offsetHeight-(2*getContainer.clientTop);
        var iw=getIMG.width;
        var ih=getIMG.height;
        var m=iw/fw;//?片与父元素?度比
        var n=ih/fh;//?片与父元素高度比
        if(m>=1&&n<=1)//?片比父元素? 或者?片比父元素短
        {
            iw=Math.ceil(iw/n);
            ih=Math.ceil(ih/n);
            getIMG.width=iw;
            getIMG.height=ih;
        }
        else if(m<=1&&n>=1)//?片比父元素窄 或者?片比父元素高
        {
            iw=Math.ceil(iw/m);
            ih=Math.ceil(ih/m);
            getIMG.width=iw;
            getIMG.height=ih;
        }
        else if(m>=1&&n>=1)
        {
            getMAX=Math.min(m,n);
            iw=Math.ceil(iw/getMAX);
            ih=Math.ceil(ih/getMAX);
            getIMG.width=iw;
            getIMG.height=ih;
        }
        var getDistance;
        var getDistance2;
        if(fh>getIMG.height){
            getDistance=Math.floor((fh-getIMG.height)/2);
            getIMG.style.marginTop=getDistance.toString()+"px";
        }else {
            getDistance=Math.floor((getIMG.height-fh)/2);
            getIMG.style.marginTop="-"+getDistance.toString()+"px";
        }
        if(fw>getIMG.width){
            getDistance2=Math.floor((fw-getIMG.width)/2);
            getIMG.style.marginLeft=getDistance2.toString()+"px";
        }else {
            getDistance2=Math.floor((getIMG.width-fw)/2);
            getIMG.style.marginLeft="-"+getDistance2.toString()+"px";
        }
    }
  </script>
  -->
</head>
<body>

<div class = "user_bar">

</div>

<div class = "head_line">
   <img src="../images/logo.jpg" class="logo">
   <p class="title"><strong><?=htmlspecialchars($admin_user, ENT_QUOTES, 'UTF-8')?></strong>さんの管理者ページ</p>
   <!--CSRF対策-->
  <form method = "post" action = "admin_logout.php">
   <input type = "hidden" name = "token" value = <?=htmlspecialchars($token, ENT_QUOTES, 'UTF-8')?> >
   <input type = "submit" value="ログアウト" style = "width:100px; height: 30px"/><br>
  </form>
</div>

<br>

<div class = "error_message">
<?php
	//エラーメッセージ
	if($case == 1 || $case == 2){
		if(empty($_POST['user'])){
			echo "<br>";
			echo "<strong>Error: 名前が入っていません。</strong><br>";
			echo "<br>";
		}
		elseif(empty($_POST['comment'])){
			echo "<br>";
			echo "<strong>Error: コメントを入力してください。</strong><br>";
			echo "<br>";
		}
	}
	elseif($case == 3){
		if(empty($_POST['delete_number'])){
			echo "<br>";
			echo "<strong>Error: 削除番号を入力してください。</strong><br>";
			echo "<br>";
		}
		elseif($_POST['delete_number'] <= 0){
			echo "<br>";
			echo "<strong>Error: 正しい削除番号を入力してください。</strong><br>";
			echo "<br>";
		}
	}
	
?>
</div>

<h2>投稿一覧</h2>
<!--CSRF対策-->
<form method = "post" action = "admin_mypage.php">
 <input type = "hidden" name = "token" value = <?=htmlspecialchars($token, ENT_QUOTES, 'UTF-8')?> >
 <input type = "submit" value="更新" style = "width:100px; height: 30px"/><br> 
</form>
<br>

<form id="submit_form" action="admin_mypage.php" method="POST">
ジャンル：<select name="toukou" id="submit_select">
<option value="選択してください">選択してください</option>
<option value="全て">全て</option>
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
<input type = "hidden" name = "token" value = <?=htmlspecialchars($token, ENT_QUOTES, 'UTF-8')?> >
</form>
<script type="text/javascript">
 $(function(){
   //if(!empty($_POST['toukou'])){
      //document.getElementById('submit_select').value = $_POST['toukou'];
   //}
   $("#submit_select").change(function(){
      var r = $("option:selected").val();
      $("#submit_form").submit();
      
      //$("#submit_select").val(r);
   });
 });
</script>

<?php

	if(empty($_POST["toukou"])){
		echo "<div class='panel'>";
		$sql = "SELECT * FROM cmt_list";
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach ($results as $row){
    			//$rowの中にはテーブルのカラム名が入る
			$num = $row['num'];
			$comment = $row['comment'];
			$filename = $row['filename'];
			$genre = $row['genre'];
			$username = $row['user'];
			echo "<div class='box'>";
			//画像または動画を表示
			if(!empty($filename)){
				$ext = strtolower(pathinfo($filename,PATHINFO_EXTENSION));
				$imgext = ['jpg','png','jpeg','gif'];
				if(in_array($ext,$imgext)){
					echo "<div class='pic' id='pic'><img src='../upload/".$filename."'></div>";
    				}else{
					echo "<div class='pic' id='pic'><video width='440px' src='../upload/".$filename."' controls></video></div>";
				}
			}else{
				echo "<br><br><br><br><br>";
			}
			
			//削除ボタン
			echo "<div class='button'>";
			echo "<form method = 'post' action = 'admin_mypage.php'>";
			echo "<input type = 'hidden' name = 'delete_number' value = ".$num.">";
			echo "<input type = 'submit' value = '削除' style = 'width:100px; height: 30px'/>";
			echo "<input type = 'hidden' name = 'token' value = ".htmlspecialchars($token, ENT_QUOTES, 'UTF-8')." >";
			echo "</form>";
			echo "</div>";
			//編集ボタン
			echo "<div class='button'>";
			echo "<form method = 'post' action = 'admin_post.php'>";
			echo "<input type = 'hidden' name = 'edit_number' value = ".$num.">";
			echo "<input type = 'submit' value = '編集' style = 'width:100px; height: 30px'/>";
			echo "<input type = 'hidden' name = 'token' value = ".htmlspecialchars($token, ENT_QUOTES, 'UTF-8')." >";
			echo "</form>";
			echo "</div>";

			echo "<br>";
			
    			echo $comment."<br>by. ".$username."jungle".$genre."</div>";
		}
		echo "</div>";
	}else{
		if($_POST["toukou"]=="全て"){
			echo "<div class='panel'>";
			$sql = "SELECT * FROM cmt_list";
			$stmt = $pdo->query($sql);
			$results = $stmt->fetchAll();
			foreach ($results as $row){
    				//$rowの中にはテーブルのカラム名が入る
				$num = $row['num'];
				$comment = $row['comment'];
				$filename = $row['filename'];
				$genre = $row['genre'];
				$username = $row['user'];
				echo "<div class='box'>";
    				//画像または動画を表示
				if(!empty($filename)){
					$ext = strtolower(pathinfo($filename,PATHINFO_EXTENSION));
					$imgext = ['jpg','png','jpeg','gif'];
					if(in_array($ext,$imgext)){
						echo "<div class='pic' id='pic'><img src='../upload/".$filename."'></div>";
    					}else{
						echo "<div class='pic' id='pic'><video width='440px' src='../upload/".$filename."' controls></video></div>";
					}
				}else{
					echo "<br><br><br><br><br>";
				}
				//削除ボタン
				echo "<div class='button'>";
				echo "<form method = 'post' action = 'admin_mypage.php'>";
				echo "<input type = 'hidden' name = 'delete_number' value = ".$num.">";
				echo "<input type = 'submit' value = '削除' style = 'width:100px; height: 30px'/>";
				echo "<input type = 'hidden' name = 'token' value = ".htmlspecialchars($token, ENT_QUOTES, 'UTF-8')." >";
				echo "</form>";
				echo "</div>";
				//編集ボタン
				echo "<div class='button'>";
				echo "<form method = 'post' action = 'admin_post.php'>";
				echo "<input type = 'hidden' name = 'edit_number' value = ".$num.">";
				echo "<input type = 'submit' value = '編集' style = 'width:100px; height: 30px'/>";
				echo "<input type = 'hidden' name = 'token' value = ".htmlspecialchars($token, ENT_QUOTES, 'UTF-8')." >";
				echo "</form>";
				echo "</div>";

				echo "<br>";
				
    				echo $comment."<br>by. ".$username."jungle".$genre."</div>";
			}
			echo "</div>";
		}
		else{
			$genre = $_POST["toukou"];
			
			echo "<div class='panel'>";
			$sql = "SELECT * FROM cmt_list WHERE genre=:genre";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
			$stmt->execute();

			$results = $stmt->fetchAll();
			foreach ($results as $row){
    				//$rowの中にはテーブルのカラム名が入る
				$num = $row['num'];
				$comment = $row['comment'];
				$filename = $row['filename'];
				$username = $row['user'];
				echo "<div class='box'>";
				//画像または動画を表示
				if(!empty($filename)){
					$ext = strtolower(pathinfo($filename,PATHINFO_EXTENSION));
					$imgext = ['jpg','png','jpeg','gif'];
					if(in_array($ext,$imgext)){
						echo "<div class='pic' id='pic'><img src='../upload/".$filename."'></div>";
    					}else{
						echo "<div class='pic' id='pic'><video width='440px' src='../upload/".$filename."' controls></video></div>";
					}
				}else{
					echo "<br><br><br><br><br>";
				}
				//削除ボタン
				echo "<div class='button'>";
				echo "<form method = 'post' action = 'admin_mypage.php'>";
				echo "<input type = 'hidden' name = 'delete_number' value = ".$num.">";
				echo "<input type = 'submit' value = '削除' style = 'width:100px; height: 30px'/>";
				echo "<input type = 'hidden' name = 'token' value = ".htmlspecialchars($token, ENT_QUOTES, 'UTF-8')." >";
				echo "</form>";
				echo "</div>";
				//編集ボタン
				echo "<div class='button'>";
				echo "<form method = 'post' action = 'admin_post.php'>";
				echo "<input type = 'hidden' name = 'edit_number' value = ".$num.">";
				echo "<input type = 'submit' value = '編集' style = 'width:100px; height: 30px'/>";
				echo "<input type = 'hidden' name = 'token' value = ".htmlspecialchars($token, ENT_QUOTES, 'UTF-8')." >";
				echo "</form>";
				echo "</div>";

				echo "<br>";

    				echo $comment."<br>by. ".$username."jungle".$genre."</div>";
			}
			echo "</div>";
		}
	}

	//データベース接続切断
	$pdo = null;
?>

<form method = "post" action = "admin_post.php">
<input type = "hidden" name = "token" value = <?=htmlspecialchars($token, ENT_QUOTES, 'UTF-8')?> >
<input type="submit" value="+" class = "toukou">
</form>

</body>

</html>
