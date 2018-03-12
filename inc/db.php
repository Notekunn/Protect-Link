<?php
  ob_start();
  // Cấu Hình Wap Site
  $groupid           = '378443292610123';
  $url               = 'http://anlink.simsimi.tech';
  $GoogleApiKey      = 'AIzaSyBTIJVq8068Xj16yXT0Kcd7Ll81rl-w-lk';
  $FacebookAppID     = '';
  $FacebookAppSecret = '';
  //token_page_admin_gr
  $token_admin       = '';
  // ID bài viết xác nhận mem
  $id_bv             = '';

  // Thông Tin MySql
  $host = '';
  $user = '';
  $pass = '';
  $data = '';

  try {
      $db = new PDO("mysql:host=$host;dbname=$data", $user, $pass);
      // set the PDO error mode to exception
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  }
  catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
  }
  $db->query("SET NAMES 'utf8'");


  function get_info_by_curl($url)
  {
      $curlObj = curl_init();

      curl_setopt($curlObj, CURLOPT_URL, $url);
      curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($curlObj, CURLOPT_HEADER, 0);
      curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
      curl_setopt($curlObj, CURLOPT_POST, 0);
      $ch = curl_exec($curlObj);
      curl_close($curlObj);
      return $ch;
  }

  ob_end_flush();
?>
