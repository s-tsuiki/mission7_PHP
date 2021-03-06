<?php

$username = $_POST['username'];

?>
<html>
<head>
<title>
<?php echo $username."のページ"; ?>
</title>
<meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimumscale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. スマホ対応にしたいので、 他のページでも入れてください。 -->
<style type="text/css">
.headline{
	height: 200px;
}
.title{
	float: right;
	font-size: 50px;
	padding-right: 50px;
	text-decoration: blink;
	text-shadow: 2px;
	font-family: cursive;
}
.logo{
	width: 300px;
	box-shadow: 1px;
	margin-left: 30px;
}
.genre{
	position: relative;
	float: right;
	height: 50px;
	font-size: 20px;
}
.link{
	float: right;
	position: absolute;
	top: 150px;
	right: 50px;
}
p{
	font-size: 30px;
	margin-left: 30px;
}
span{
	margin-left: 30px;
}
.panel{
	margin-left: 20px;
	column-count: 4;
	column-width: 270px;
	column-gap: 20px;
}
.box {
    float: left;
    width: 250px;
    height: 300px;
    padding: 10px;
    margin: 10px 5px 5px;
    -moz-page-break-inside: avoid;
    -webkit-column-break-inside: avoid;
    break-inside: avoid;
    background: white;
    box-sizing: border-box;
    overflow: auto;
    -moz-box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 1px 2px 0 rgba(0, 0, 0, 0.24);
    -webkit-box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 1px 2px 0 rgba(0, 0, 0, 0.24);
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 1px 2px 0 rgba(0, 0, 0, 0.24);
}
.pic{
	height: 250px;
	width: 230px;
	overflow: hidden;
}
.pic img{
    width: 100%;
    display: block;
}
.pic video {
    display: block;
    width: 100%;
}
</style>

</head>
<body>
<div class="headline">
<img src="images/logo.jpg" class="logo"><p class="title"><?php echo $username."のページ";?></p><a href="toppage.php" class="link">トップページへ</a>
</div>
<p>投稿一覧</p>

<form id="submit_form" action="userpage.php" method="POST">
<input type="hidden" name="username" value="<?php echo $username; ?>">
<span>ジャンル：</span>
<select name="toukou" id="submit_select" onchange="submit(this.form)">
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
</form>
<script type="text/javascript">
 $(function(){
  $("#submit_select").change(function(){
   var r = $("option:selected").val(); //user selected value
   $("#submit_form").submit();
   $("#submit_select").val(r);
  });
 });
</script>
<?php
require 'tools/database_connect/database_connect.php';
$pdo = db_connect();

	if(empty($_POST["toukou"])){
		echo "<div class='panel'>";
		$sql = "SELECT * FROM cmt_list WHERE user=:user";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':user', $username, PDO::PARAM_STR);
		$stmt->execute();

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
					echo "<div class='pic' id='pic'><img src='./upload/".$filename."'></div>";
    				}else{
					echo "<div class='pic' id='pic'><video width='440px' src='./upload/".$filename."' controls></video></div>";
				}
			}else{
				echo "<br><br><br><br><br>";
			}

			echo "<br>";
			
    			echo $comment."<br>";
			echo "ジャンル：".$genre."<br>";
			echo "by. ".$username."</div>";
		}
		echo "</div>";
	}else{
		if($_POST["toukou"]=="全て"){
			echo "<div class='panel'>";
			$sql = "SELECT * FROM cmt_list WHERE user=:user";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':user', $username, PDO::PARAM_STR);
			$stmt->execute();

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
						echo "<div class='pic' id='pic'><img src='./upload/".$filename."'></div>";
    					}else{
						echo "<div class='pic' id='pic'><video width='440px' src='./upload/".$filename."' controls></video></div>";
					}
				}else{
					echo "<br><br><br><br><br>";
				}

				echo "<br>";
				
    				echo $comment."<br>";
				echo "ジャンル：".$genre."<br>";
				echo "by. ".$username."</div>";
			}
			echo "</div>";
		}
		else{
			$genre = $_POST["toukou"];
			
			echo "<div class='panel'>";
			$sql = "SELECT * FROM cmt_list WHERE user=:user AND genre=:genre";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':user', $username, PDO::PARAM_STR);
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
						echo "<div class='pic' id='pic'><img src='./upload/".$filename."'></div>";
    					}else{
						echo "<div class='pic' id='pic'><video width='440px' src='./upload/".$filename."' controls></video></div>";
					}
				}else{
					echo "<br><br><br><br><br>";
				}

				echo "<br>";

    				echo $comment."<br>";
				echo "ジャンル：".$genre."<br>";
				echo "by. ".$username."</div>";
			}
			echo "</div>";
		}
	}

	//データベース接続切断
	$pdo = null;
?>
</body>
</html>
