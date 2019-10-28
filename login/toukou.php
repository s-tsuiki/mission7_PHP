<!DOCTYPE html>
<html lang="ja">

<head>
<title>postpage.php</title>
<meta charset="utf-8" meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimumscale=1.0, maximum-scale=2.0, user-scalable=yes">
<!-- for smartphone. スマホ対応にしたいので、 他のページでも入れてください。 -->
</head>

<body>
<h1>投稿フォーム</h1>


<?php
//データベース接続
require 'tools/database_connect/database_connect.php';
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

  //セッション開始
  session_start();
  //投稿（編集）ボタンが押されたとき
  if(isset($_POST["post"]){
  //編集対象番号を変数に格納
  $editnum=$_POST["editnum"];
 	 //編集番号が空の時新規投稿にする
  	if($editnum ==  "" ){																													
	    if(!empty($_POST["comment"]) && isset($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error'])){
            //エラーチェック
            switch ($_FILES['upfile']['error']) {
                case UPLOAD_ERR_OK: // OK
                    break;
                case UPLOAD_ERR_NO_FILE:   // 未選択
                    throw new RuntimeException('ファイルが選択されていません', 400);
                case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
                    throw new RuntimeException('ファイルサイズが大きすぎます', 400);
                default:
                    throw new RuntimeException('その他のエラーが発生しました', 500);
            }
	    //userid,username,comment,filename,genreを変数に格納
	    $userid = $_SESSION['id'];
	    $username = $_SESSION['user'];
	    $comment = $_POST["comment"];
	    $filename = $_POST["upfile"];
	    $genre = $_POST["toukou"];

            //userid,username,comment,filename,genreをDBに格納．
            $sql = "INSERT INTO cmt_list (id, user, comment, filename, genre) VALUES (:id, :user, :comment, :filename, :genre);";
            $stmt = $pdo->prepare($sql);
            $stmt -> bindValue(":id",$userid, PDO::PARAM_STR);
            $stmt -> bindValue(":user",$username, PDO::PARAM_STR);
            $stmt -> bindValue(":comment",$comment, PDO::PARAM_STR);
	    $stmt -> bindValue(":filename",$filename, PDO::PARAM_STR);
	    $stmt -> bindValue(":genre",$genre, PDO::PARAM_STR);
            $stmt -> execute();
	      echo '<script>alert("投稿が完了しました。");location.href = "/mypage.php"</script>';
	    }

       //編集番号が1以上のとき編集する。コメント、画像、ジャンルを表示するまで
       }elseif($editnum >= 1){
	    $sql = "SELECT * FROM cmt_list WHERE num = '$editnum'";
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
	    foreach ($results as $row){
	    //変更したいコメント、画像、ジャンルを表示
	    $newcomment = $row["comment"];
	    $newfilename = $row["upfile"];
	    $newgenre = $row["toukou"];
	    }

		//画像、コメント、ジャンルを編集して再投稿するまで
		if(!empty($_POST["comment"]) && isset($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error'])){
            	  //エラーチェック
            	  switch ($_FILES['upfile']['error']) {
                	case UPLOAD_ERR_OK: // OK
                    	  break;
                	case UPLOAD_ERR_NO_FILE:   // 未選択
                    	  throw new RuntimeException('ファイルが選択されていません', 400);
                	case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
                    	  throw new RuntimeException('ファイルサイズが大きすぎます', 400);
                	default:
                    	  throw new RuntimeException('その他のエラーが発生しました', 500);
            	  }
        		//updateで上書きする
			$sql ="UPDATE cmt_list SET comment=:comment, filename=:filename, genre=:genre WEHRE num = '$editnum'"; 
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt->bindParam(':filename', $filename, PDO::PARAM_STR);
			$stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
			$stmt->execute();
			  echo '<script>alert("編集が完了しました。");location.href = "/mypage.php"</script>';
		}
	}
  }


?>

<form id="submit_form" action="postpage.php" enctype="multipart/form-data" method="POST">
<input type="hidden" name="editnum" value="$editnum">

<select name="toukou" id="submit_select" onchange="submit(this.form)" value=<?php if($editnum >=1){ echo "$newgenre";} ?>>
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
</select></br>



        <label>画像/動画アップロード</label></br>
        <input type="file" name="upfile" value=<?php if($editnum >= 1){ echo "$newfilename";} ?>></br>
	<label>コメント</label></br>
	<input type="text" size="40" name="comment" value=<?php if($editnum >= 1){ "$newcomment";} ?>>
        <br>
        ※画像はjpeg方式，png方式，gif方式に対応しています。動画はmp4方式のみ対応しています。<br>
        <input type="submit" name="post" value=<?php if($editnum == ""){ echo "投稿する";} elseif($editnum >= 1){ echo "編集する";} ?>>
    </form>

?>


</body>
</hrml>