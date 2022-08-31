<html>
	<head>
		<title>Stuart's Favorite Albums</title>
		<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <style type="text/css">
        .info_edit {
            display: none;
        }
        </style>
		<script type='text/javascript'>
			function flip(element) {
				if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
 					var containers = document.getElementsByClassName('flip-container');
					for (var i = 0; i < containers.length; i++) { 
						if (containers[i]!=element) {
  							containers[i].classList.remove('flip-container-hover');
  						};
  					};
					element.classList.toggle('flip-container-hover');
				}
			}
		</script>
        <link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
	
		<?php
		ini_set('display_errors', 'On');
		error_reporting(E_ALL);
		
        require "auth.php";
		
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$columns = array('ARTWORK','RANK','NAME','ARTIST','YEAR','URL');
	
		$query = 'SELECT '.join(',', $columns).' FROM `albums` ORDER BY `RANK` ASC;';
		$result = $conn->query($query);
		
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$album_html = file_get_contents('album_template.html');
				foreach ($columns as $column) {
					$album_html = str_replace('{'.$column.'}', $row[$column], $album_html);
				}
				
				echo $album_html;
			}
		} else {
			echo "0 results";
		}
		$conn->close();
		?>

		<!---<div class="flip-container" ontouchstart="this.classList.toggle('hover');">
			<div class="flipper">
				<div class="front" style="background:url(//i.scdn.co/image/8ea837cf2ca0b37a4d966be9535f31239709d32f)  0 0 no-repeat;">
				</div>
				<div class="back" style="background:#404040;">
					<div class="info_container">
						<div class="info_rank">#1</div>
						<div class="info_name">All Delighted People</div>
						<div class="info_artist">Sufjan Stevens</div>
						<div class="info_year">2010</div>
						<div class="info_spotify"><a href="https://open.spotify.com/album/79aBySIboEq6ZcQ7bcq5me">link</a></div>
					</div>
				</div>
			</div>
		</div>--->
	</body>

</html>
