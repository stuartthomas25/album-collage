<html>
<body>
<?php 
ini_set('display_errors', 'On');
error_reporting(E_ALL);

require 'vendor/autoload.php';

$api = new SpotifyWebAPI\SpotifyWebAPI();

$api->setAccessToken($_GET['token']);

$raw_id = $_GET["url"];
$pattern = "/album\/(.{22})/i";
preg_match($pattern, $raw_id, $matches);
$id = $matches[1];
# spotify:album:51WLEfPEEkzAWurvuY6Gco
echo "id: $id </br>";

$album = $api->getAlbum($id);

$name = $album->name;
$artist = $album->artists[0]->name;
$year = substr($album->release_date, 0, 4);
$url = $album->external_urls->spotify;
$artwork = $album->images[1]->url;

$rank = (int)$_GET["rank"]; 

require "auth.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} 

$result = $conn->query("SELECT `RANK` FROM `albums` WHERE `URL`='".$id."';");

if ($result->num_rows > 0) {
   die('Album already exists');
} else {
	//new entry
    file_put_contents("changelog.txt", date("Y-m-d") . " > ADD $name ($artist) TO $rank \n", FILE_APPEND | LOCK_EX);
	$conn->query("update `albums` 
		set `RANK`=`RANK`+1
		where `RANK` >= ".strval($rank)."
		ORDER BY `RANK` DESC;");
	$query="
	INSERT
	INTO
		`albums`(
			`RANK`,
			`NAME`,
			`ARTIST`,
			`ARTWORK`,
			`YEAR`,
			`URL`
		)
	VALUES(
		'".strval($rank)."',
		'".mysqli_real_escape_string($conn, $name)."',
		'".mysqli_real_escape_string($conn, $artist)."',
		'".mysqli_real_escape_string($conn, $artwork)."',
		'".mysqli_real_escape_string($conn, $year)."',
		'".mysqli_real_escape_string($conn, $id)."'
	);
	
	";
	//echo $query;
	$conn->query($query);
	echo "Added New Entry";
};
		
//Backup
shell_exec("mysqldump --user=".$username." --password=".$password." --host=".$servername." ".$dbname." > \"bkups/\$(date).sql\" 2>&1");
$conn->close();



?>
</body>
</html>
