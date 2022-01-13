<html>
<body>
<?php
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);

    require 'vendor/autoload.php';

    require 'auth.php';

    $session = new SpotifyWebAPI\Session(
        $spotify_id,
        $spotify_secret,
        $spotify_redirect
    );

    $api = new SpotifyWebAPI\SpotifyWebAPI();

    if (isset($_GET['code'])) {
        $session->requestAccessToken($_GET['code']);
        $api->setAccessToken($session->getAccessToken());

        # do the Lord's work here:
        #
        #
        $album = $api->getAlbum("51WLEfPEEkzAWurvuY6Gco");

        $name = $album->name;
        $artist = $album->artists[0]->name;
        $year = $album->release_date;
        $link = $album->external_urls->spotify;
        $image = $album->images[0]->url;

        echo $name;
        echo $artist;
        echo $year;
        echo $link;
        echo $image;
    } else {
        $options = [
            'scope' => [
                'user-read-email',
            ],
        ];

        header('Location: ' . $session->getAuthorizeUrl($options));
        die();
    }
?>
<body>
<html>
