<html>
<body>

<form action='change.php' method='get'>
<?php 

ini_set('display_errors', 'On');
error_reporting(E_ALL);

require 'vendor/autoload.php';

require 'auth.php';

$session = new SpotifyWebAPI\Session(
    $spotify_id,
    $spotify_secret,
    $home_url."add_album.php"
);

if (isset($_GET['code'])) {
    $session->requestAccessToken($_GET['code']);
    $token = $session->getAccessToken();
    echo '<input type="hidden" name="token" value="'.$token.'">';
} else {
    $options = [

    ];

    header('Location: ' . $session->getAuthorizeUrl($options));
    die();
}		
?>

Rank: <input type="text" name="rank"> </br>
URI: <input type="text" name="url"/> </br>
<input type="submit"/>
</form>
</body>
</html>
