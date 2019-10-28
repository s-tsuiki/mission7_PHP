<?php
session_start();   
session_unset();
session_destroy();
echo '<script>alert("ログアウトしました。");location.href = "../toppage.php"</script>';
?>
