<?php
 session_start();
 define('Hadpro', 1);
 define('verify', 1);
 require('inc/head.php');

 $app_scoped_user_id=$_SESSION['facebook_user_id'];

 $user_name = $_SESSION['facebook_user_name'];

 $check_mem = $db->query("SELECT COUNT(*) FROM `member` WHERE `app_scoped_user_id` = '$app_scoped_user_id'");

 $mem = $check_mem->fetch();

 if($mem["COUNT(*)"]!=0){

echo
 '<div class="row">
    <div class="col-lg-6 ">
        <div class="card">
            <div class="card-block">
                <div class="alert alert-danger"><strong>Bạn đã là thành viên trong group </strong>
                </div>
            </div>
        </div>
    </div>
</div>';

 exit();
 }


 if(isset($_POST["url"])){

 	$id_cmt = preg_replace("/(.*)(=)(.*?)(&)(.*)/", "$3", $_POST["url"]);

    $url_check= "https://graph.facebook.com/v2.9/".$id_bv."_".$id_cmt."/?access_token=".$token_admin;

    $info=json_decode(get_info_by_curl($url_check),true);

    if ($info['from']["id"] !=null && $info['from']["name"]==$user_name && $info["message"]== $app_scoped_user_id) {

        $user_id=$info['from']["id"];
    	$db->query("INSERT INTO `member` VALUES (NULL,0, '$user_id', '$app_scoped_user_id', '$user_name')");

        echo
    '<div class="row">
    <div class="col-lg-6 ">
        <div class="card">
            <div class="card-block">
                <div class="alert alert-danger"><strong>Bạn đã là thành viên trong group </strong>
                </div>
            </div>
        </div>
    </div>
    </div>';

     exit();


    }

 }


echo
'<div class="row">
    <div class="col-lg-6 ">
        <div class="card">
            <div class="card-block">
                <div class="ibox-title">
                    <h5><i class="fa fa-user"></i> Xác Nhận Thành Viên thuthuatcasio2018 Community</h5>
                </div>
                <div class="ibox-content">
                    <form action="" role="form" id="form" method="POST" accept-charset="utf-8">
                        <div class="form-group">
                            <label for="code">Mã xác nhận của bạn</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-heart" aria-hidden="true"></i>
                                </div>
                                <input type="text" class="form-control" id="code" name="code" value="'.$app_scoped_user_id.'" readonly="">
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="url">Nhập liên kết tới bình luận có mã xác nhận của bạn</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-link" aria-hidden="true"></i>
                                </div>
                                <input value="" type="url" class="form-control" id="url" name="url" placeholder="https://www.facebook.com/groups/thuthuatcasio2018/permalink/461704614226429/?comment_id=XXX" autofocus="" required="" autocomplete="off">
                                <span class="input-group-btn">
                                        <button type="submit" class="btn btn-success" id="submitBtn" data-loading-text="Loading..."><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Kích hoạt</button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <h3>Hướng dẫn xác nhận trong 2 bước</h3>
                <ol>
                    <li>Copy mã xác nhận phía trên và bình luận vào <a href="https://www.facebook.com/'.$id_bv.'" target="_blank" style="font-size: 18px;"><strong>bài viết này</strong></a>.</li>
                    <li>Copy liên kết dẫn tới bình luận của bạn và paste vào trên. <a href="#">Xem hướng dẫn!</a>
                    </li>
                </ol>
                <div class="alert alert-danger">
                    <strong>LƯU Ý:</strong> Comment ID vào Bài viết Verify trong Group
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6" id="help">
        <div class="card">
            <div class="card-block">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-user"></i> Hướng Dẫn Xác Nhận</h5>
                    </div>
                    <div class="ibox-content">
                        <p>
                            Vào bài viết Xác Thực trong Group</br>
                            Comment ID vào Bài Viết</br>
                            Click chuột phải vào thời gian bình luận và chọn Sao chép liên kết.</br>
                            Dán liên kết vào khung. </br>
                            Nhấn Kích hoạt để xác minh bạn là thành viên của Group
                        </p>
                        <center><img class="img-responsive" src="/help.gif"></img>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>';
