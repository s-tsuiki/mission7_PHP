<?php
//トップページから、ユーザー名を変数としてpostする
//$user = $_POST['user'];
//今は値を設定している
$username = "RIN";
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

</style>

</head>
<body>
<div class="headline">
<img src="images/logo.jpg" class="logo"><p class="title"><?php echo $username."のページ";?></p><a href="toppage.php" class="link">トップページへ</a>
</div>
<p>投稿一覧</p>

<form id="submit_form" action="userpage.php" method="POST">
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
$dsn = 'mysql:dbname=tb210282db; host=localhost';
$user = '';
$password = '';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


if(empty($_POST["toukou"])){
	echo "<div class='panel'>";
	$sql = "SELECT comment,filename,genre FROM cmt_list WHERE user='$username'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
	$genre = $row['genre'];
	echo "<div class='box'>";
    echo "<div class='pic' id='pic'><img src='".$filename."' class='cut'></div>";
    echo $comment."<br>by. ".$username."jungle".$genre."</div>";

}
	echo "</div>";
}
else{

if($_POST["toukou"]=="全て"){
	echo "<div class='panel'>";
	$sql = "SELECT comment,filename,genre FROM cmt_list WHERE user='$username'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
	$genre = $row['genre'];
	echo "<div class='box'>";
    echo "<div class='pic' id='pic'><img src='".$filename."' class='cut'></div>";
    echo $comment."<br>by. ".$username."jungle".$genre."</div>";

}
	echo "</div>";
}
if($_POST["toukou"]=="食べ物"){
	echo "<p>".$_POST["toukou"]."</p>";
	echo "<div class='panel'>";
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='1'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
	echo "<div class='box'>";
    echo "<div class='pic' id='pic'><img src='".$filename."'></div>";
    echo $comment."<br>by. ".$username."</div>";
}
    echo "</div>";
}

if($_POST["toukou"]=="芸能人"){
	echo "<p>".$_POST["toukou"]."</p>";
	echo "<div class='panel'>";
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='2'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
	echo "<div class='box'>";
    echo "<div class='pic' id='pic'><img src='".$filename."'></div>";
    echo $comment."<br>by. ".$username."</div>";
}
    echo "</div>";
}

if($_POST["toukou"]=="ネット有名人"){
	echo "<p>".$_POST["toukou"]."</p>";
	echo "<div class='panel'>";
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='3'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
	echo "<div class='box'>";
    echo "<div class='pic' id='pic'><img src='".$filename."' width='200px'></div>";
    echo $comment."<br>by. ".$username."</div>";
}
    echo "</div>";
}

if($_POST["toukou"]=="アニメ"){
	echo "<p>".$_POST["toukou"]."</p>";
	echo "<div class='panel'>";
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='4'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
	echo "<div class='box'>";
    echo "<div class='pic' id='pic'><img src='".$filename."' width='200px'></div>";
    echo $comment."<br>by. ".$username."</div>";
}
    echo "</div>";
}


if($_POST["toukou"]=="映画"){
	echo "<p>".$_POST["toukou"]."</p>";
	echo "<div class='panel'>";
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='5'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
	echo "<div class='box'>";
    echo "<div class='pic' id='pic'><img src='".$filename."' width='200px'></div>";
    echo $comment."<br>by. ".$username."</div>";
}
    echo "</div>";
}

if($_POST["toukou"]=="音楽"){
	echo "<p>".$_POST["toukou"]."</p>";
	echo "<div class='panel'>";
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='6'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
	echo "<div class='box'>";
    echo "<div class='pic' id='pic'><img src='".$filename."' width='200px'></div>";
    echo $comment."<br>by. ".$username."</div>";
}
    echo "</div>";
}


if($_POST["toukou"]=="舞台"){
	echo "<p>".$_POST["toukou"]."</p>";
	echo "<div class='panel'>";
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='7'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
	echo "<div class='box'>";
    echo "<div class='pic' id='pic'><img src='".$filename."' width='200px'></div>";
    echo $comment."<br>by. ".$username."</div>";
}
    echo "</div>";
}

if($_POST["toukou"]=="スポーツ"){
	echo "<p>".$_POST["toukou"]."</p>";
	echo "<div class='panel'>";
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='8'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
	echo "<div class='box'>";
    echo "<div class='pic' id='pic'><img src='".$filename."' width='200px'></div>";
    echo $comment."<br>by. ".$username."</div>";
}
    echo "</div>";
}

if($_POST["toukou"]=="機械"){
	echo "<p>".$_POST["toukou"]."</p>";
	echo "<div class='panel'>";
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='9'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
	echo "<div class='box'>";
    echo "<div class='pic' id='pic'><img src='".$filename."' width='200px'></div>";
    echo $comment."<br>by. ".$username."</div>";
}
    echo "</div>";
}

if($_POST["toukou"]=="その他"){
	echo "<p>".$_POST["toukou"]."</p>";
	echo "<div class='panel'>";
	$sql = "SELECT comment,filename FROM cmt_list WHERE user='$username' AND genre='10'";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
	$comment = $row['comment'];
	$filename = $row['filename'];
	echo "<div class='box'>";
    echo "<div class='pic' id='pic'><img src='".$filename."' width='200px'></div>";
    echo $comment."<br>by. ".$username."</div>";
}
    echo "</div>";
}

}
?>
</body>
</html>
