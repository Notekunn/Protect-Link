<?php

ob_start();
session_start();
define('Hadpro', 1);
require('inc/head.php');


function FoundPost($groupid, $Token, $Hashtag)
{
    global $FoundPost, $FoundPostID;

    $FeedApi  = 'https://graph.facebook.com/' . $groupid . '/feed?fields=id,message&limit=50&access_token=' . $Token;
    $FeedJson = json_decode(get_info_by_curl($FeedApi), true);
    if (!$FeedJson['error']) {
        foreach ($FeedJson['data'] as $feed) {
            if (strpos(@$feed['message'], $Hashtag) !== FALSE) {
                $FoundPost   = true;
                $FoundPostID = str_replace($groupid . '_', '', $feed['id']);
                break;
            }
        }
    }


}
function PostInfo($groupid, $postID, $Token, $userID)
{
    global $FoundPostURL, $Nguoitao, $TKLike, $TKBL, $Liked, $Comments;
    // Lấy thông tin bài viết bao gồm link trực tiếp, tên tác giả, số like
    $PostApi      = 'https://graph.facebook.com/v2.10/' . $groupid . '_' . $postID . '?fields=id,permalink_url,message,from&access_token=' . $Token;
    $PostPage     = json_decode(get_info_by_curl($PostApi));

    $FoundPostURL = $PostPage->permalink_url;
    $Nguoitao     = $PostPage->from->name;
    //Lấy số like
    $demlike        = 'https://graph.facebook.com/v2.10/' . $groupid . '_' . $postID . '/reactions?summary=total_count&access_token=' . $Token;
    $timlike       = json_decode(get_info_by_curl($demlike));
    $TKLike         = $timlike->summary->total_count;
    //Lấy số cmt
    $dembl        = 'https://graph.facebook.com/v2.10/' . $groupid . '_' . $postID . '/comments?summary=total_count&access_token=' . $Token;
    $timbl        = json_decode(get_info_by_curl($dembl));
    $TKBL         = $timbl->summary->total_count;

    //Kiểm tra cảm xúc bài viết
    $LikeApi = 'https://graph.facebook.com/v2.10/' . $groupid . '_' . $postID . '/reactions?fields=id&pretty=0&live_filter=no_filter&limit=10000&access_token=' . $Token;
    $FindLike = get_info_by_curl($LikeApi);
    if (strpos($FindLike, $userID) !== false) {
        $Liked = true;
    }

    //Kiểm tra share
    $Share = 'https://graph.facebook.com/v2.10/' . $groupid . '_' . $postID . '/reactions?fields=id&pretty=0&live_filter=no_filter&limit=10000&access_token=' . $Token;
    $FindShare = get_info_by_curl($LikeApi);
    if (strpos($FindLike, $userID) !== false) {
        $Shared = true;
    }

    //Kiểm tra thành viên comment chưa
    $CmtApi  = 'https://graph.facebook.com/v2.10/' . $groupid . '_' . $postID . '/comments?limit=10000&fields=from&access_token=' . $Token;
    $FindCmt = get_info_by_curl($CmtApi);
    if (strpos($FindCmt, $userID) !== false) {
        $Comments = true;
    }

}



$sHash = addslashes($_GET['hash']);

$CheckHash = $db->query("SELECT * FROM `link` WHERE `Hash` = '$sHash' LIMIT 1");
$wap       = $CheckHash->fetch();
//Check member group
$app_scoped_user_id = $_SESSION['facebook_user_name'];
$check_mem          = $db->query("SELECT COUNT(*) FROM `member` WHERE `app_scoped_user_id` = '$app_scoped_user_id'");
$mem = $check_mem->fetch();
if($mem["COUNT(*)"]!=0){
  $Mem=true;
}

if ($wap['Hash'] == $sHash) {
    // Hàm Kiểm Tra Mật Khẩu
    $userID      = $_SESSION['facebook_user_id'];
    $userName    = $_SESSION['facebook_user_name'];
    if (isset($wap['Password'])) {
        if ($wap['Password'] !== "") {
            $PasswordLocked = true;
            if (isset($_POST['password'])) {
                if (md5($_POST['password']) == $wap['Password']) {
                    unset($PasswordLocked);
                }
            } else {
                $Password = null;
            }
        }
    }


    $FoundPostID = $wap['PostID'];
    if ($FoundPostID == 0) {
        FoundPost($groupid, $accessToken, $wap['Hash']);
        $db->exec("UPDATE `link` SET `PostID` = '$FoundPostID' WHERE `Hash` = '$Hashtag'");
    }

    PostInfo($groupid, $FoundPostID, $accessToken, $userID);
    $linkCreator = ($userID == $wap['FBID']) ? true : false;

}




?>
<?php

