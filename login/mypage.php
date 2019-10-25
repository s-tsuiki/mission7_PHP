<!DOCTYPE html>
<html lang = “ja”>
	<head>
	<meta charset="utf-8" name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. -->
	<link rel="stylesheet" type="text/css" href="/layout/mission_7.css">
	<title>おすきにどうぞ！</title>
	</head>

	<header>
<h1>マイページ</h1>
<div align="right"><h2><a href ="logout.php">ログアウト</a></h2></div>
<div align=center><h3><a href = "toppage.php">投稿一覧を見る</a></h3></div>
</header>
<hr>	

<?php
//ログイン確認
	session_start();
	if(!isset($_SESSION["USERID"])){
		header("Location:login.php");
		exit;
}
?>

<form id = "submit_form" action = "mypage.php" method = "post">
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
//ジャンル選択

<a href ="toukou.php">新規投稿</a><br>
<!新規投稿へのリンク!>
<br><br>
<hr>
<font size="4">過去の投稿</font><br>
<?php
//$dsnの式の中にスペースを入れないこと！
// 4-1データベースへの接続
function db_connect(){
$dsn = '';
	$user = '';
	$password = '';

	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	return $pdo;
}

$pdo = db_connect();	
//4-6入力したデータを表示
$username = $_SESSION["USERID"];
//whereでログインユーザー限定指定
if(empty($_POST["toukou"])){
	$sql = "SELECT comment,filename,genre FROM cmt_list WHERE user='$username'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
	$genre = $row['genre'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>by. ".$username."jungle".$genre."<br>"."<a href ='delete.php'>削除</a><br>"
."<a href ='edit.php'>編集</a><br>"."<hr>";
}
}
else{
if($_POST["toukou"]=="全て"){
	$sql = "SELECT comment,filename FROM cmt_list";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>"."<a href ='delete.php'>削除</a><br>"
."<a href ='edit.php'>編集</a><br>"."<hr>";
}
}
if($_POST["toukou"]=="食べ物"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='1'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment ."<br>"."<a href ='delete.php'>削除</a><br>"
."<a href ='edit.php'>編集</a><br>"."<hr>";
}
}
if($_POST["toukou"]=="芸能人"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='2'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>"."<a href ='delete.php'>削除</a><br>"
."<a href ='edit.php'>編集</a><br>"."<hr>";
}
}
if($_POST["toukou"]=="ネット有名人"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='3'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>"."<a href ='delete.php'>削除</a><br>"
."<a href ='edit.php'>編集</a><br>"."<hr>";
}
}
if($_POST["toukou"]=="アニメ"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='4'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>"."<a href ='delete.php'>削除</a><br>"
."<a href ='edit.php'>編集</a><br>"."<hr>";
}
}
if($_POST["toukou"]=="映画"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='5'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>"."<a href ='delete.php'>削除</a><br>"
."<a href ='edit.php'>編集</a><br>"."<hr>";
}
}
if($_POST["toukou"]=="音楽"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='6'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>"."<a href ='delete.php'>削除</a><br>"
."<a href ='edit.php'>編集</a><br>"."<hr>";
}
}
if($_POST["toukou"]=="舞台"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='7'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>"."<a href ='delete.php'>削除</a><br>"
."<a href ='edit.php'>編集</a><br>"."<hr>";
}
}
if($_POST["toukou"]=="スポーツ"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='8'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>"."<a href ='delete.php'>削除</a><br>"
."<a href ='edit.php'>編集</a><br>"."<hr>";
}
}
if($_POST["toukou"]=="機械"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='9'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>"."<a href ='delete.php'>削除</a><br>"
."<a href ='edit.php'>編集</a><br>"."<hr>";
}
}
if($_POST["toukou"]=="その他"){
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='10'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
    echo "<img src='".$filename."' width='200px'><br>";
    echo $comment."<br>"."<a href ='delete.php'>削除</a><br>"
."<a href ='edit.php'>編集</a><br>"."<hr>";
}
}
}

	?>
	</body>
</html>	