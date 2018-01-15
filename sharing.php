<?php 
 date_default_timezone_set("Asia/Karachi");
$page_access_token = 'EAAVBunH8H9cBAKSjswD1g2nbUdR2xOZAeBSQy5huD78PZAA6l2Snb8vMFHXURHEiS3SdK7sWGcsKnKAdJ6w3iagF9BVzR1RJZAE4Fi0OyPzZAl34x6NzOOezfgewdRuqLZCZAOzVslW0qj11ZCinP2RSZA89Ipb5ZAY6bINKyzLBUZAwZDZD';
$page_id = '139390179578335';
$data['picture'] = "https://www.petefreitag.com/images/pete-freitag-medium.jpg";
$data['link'] = "https://www.petefreitag.com/images/pete-freitag-medium.jpg";
$data['message'] = "hi testing message";
$data['caption'] = "Caption";
$data['description'] = "this is testing desc";
$data['access_token'] = $page_access_token;
$data['published']=false;
echo date("Y-m-d h:m:s");
$epochTime = strtotime("2017-11-10 06:29:00");
$data['scheduled_publish_time'] = $epochTime;


$post_url = 'https://graph.facebook.com/'.$page_id.'/feed';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $post_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$return = curl_exec($ch);
print_r($return);
// Check for errors and display the error message
if($errno = curl_errno($ch)) {
    $error_message = curl_strerror($errno);
    echo "cURL error ({$errno}):\n {$error_message}";
}

curl_close($ch);
?>
