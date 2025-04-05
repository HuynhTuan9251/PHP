<?php
session_start();
session_unset();
session_destroy();
header("Location: /webbanhang/account/login.php");
exit();
?>
