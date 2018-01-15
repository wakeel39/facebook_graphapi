<?php 
if(!session_id()) {
    session_start();
}
require_once __DIR__ . '/vendor/autoload.php'; 
$fb = new Facebook\Facebook([
  'app_id' => '1479643915624407', // Replace {app-id} with your app id
  'app_secret' => 'bcbdfe909b17ee0ff5b8fae004075dbe',
  'default_graph_version' => 'v2.2',
  'persistent_data_handler'=>'session'
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email','manage_pages','user_managed_groups']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://localhost/fb/fb-callback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';

?>