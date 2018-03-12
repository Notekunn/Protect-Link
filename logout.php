<?php
 /*
 --------------------------------------
 -- Tên Code : Code Link Protect Facebook Page
 -- Người Coder : Hậu Nguyễn
 -- Sử Dụng : PHP 7 & PDO
 -- Vui Lòng Tôn Trọng Bản Quyền
 --------------------------------------
 */
define('Hadpro', 1);
session_start();
unset($_SESSION["facebook_access_token"]);
unset($_SESSION["facebook_user_idfacebook_user_name"]);
unset($_SESSION["facebook_user_name"]);
header("Location: /index.php");
?>