if ($linkCreator == true || (isset($Mem) && isset($Comments) && isset($Liked) && empty($PasswordLocked))) {
?>
 <div class="row mb-4">
    <div class="col-md-12">
                            <div class="card">
                                <div class="card-block">
                                    <h5 class="card-title mb-4">UnLock Protect Link</h5><hr>
                  <p class="text-center text-danger"> Link Bài Viết Đã Được Mở Khóa</p>
                  <i class="fa fa-link fa-spin" aria-hidden="true"></i> Link Khóa : <center><a class="text-info" href="<?php
    echo $wap['Url'];
?>"><?php
    echo $wap['Url'];
?></a></center>
                                </div>
                            </div>
                        </div>
</div>
<?php
}
?>
 <div class="row">
    <div class="col-md-4">
                            <div class="card">
                                <div class="card-block">
                                    <h5 class="card-title mb-4">Thành Viên Đăng Bài</h5><hr>
                                    <div class="text-center">
                                        <img src="https://graph.facebook.com/<?php
echo $wap['FBID'];
?>/picture?type=large&redirect=true&width=82&height=82" alt="<?php
echo $Nguoitao;
?>" class="rounded-circle" width="100" height="100" />
                                    </div><br />
                                    <p class="card-text">Người chia sẻ : <a href="https://facebook.com/<?php
echo $wap['FBID'];
?>" target="_blanks"><b><?php
echo $Nguoitao;
?></b></a></p>
                                    <hr><p class="card-text">ID : <b><?php
echo $wap['FBID'];
?></b></p>
                                </div>
                            </div>
                        </div>

  <div class="col-md-8">
                            <div class="card">
                                <div class="card-block">
                                    <h5 class="card-title mb-4">Thông Tin Chung</h5><hr>
                                    <h6 class="card-title mb-4">Link Protect</h6>
                  <p class="text-center"><a class="text-info" href="<?php
echo ($FoundPostURL !== null) ? $FoundPostURL : '#';
?>"><?php
echo ($FoundPostURL !== null) ? $FoundPostURL : 'Link khóa này chưa được cập nhật link bài viết trong Group';
?></a></p>
<hr >
<div class="row">
<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 mb-6">
                            <div class="card">
                                <div class="card-block">
                                    <h4 class="card-title font-weight-normal text-danger"><?php
echo '' . $TKLike . '';
?></h4>
                                    <p class="card-text"><i class="fa fa-heart" aria-hidden="true"></i> Lượt Thích Bài Viết</p>
                                </div>
                            </div>
                        </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 mb-6">
                            <div class="card">
                                <div class="card-block">
                                    <h4 class="card-title font-weight-normal text-danger"><?php
echo '' . $TKBL . '';
?></h4>
                                    <p class="card-text"><i class="fa fa-comments" aria-hidden="true"></i> Lượt Bình Luận Bài Viết</p>
                                </div>
                            </div>
                        </div>
            </div><hr>
<h6 class="card-title mb-4">UnLock Protect</h6>
<?php
if ($Mem == true) {
    echo '<p class="text-success"><i class="fa fa-check-square-o" aria-hidden="true"></i> Xác Nhận Thành Viên Thành Công</p>';
} else {
    echo '<p class="text-danger"><i class="fa fa-times-circle-o" aria-hidden="true"></i> Bạn Không Phải Là Thành Viên Của Nhóm Hoặc Chưa Xác Nhận ! Xác nhận <a href="/verify">Tại đây</a></p>';
}
if ($Comments == true) {
    echo '<p class="text-success"><i class="fa fa-check-square-o" aria-hidden="true"></i> Bạn Đã Bình Luận Bài Viết Này</p>';
} else {
    echo '<p class="text-danger"><i class="fa fa-times-circle-o" aria-hidden="true"></i> Bạn Chưa Bình Luận Bài Viết Này !</p>';
}
if ($Liked == true) {
    echo '<p class="text-success"><i class="fa fa-check-square-o" aria-hidden="true"></i> Bạn đã thích bài viết của liên kết này</p>';
} else {
    echo '<p class="text-danger"><i class="fa fa-times-circle-o" aria-hidden="true"></i> Bạn chưa thích bài viết của liên kết này</p>';
}
if ($Shared == true) {
    echo '<p class="text-success"><i class="fa fa-check-square-o" aria-hidden="true"></i> Bạn đã chia sẻ bài viết của liên kết này</p>';
} else {
    echo '<p class="text-danger"><i class="fa fa-times-circle-o" aria-hidden="true"></i> Bạn chưa chia sẻ bài viết của liên kết này</p>';
}
if (empty($PasswordLocked)) {
    echo '<p class="text-success"><i class="fa fa-check-square-o" aria-hidden="true"></i> Bạn đã mở khoá mật khẩu</p>';
} else {
    echo '<p class="text-danger"><i class="fa fa-times-circle-o" aria-hidden="true"></i> Liên kết này có mật khẩu, hãy điền mật khẩu để mở khóa</p>';
}

if (isset($PasswordLocked)) {
?>
<form action="" method="POST">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="text" name="password" class="form-control p_input" placeholder="Password"> &#160;<button type="submit" class="btn btn-primary">Mở Khóa</button>
                  </div>
                </div>
              </form>
                                </div>
                            </div>
                        </div>
</div>
<?php
}
ob_end_flush();
require('inc/end.php');
?>
