<html>
<body>

<form action='change.php' method='get'>
<?php 

ini_set('display_errors', 'On');
error_reporting(E_ALL);

require 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
    '47d3780c8c9242b585d9b2e9fec240b5',
    '4fffe285204a4d98a1bc674fcc9198d8',
    'http://files.stuartthomas.us/albums/add_album.php'
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
