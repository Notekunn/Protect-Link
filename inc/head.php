<?php
  /*
  --------------------------------------
  -- Tên Code : Code Link Protect Facebook Page
  -- Người Coder : Hậu Nguyễn
  -- Sử Dụng : PHP 7 & PDO
  -- Vui Lòng Tôn Trọng Bản Quyền
  --------------------------------------
  */
  defined('Hadpro') or die('Lỗi : Không Được Phép Truy Cập');
  require('login.php');

  echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Link Protect</title>
<link rel="shortcut icon" href="../Themes/images/favicon.png"/>
<base href="' . $url . '" />
<meta name="description" content="Ẩn link chống ninja, bảo vệ link share trên group, fanpage facebook, tăng tương tác cho bài viết">
<meta property="og:title" content="Link Protect" />
<meta property="og:image" content="logo.png" />
<meta property="og:site_name" content="Link Protect" />
<meta property="og:description" content="Ẩn link chống ninja, bảo vệ link share trên group, fanpage facebook, tăng tương tác cho bài viết" />
<meta property="og:url" content="' . $url . '" />
<link rel="stylesheet" type="text/css" href="' . $url . '/Themes/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="' . $url . '/Themes/css/perfect-scrollbar.min.css" />
<link rel="stylesheet" type="text/css" href="' . $url . '/Themes/css/style.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
</head>
<body>';
?>
  <div class=" container-scroller">
   <!--Navbar-->
        <nav class="navbar bg-primary-gradient col-lg-12 col-12 p-0 fixed-top navbar-inverse d-flex flex-row">
            <div class="bg-white text-center navbar-brand-wrapper">
                <a class="navbar-brand brand-logo-mini" href="#"><img src="../Themes/images/logo_mini.png" alt=""></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center">
                <button class="navbar-toggler navbar-toggler hidden-md-down align-self-center mr-3" type="button" data-toggle="minimize">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <form class="form-inline mt-2 mt-md-0 hidden-md-down">
                    <input class="form-control mr-sm-2 search" type="text" placeholder="Search">
                </form>
                <ul class="navbar-nav ml-lg-auto d-flex align-items-center flex-row">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-th"></i></a>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right hidden-lg-up align-self-center" type="button" data-toggle="offcanvas">
                  <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>
        <!--End navbar-->
	     <div class="container-fluid">
            <div class="row row-offcanvas row-offcanvas-right">
                <nav class="bg-white sidebar sidebar-fixed sidebar-offcanvas" id="sidebar">
                <?php if(isset($accessToken)){ ?>
				<div class="user-info">
                    <img src="<?php echo ''.$profile['picture']['url'].''; ?>" alt="">
                    <p class="name">Hello : <b><?php echo  ''.$profile['name'].''; ?></b></p>
                    <p class="designation"><a href="<?php echo ''.$profile['link'].''; ?>">Trang Cá Nhân</a></p>
                    <span class="online"></span>
                </div>
				<?php } else { ?>
				<div class="user-info">
                    <img src="../Themes/images/users.png" alt="">
                    <p class="name"><b>Hello !</b></p>
                    <span class="online"></span>
                </div>
				<?php } ?>
                    <ul class="nav">
                        <li class="nav-item active">
                            <a class="nav-link" href="<?php echo $url;?>">
                                <i class="fa fa-dashboard"></i>
                                <span class="menu-title">Trang Chủ</span>
                            </a>
                        </li>
						 <?php if(isset($accessToken)){ ?>
						 <li class="nav-item">
                            <a class="nav-link" href="<?php ''.$url.'';?>/baiviet.php">
                                <i class="fa fa-facebook-square" aria-hidden="true"></i>
                                <span class="menu-title">Bài Viết Mới</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php ''.$url.'';?>/link.php">
                                <i class="fa fa-edit"></i>
                                <span class="menu-title">Tạo Link</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php ''.$url.'';?>/logout.php">
                                <i class="fa fa-sign-out" aria-hidden="true"></i>
                                <span class="menu-title">Thoát</span>
                            </a>
                        </li>
						<?php } ?>
                    </ul>
                </nav>
                <!-- SIDEBAR ENDS -->
<div class="content-wrapper">
<?php
  if (!isset($accessToken)) {
      echo '<div class="row">
          <div class="card col-md-12">
            <div class="card-block">
              <h3 class="card-title text-primary text-left mb-5 mt-4">Link Protect Lock</h3>
              <form>
                <div class="text-center">
                    <a href="' . $loginUrl . '" id="btn-ketnoi" class="btn btn-primary btn-round"> <i class="fa fa-facebook-square"></i>  Login</a>
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

?>
