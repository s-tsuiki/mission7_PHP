<!DOCTYPE html>
<html lang = “ja”>
	<head>
	<meta charset="utf-8" name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. -->
	<link rel="stylesheet" type="text/css" href="/layout/mission_7.css">
	<title>おすきにどうぞ！</title>
	</head>

	<header>
	<h1><a href = "toppage.php">おすきにどうぞ！</a></h1>
	<h2>
	<?php 
	session_start();
	if(isset($_SESSION['USERID'])){
	echo '<a href = "mypage.php">マイページ</a>';
	}else{
	echo 	'<a href = "login.php">新規登録・ログイン</a>';
}
?>
</h2>
  </header>
  <hr>	
  
	<p>すきを語らう憩いの場です！<br>あなたの「好き」を熱く語りましょう
	</p>
	<hr>
	
<?php
//トップページ、登録なしでも閲覧可能

//$dsnの式の中にスペースを入れないこと！
// 4-1データベースへの接続
function db_connect(){
$dsn = '';
	$user = '';
	$password = '';

	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	return $pdo;
}
require '../database_connect/database_connect.php';
	//データベースへの接続
	$pdo = db_connect();
//4-2テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS cmt_list (
num INT AUTO_INCREMENT PRIMARY KEY,
id INT NOT NULL,
user VARCHAR(50) NOT NULL,
comment TEXT,
filename VARCHAR(128),
genre VARCHAR(128) NOT NULL
)";
//ジャンルの項目を追加
$stmt = $pdo->query($sql);
//データベース接続切断
	$pdo = null;
	?>
	
<form id = "submit_form" action = "toppage.php" method = "post">
<select name="toukou" id = "submit_select" onchange = "submit(this.form)">
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
			$("#submit_form").submit();
		});
	});
</script>
	
<section>
	<?php
	
	$pdo = db_connect();
if(empty($_POST["toukou"])){//
	$sql = "SELECT comment,filename,genre FROM cmt_list";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
	$genre = $row['genre'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>by. ".$username."jungle".$genre."<hr>";
}
}
else{//選択したジャンルを表示
if($_POST["toukou"]=="全て"){
	$sql = "SELECT comment,filename FROM cmt_list";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>by. ".$username;
}
}
if($_POST["toukou"]=="食べ物"){
	$sql = "SELECT comment,filename FROM cmt_list";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>by. ".$username;
}
}
if($_POST["toukou"]=="芸能人"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE genre='2'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>by. ".$username;
}
}
if($_POST["toukou"]=="ネット有名人"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE genre='3'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>by. ".$username;
}
}
if($_POST["toukou"]=="アニメ"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE genre='4'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>by. ".$username;
}
}
if($_POST["toukou"]=="映画"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE genre='5'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>by. ".$username;
}
}
if($_POST["toukou"]=="音楽"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE genre='6'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>by. ".$username;
}
}
if($_POST["toukou"]=="舞台"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE genre='7'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>by. ".$username;
}
}
if($_POST["toukou"]=="スポーツ"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE genre='8'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>by. ".$username;
}
}
if($_POST["toukou"]=="機械"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE genre='9'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>by. ".$username;
}
}
if($_POST["toukou"]=="その他"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE genre='10'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>by. ".$username;
}
}
}
	?>
	</section>
	</body>
</html>	
