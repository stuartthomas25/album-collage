<html>
	<head>
		<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
		<style type='text/css'>
			/* entire container, keeps perspective */
			body{background-color:black;}
			.flip-container {
				perspective: 1000px;
				display: inline-block;
			}
				/* flip the pane when hovered */
				.flip-container:hover .flipper {
					transform: rotateY(180deg);
				}

			.flip-container, .front, .back {
				width: 300px;
				height: 300px;
			}

			/* flip speed goes here */
			.flipper {
				transition: 0.6s;
				transform-style: preserve-3d;

				position: relative;
			}

			/* hide back of pane during swap */
			.front, .back {
				backface-visibility: hidden;
				-webkit-backface-visibility: hidden;
				-moz-backface-visibility: hidden;
				position: absolute;
				top: 0;
				left: 0;
			}

			/* front pane, placed above back */
			.front {
				z-index: 2;
				/* for firefox 31 */
				transform: rotateY(0deg);
			}

			/* back, initially hidden pane */
			.back {
				transform: rotateY(180deg);
			}
			
			.info_container {
				text-align: center;
				font-family: 'Ubuntu', sans-serif;
			}
			.info_rank {
				margin-top: 20%;
				font-size: 25pt;
				color: gainsboro;
			}
			.info_name {
    			margin-top: 10;
   				font-size: 20pt;
   			}
   			.info_name a {
   				color: whitesmoke;
   				text-decoration: none;
			}
			.info_artist {
				margin-top: 5;
				font-size: 15pt;
				color: gainsboro;
			}
			.info_year {
    			margin-top: 2;
   				font-size: 13pt;
    			color: gainsboro;
			}
			.info_edit {
    			margin-top: 2;
   				font-size: 25pt;
    			color: black
			}
		</style>
		<script type='text/javascript'>
			function flip(element) {
				var containers = document.getElementsByClassName('flip-container');
				for (i = 0; i < containers.length; i++) {
					containers[i].classList.remove("hover");
				};
				element.classList.add('hover');
			}
			function edit(id) {
				var new_rank = prompt("Insert new rank (or 'delete' to remove album)");
				if (new_rank != null) {
					const Http = new XMLHttpRequest();
					const url='../shift.php?id='+id.toString()+'&rank='+new_rank;
					Http.open("GET", url,false);
					//alert(url);
					Http.send();
					location.reload()
				};
			}	
		</script>
	</head>
	<body>
	
		<?php
		ini_set('display_errors', 'On');
		error_reporting(E_ALL);
		
        require "../auth.php";
		
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$columns = array('ID','ARTWORK','RANK','NAME','ARTIST','YEAR','URL');
	
		$query = 'SELECT '.join(',', $columns).' FROM `albums` ORDER BY `RANK` ASC;';
		$result = $conn->query($query);
		
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$album_html = file_get_contents('../album_template.html');
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
