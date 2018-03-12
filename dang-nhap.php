<?php
ob_start();
session_start();
$url = 'http://simsimi.tech';
$FacebookAppID = '318522435315118';
$FacebookAppSecret = '26e71b82be6839a14d5f153dff532061';
require_once('inc/Facebook/autoload.php');
$fb = new Facebook\Facebook([
  'app_id' => $FacebookAppID,
  'app_secret' => $FacebookAppSecret,
  'default_graph_version' => 'v2.11',
  ]);

$helper = $fb->getRedirectLoginHelper();
$permissions = []; //optional

echo $loginUrl = $helper->getLoginUrl(''.$url.'/login.php', $permissions);

ob_end_flush();
if (isset($_SESSION['facebook_access_token'])) {
   header("Location: ./");
 }
else
{ 
  echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Link Protect</title>
<link rel="shortcut icon" href="../Themes/images/favicon.png"/>
<base href="'.$url.'" />
<meta name="description" content="Ẩn link chống ninja, bảo vệ link share trên group, fanpage facebook, tăng tương tác cho bài viết">
<meta property="og:title" content="Link Protect" />
<meta property="og:image" content="logo.png" />
<meta property="og:site_name" content="Link Protect" />
<meta property="og:description" content="Ẩn link chống ninja, bảo vệ link share trên group, fanpage facebook, tăng tương tác cho bài viết" />
<meta property="og:url" content="'.$url.'" />
<link rel="stylesheet" type="text/css" href="'.$url.'/Themes/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="'.$url.'/Themes/css/perfect-scrollbar.min.css" />
<link rel="stylesheet" type="text/css" href="'.$url.'/Themes/css/style.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
</head>
<body>';
	
  echo '<div class="row">
          <div class="card col-md-12">
            <div class="card-block">
              <h3 class="card-title text-primary text-left mb-5 mt-4">Link Protect Lock</h3>
              <form>
                <div class="text-center">
				    <a href="'.$loginUrl.'" id="btn-ketnoi" class="btn btn-primary btn-round"> <i class="fa fa-facebook-square"></i>  Login</a>
                </div><br />
				 <div class="form-group">
                     Lưu ý: Nếu là lần đầu tiên sử dụng Ứng dụng sẽ yêu cầu quyền lấy thông tin cá nhận <strong>Công khai</strong> của bạn. Ứng dụng chỉ lấy những thông tin mà bạn công khai như <strong>Tên</strong> và <strong>ID</strong> để xác nhận. Ngoài ra <strong>không lấy bất cứ quyền nào</strong>, không lưu cookie hay token.</p>
              </div>
              </form>
            </div>
          </div>
      </div>';	
	  exit;
} 
