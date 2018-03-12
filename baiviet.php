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
  require('inc/head.php');
  date_default_timezone_set('America/New_York');
  function facebook_time_ago($timestamp)
  {
      $time_ago        = strtotime($timestamp);
      $current_time    = time();
      $time_difference = $current_time - $time_ago;
      $seconds         = $time_difference;
      $minutes         = round($seconds / 60); // value 60 is seconds
      $hours           = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec
      $days            = round($seconds / 86400); //86400 = 24 * 60 * 60;
      $weeks           = round($seconds / 604800); // 7*24*60*60;
      $months          = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60
      $years           = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60
      if ($seconds <= 60) {
          return "Vừa Xong";
      } else if ($minutes <= 60) {
          if ($minutes == 1) {
              return "Một Phút Trước";
          } else {
              return "$minutes Phút Trước";
          }
      } else if ($hours <= 24) {
          if ($hours == 1) {
              return "Một Giờ Trước";
          } else {
              return "$hours Giờ Trước";
          }
      } else if ($days <= 7) {
          if ($days == 1) {
              return "Hôm Qua";
          } else {
              return "$days Ngày Trước";
          }
      } else if ($weeks <= 4.3) //4.3 == 52/12
          {
          if ($weeks == 1) {
              return "Một Tuần Trước";
          } else {
              return "$weeks Tuần Trước";
          }
      } else if ($months <= 12) {
          if ($months == 1) {
              return "Một Tháng Trước";
          } else {
              return "$months Tháng Trước";
          }
      } else {
          if ($years == 1) {
              return "Một Năm Trước";
          } else {
              return "$years Năm Trước";
          }
      }
  }

  if (isset($accessToken)) {
      $MemberApi = 'https://graph.facebook.com/v2.10/' . $groupid . '/feed?limit=100&access_token=' . $accessToken;
      $FindMem   = json_decode(file_get_contents($MemberApi), true);
      foreach ($FindMem['data'] as &$member) {
          $FoundPostID = str_replace($groupid . '_', '', $member['id']);
          // Người Tạo Bài Viết
          $userapi     = 'https://graph.facebook.com/v2.10/' . $groupid . '_' . $FoundPostID . '?fields=from,message,created_time,permalink_url,updated_time&access_token=' . $accessToken;
          $Postuser    = json_decode(file_get_contents($userapi));
          $Nguoitao    = $Postuser->from->name;
          $idtao       = $Postuser->from->id;
          $ten         = $Postuser->message;
          $time        = $Postuser->created_time;
          $timeupdate  = $Postuser->updated_time;
          $linkbv      = $Postuser->permalink_url;
          // Thống Kê Like Bài Viết
          $demlike     = 'https://graph.facebook.com/v2.10/' . $groupid . '_' . $FoundPostID . '/likes?summary=total_count&access_token=' . $accessToken;
          $timdl       = json_decode(file_get_contents($demlike));
          $TKLike      = $timdl->summary->total_count;
          // Thống Kê Bình Luận Bài Viết
          $dembl       = 'https://graph.facebook.com/v2.10/' . $groupid . '_' . $FoundPostID . '/comments?summary=total_count&access_token=' . $accessToken;
          $timbl       = json_decode(file_get_contents($dembl));
          $TKBL        = $timbl->summary->total_count;
?>
                <div class="row mb-4">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="card">
                                <div class="card-block">
                 <div class="row mb-4">
                  <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2">
                                    <img src="https://graph.facebook.com/<?php
          echo $idtao;
?>/picture?type=large&redirect=true&width=120&height=120" alt="" class="img-thumbnail" />
                  </div><div class="col-xl-10 col-lg-10 col-md-10 col-sm-10">
                  <p class="card-title"><i class="fa fa-user-circle-o"></i> Member Post : <a href="http://facebook.com/<?php
          echo $idtao;
?>"><?php
          echo '' . $Nguoitao . '';
?></a></p>
                  <p class="card-title"><i class="fa fa-times-circle-o" aria-hidden="true"></i> Time : <?php
          echo facebook_time_ago($time);
?></p>
                  <p class="card-text">Likes : <?php
          echo $TKLike;
?> <i class="fa fa-heart text-danger" aria-hidden="true"></i> | Comments : <?php
          echo $TKBL;
?> <i class="fa fa-comments text-danger" aria-hidden="true"></i></p>
                  </div>
                  </div><hr>
                  <p class="card-text"><?php
          echo '' . $ten . '';
?></p>
                  <hr>
                  <div class>
                  <a target="_blanks" href="<?php
          echo $linkbv;
?>"><button class="btn btn-primary"><i class="fa fa-send"></i> Xem Bài Trên Facebook</button></a>
                                   </div>
                </div>
                            </div>
          </div>
          </div>
          <?php
      }
  }
  require('inc/end.php');
?>
