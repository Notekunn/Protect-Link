<?php
session_start();
require('db.php');
require('PseudoCrypt.php');
switch($al){
	case 'taolink' :
if(empty($accessToken)){
			if(isset($_POST['formsubmit'])){

			function userID($Token){
				$ProfileApi = 'https://graph.facebook.com/me?access_token='.$Token;
				$user = json_decode(get_info_by_curl($ProfileApi, true));
				return $user->id;
			}

			$EncodeLink = PseudoCrypt::hash(time(), 10);
			$LockedLink = WEBURL.'/x/'.$EncodeLink;
			$HashLink = '#protect@'.$EncodeLink.'@';

			$longUrl = $LockedLink;
			$apiKey  = $GoogleApiKey;

			$postData = array('longUrl' => $longUrl);
			$jsonData = json_encode($postData);

			$curlObj = curl_init();

			curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey);
			curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curlObj, CURLOPT_HEADER, 0);
			curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
			curl_setopt($curlObj, CURLOPT_POST, 1);
			curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

			$reply = curl_exec($curlObj);

			$json = json_decode($reply);

			curl_close($curlObj);

			if(isset($json->error)){
			echo $json->error->message;
			}else{
				$GoogleShortUrl = $json->id;
			}

			$db->exec("INSERT INTO `link` SET `FBID` = '".$_SESSION['facebook_access_token']."',`Hash` = '$EncodeLink', `Password` = '{$_POST['pass']}', `Url` = '{$_POST['link']}', `SUrl` = '$GoogleShortUrl', `Time` = '".date("Y-m-d H:i:s")."'");
			}
		}
	 echo '<div class="alert alert-info" style="color:blue" role="alert">
         <strong>*Lưu ý:</strong><br>
           - Khi post bài trong Group bạn phải kèm theo <span class="label label-danger">Hash của bài viết</span> Có thể để ở bất cứ đâu trong bài viết để tool có thể tự cập nhật link bài viết cho bạn.<br>
           - Nếu link của bài viết không tự cập nhật. Bạn vui lòng vào mục <a href="catnhat.html" <span="" class="label label-danger">Cập nhật link</a>
           - Nếu link bài viết không được cập nhật thì ngoài chức năng khóa mật khẩu các chức năng khác sẽ không hoạt động.
           </div>
           <strong>Link khóa &amp; Hash của bài viết:</strong>
           <br/>
           <div class="input-group">
           <input id="linktonghop" class="form-control" value="'.$GoogleShortUrl.' | '.$HashLink.'" style="width: 100%"><br>
           <span class="input-group-btn">
           <button id="copyLink" type="button" class="btn btn-info btn-fill" data-clipboard-target="#linktonghop">Copy</button>
          </span>
        </div>';
	break;
}
// Het Switch
if(isset($accessToken)){
$sHash = $_GET['x'];

$CheckHash = $db->query("SELECT * FROM `link` WHERE `Hash` = '$sHash'");
$URL = $CheckHash->fetchAll();
if(isset($URL['Password'])){
  if(isset($_POST['password'])){
    if($_POST['password'] == $URL['Password']){
      $Password = true;
    } else {
      $Password = false;
    }
  }
}

if(empty($Password)){
  $Password = true;
}

function FacebookName($Token, $ID){
	$ProfileApi = 'https://graph.facebook.com/me?access_token='.$Token;
	$user = json_decode(file_get_contents($ProfileApi, true));
  return $user->name;
}

function userID($Token){
  global $userID, $userName, $Link;
	$ProfileApi = 'https://graph.facebook.com/me?access_token='.$Token;
	$user = json_decode(file_get_contents($ProfileApi, true));
	$userID = $user->id;
  $userName = $user->name;
  $Link     = $user->link;
}

function Fanpage($PageID, $Token, $Hashtag, $PostID, $userID){
	global $FoundPost, $FoundPostID, $FoundPostURL, $Liked;
	//Feed (timeline) data
	$FeedApi = 'https://graph.facebook.com/'.$PageID.'/feed?limit=100&access_token='.$Token;
	$FeedJson = json_decode(file_get_contents($FeedApi), true);

	if(is_array($FeedJson) or is_object($FeedJson)){
		foreach($FeedJson['data'] as &$feed) {
			if(strpos(@$feed['message'], $Hashtag) !== FALSE) {
				$FoundPost = true;
				$FoundPostID = str_replace($PageID.'_', '',$feed['id']);

				//Get info Post
				$PostApi = 'https://graph.facebook.com/v2.10/'.$PageID.'_'.$FoundPostID.'?fields=id,permalink_url,message&access_token='.$Token;
				$PostPage = json_decode(get_info_by_curl($PostApi));

				$FoundPostURL = $PostPage->permalink_url;

				//Search user reactions ID
				$LikeApi = 'https://graph.facebook.com/v2.10/'.$PageID.'_'.$FoundPostID.'/reactions?fields=id&pretty=0&live_filter=no_filter&limit=5000&access_token='.$Token;
				$FindLike = json_decode(get_info_by_curl($LikeApi));
				foreach($FindLike->data as $like){
				if($like->id == $userID){
					$Liked = true;
					}
				}
			}
		}
	}
}

userID($accessToken);
Fanpage(PAGEID, $accessToken, $URL['Hash'], $URL['PostID'], $userID);

if($FoundPost == true && $PostID == 0){
	$db->exec("UPDATE `link` SET `PostID` = '$FoundPostID' WHERE `Hash` = '$sHash'");
}
}
