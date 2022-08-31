<html>
<body>
<?php 
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$rank = $_GET["rank"];
$id = $_GET["id"];


require 'simple_html_dom.php';

require "auth.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} 

$result = $conn->query("SELECT `RANK`, `NAME`, `ARTIST` FROM `albums` WHERE `ID`=".$id.";");

if (!$result) {
    die("Result does not exist");
} else {
    //entry already exists
    $info = $result->fetch_assoc();
    $name = $info['NAME'];
    $artist= $info['ARTIST'];
    $old_rank = $info['RANK'];
    file_put_contents("changelog.txt", date("Y-m-d"). " > SHIFT $name ($artist) FROM $old_rank TO $rank \n", FILE_APPEND | LOCK_EX);
    mysqli_report(MYSQLI_REPORT_ALL);
    if ($rank=="delete"){
        $delete_query = "delete from `albums` where `ID` = ".$id.";";
        $shift_query = "update `albums` 
            set `RANK`=`RANK`-1
            where `RANK` > ".strval($old_rank).";";
        
        if ($conn->multi_query($delete_query.$shift_query)) {
            echo "Entry deleted";
        } else {
            die("Query failed");
        };

    } else {
        if ($rank>$old_rank){
            //This album is growing on me!
            $shift_query = "update `albums` 
                set `RANK`=`RANK`-1
                where `RANK` > ".strval($old_rank)." and `RANK` <= ".strval($rank).";";
        } else {
            //Eh... losing interest
            $shift_query = "update `albums` 
                set `RANK`=`RANK`+1
                where `RANK` >= ".strval($rank)." and `RANK` < ".strval($old_rank).";";
        };
        $query = $shift_query."update `albums` set `RANK`=".strval($rank)." where `ID` = ".$id.";";
        if ($conn->multi_query($query)) {
            echo "Entry Changed";
        } else {
            die("Query failed");
        };
    };
    

};
		

//Backup
shell_exec("mysqldump --user=".$username." --password=".$password." --host=".$servername." ".$dbname." > \"bkups/\$(date).sql\" 2>&1");
$conn->close();




?>
</body>
</html>
