<?php
    $myPDO = new PDO('sqlite:sqlite3/AEdatabase.db');
?>
<html>
	<head>
		<meta charset=utf-8>
		<title>WebVr Animal Encyclopedia</title>
		<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>-->
		<script src="https://aframe.io/releases/0.7.0/aframe.min.js"></script>
		<script src="https://rawgit.com/feiss/aframe-environment-component/master/dist/aframe-environment-component.min.js"></script>
		<script src="https://rawgit.com/chenzlabs/auto-detect-controllers/master/dist/aframe-auto-detect-controllers-component.min.js"></script>
		<script src="https://unpkg.com/aframe-text-geometry-component@^0.5.0/dist/aframe-text-geometry-component.min.js"></script>
    	<script src="js/aframe-extras.js"></script>
    	<script src="js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript">
			//function called to open the sidebar

			function togNav() {
				console.log(document.getElementById("mySidenav").style.width);
				if (document.getElementById("mySidenav").style.width!="0px"){
					document.getElementById("mySidenav").style.width = "0";
			    	document.getElementById("mySidenav").style.boxShadow = "none";
				}else{
					document.getElementById("mySidenav").style.width = "25vw";
			    	document.getElementById("mySidenav").style.boxShadow = "5px 0px 30px 10px rgba(0, 0, 0, .7)";
				}
			}

			function openNav() {
			    document.getElementById("mySidenav").style.width = "250px";
			    document.getElementById("mySidenav").style.boxShadow = "5px 0px 30px 10px rgba(0, 0, 0, .7)";
			}

			//called to close the sidebar
			function closeNav() {
			    document.getElementById("mySidenav").style.width = "0";
			    document.getElementById("mySidenav").style.boxShadow = "none";
			}

			//resets the navigation bar (empties the search bar, hides the blocks and shows the collapsible lists)
			function resetNav(clear) {

				//shows the collapsible lists
				document.getElementById("v_li").style.display='block';
				document.getElementById("i_li").style.display='block'; 

				//declares an "array" which contains the list of animals
				var elems=document.getElementById("all_list").getElementsByTagName("li");
				
				//sets display of all the <li> elements from the above array to none (hides them)
				for (var i=0;i<elems.length;i+=1){
					elems[i].style.display = 'none';
				}

				//clears search bar when the argument passed is "true"
				if (clear){
					document.getElementById("searchBar").value="";					
				}

			}

			//function which updates the results list as the user types (called on keyUp of the <input> element)
			function updateList() {

				n=event.keyCode;

				var input=document.getElementById("searchBar").value;
				
				if (input==""){
					resetNav(false);
					return;
				}
				document.getElementById("v_li").style.display='none';
				document.getElementById("i_li").style.display='none'; 
					
				var elems=document.getElementById("all_list").getElementsByTagName("li");
					
				for (var i=0;i<elems.length;i+=1){

				  elems[i].style.display = 'none';
					
				}



				$.ajax({
					url: 'php_process.php',
					type: 'GET',
					data: { input: input },
					success: function(data) {

						var list=JSON.parse(data);
						

							
						if (list.length!=0){
							for (i=0;i<list.length;i++){

								document.getElementById(list[i]).style.display='block';
								
							}

						}else{
							resetNav(false);							
							if (n == 13){
								alert("No results match the search request");
								document.getElementById("searchBar").value="";

							}

						}

					}
				});

			}

			function showAnimal(filepath, name, scientificName, diet, category){

				//calls the js script which has the code to display the animal in the correct environment
				$.getScript('models-js/'+filepath.toLowerCase(), function(){
					document.querySelector('#vr-name').setAttribute('text-geometry', 'value', name.toUpperCase());
					document.querySelector('#vr-s-name').setAttribute('text-geometry', 'value', "Scientific Name: "+scientificName);
					document.querySelector('#vr-diet').setAttribute('text-geometry', 'value', "Diet: "+diet);
					document.querySelector('#vr-category').setAttribute('text-geometry', 'value', "Category: "+category);
				});

				closeNav();
				resetNav(true);

			}
		</script>
		<style type="text/css">
		/*canvas {
			width: $(window).width() * 0.90;
			height: $(window).height * 0.90;
			background
		}
		body {
		    font-family: "Lato", sans-serif;
		    width: 100%;
		}*/
		canvas {
			width: $(window).width() * 0.90;
			height: $(window).height * 0.90;
		}

		body {
			overflow: hidden;
		}

		*{padding:0; margin:0;}

		.topnav {
		  overflow: hidden;
		  position:sticky;
		  background-color: #0f1239;
		  box-shadow: 0px 5px 30px 10px rgba(0, 0, 0, .7);
		  top:0;
		  width:100%;
		  height:12%;

		}

		.topnav a {
		  float: left;
		  color: #f2f2f2;
		  vertical-align: middle;
		  padding: 0 18px;
		  text-decoration: none;
		}

		#title {
			margin: 0 auto;
		}

		#homeScreen {
			padding: 12px 12px 12px 12px;
			margin: 12px 12px 12px 12px;
		}

		.sidenav {
			height: 86%;
		    width: 25%;
		    overflow-y: auto;
		    overflow-x: hidden;
		    position: absolute;
		    z-index: 1;
		    top: $(window).height()*0.14;
		    left: 0;
		    background-color: #0f1239;
		    transition: 0.5s;
		    padding-top: 10px;
		}

		.sidenav a {
		    text-decoration: none;
		    color: #ecf2ff;
		    display: block;
		    transition: 0.3s;
		}

		.sidenav a:hover {
		    color: #f1f1f1;
		    background-color: #1c1e4e;
		}

		.main_nav_ul a {
			margin:0;
			text-decoration: none;
			display: block;
			padding: 10px 0 10px 20px;
			border-bottom:1px grey;
			font-size: 3.8vh;
		}

		/*.sidenav li {list-style: none;}*/
		.sidenav {width: 0px; margin: 0;}

		.main_nav_ul ul {display: none;}
		.main_nav_ul li:tap ul {display: block;}
		.sidenav .sub-arrow:after {
			content: '\203A';
			float: right;
			margin-right: 20px;
			transform: rotate(90deg);
			-webkit-transform: rotate(90deg);
			-moz-transform: rotate(90deg);			
		}
		.sidenav .sub-arrow1:after {
			content: '\203A';
			float: right;
			margin-right: 20px;
			transform: rotate(90deg);
			-webkit-transform: rotate(90deg);
			-moz-transform: rotate(90deg);			
		}
		.v_sub li a {
			margin-left: 25px;
			font-size: 3.4vh;
		}

		.i_sub li a {
			margin-left: 25px;
			font-size: 3.4vh;
		}

		.all li {
			padding: 10px 10px 10px 10px;
			display:none;
		}

		.all li a {
			padding: 10px 10px 10px 10px;
		}

		.lbl { 
			color:#fa0;
			font-size:2.6vh;
			font-weight:bold;
			position: absolute;
			bottom:5%; z-index:100;
			text-shadow:#000 3px 3px 3px;
			background-color:rgba(0,0,0,0.3);
			padding:1em;
			display: none;
		}
		
		#container {
			height: 88%;
			width: 100%;
			position: relative;
			margin-top:$(window).height()*0.14;
		    left: 0;
		}

		#info_display { text-align:left; right:0px }
		.c { color:#fff }
		.n { color:#fff }
		.s { color:#fff }
		.d { color:#fff }

		/*@media screen and (max-height: 450px) {
		  .sidenav {padding-top: 15px;}
		  .sidenav a {font-size: 18px;}
		}*/

		input[type=text] {
		    width: 100%;
		    box-sizing: border-box;
		    border: 2px solid #ccc;
		    border-radius: 4px;
		    font-size: 3.4vh;
		    background-color: white;
		    background-image: url('img/searchIcon.png');
		    background-size: 3.4vh  3.3vh;
		    background-position: 10px 10px; 
		    background-repeat: no-repeat;
		    padding: 12px 20px 12px 40px;
		}



		input::-webkit-calendar-picker-indicator {
		  display: none;
		}

		nav::-webkit-scrollbar {
		    width: 10px;
		}

		/* Track */
		nav::-webkit-scrollbar-track {
		    box-shadow: inset 0 0 5px grey; 
		    border-radius: 6px;
		}

		/* Handle */
		nav::-webkit-scrollbar-thumb {
		    background: #FFF; 
		    border-radius: 6px;
		}

		/* Handle on hover */
		nav::-webkit-scrollbar-thumb:hover {
		    background: rgb(220, 220, 220); 
		}


		</style>
	</head>


	<body>	

		<div class="topnav">
			<a id="threeLines" style="font-size:7vh;cursor:pointer" onclick="togNav()">&#9776;</a>
			<a><p style="font-size:6vh">Animal Encyclopedia</p>
			<p style="font-size:3vh">WebVr</p></a>
			<div class="scrollbar" id="style-1">
			  <div class="force-overflow"></div>
			</div>
		</div>

		<nav id="mySidenav" class="sidenav">
			<input id="searchBar" list="animals" type="text" name="searchBar" placeholder="Search..." onkeyup="updateList()"></input>
			<ul id="main_nav_ul" class="main_nav_ul">
			 	<!--<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>-->
				<li id="v_li"><a id="vertebrates" href="#" onclick="$(this).parent().find('ul.v_sub').toggle();">Vertebrates<span class="sub-arrow"></span></a>
					<ul class="v_sub">
				    	<li><a href="#" onclick="$(this).parent().find('ul.fish_sub').toggle();">Fishes</a>
				    		<ul class="fish_sub">
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Fish' ORDER BY Name");
					    			foreach($result as $row)
									{
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";
									}
								?>
				    		</ul>
				    	</li>
				    	<li><a href="#" onclick="$(this).parent().find('ul.amphibian_sub').toggle();">Amphibians</a>
				    		<ul class="amphibian_sub">
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Amphibian' ORDER BY Name");
					    			foreach($result as $row)
									{
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";
									}
								?>
				    		</ul>
				    	</li>
				    	<li><a href="#">Reptiles</a>
				    		<ul class="amphibian_sub">
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Reptile' ORDER BY Name");
					    			foreach($result as $row)
									{
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";
									}
								?>
				    		</ul>
				    	</li>
				    	<li><a href="#">Birds</a>
				    		<ul class="amphibian_sub">
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Bird' ORDER BY Name");
					    			foreach($result as $row)
									{
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";
									}
								?>
				    		</ul>
				    	</li>
				    	<li><a href="#" onclick="$(this).parent().find('ul.mammal_sub').toggle();">Mammals</a>
				    		<ul class="mammal_sub">
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Mammal' ORDER BY Name");
					    			foreach($result as $row)
									{
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";
									}
								?>
				    		</ul>
				    	</li>
				    </ul>
				</li>
				<li id="i_li"><a id="invertebrates" href="#" onclick="$(this).parent().find('ul.i_sub').toggle();">Invertebrates<span class="sub-arrow1"></span></a>
					<ul class="i_sub">
				    	<li><a href="#">Porifera</a>
				    		<ul class="mammal_sub">
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Porifera' ORDER BY Name");
					    			foreach($result as $row)
									{
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";
									}
								?>
				    		</ul>
				    	</li>
				    	<li><a href="#">Cnidarians</a>
				    		<ul class="mammal_sub">
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Cnidarian' ORDER BY Name");
					    			foreach($result as $row)
									{
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";
									}
								?>
				    		</ul>
				    	</li>
				    	<li><a href="#" onclick="$(this).parent().find('ul.plathyhelminthes').toggle();">Plathyhelminthes</a>
				    		<ul class="mammal_sub">
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Platyhelminthes' ORDER BY Name");
					    			foreach($result as $row)
									{
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";
									}
								?>
				    		</ul>
				    	</li>
				    	<li><a href="#">Nematoda</a>
				    		<ul class="mammal_sub">
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Nematoda' ORDER BY Name");
					    			foreach($result as $row)
									{
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";
									}
								?>
				    		</ul>
				    	</li>
				    	<li><a href="#">Annelida</a>
				    		<ul class="mammal_sub">
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Annelida' ORDER BY Name");
					    			foreach($result as $row)
									{
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";
									}
								?>
				    		</ul>
				    	</li>
				    	<li><a href="#">Echinodermata</a>
				    		<ul class="mammal_sub">
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Echinodermata' ORDER BY Name");
					    			foreach($result as $row)
									{
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";
									}
								?>
				    		</ul>
				    	</li>
				    	<li><a href="#">Mollusca</a>
				    		<ul class="mammal_sub">
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Mollusca'' ORDER BY Name");
					    			foreach($result as $row)
									{
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";
									}
								?>
				    		</ul>
				    	</li>
				    	<li><a href="#">Arthropoda</a>
				    		<ul class="mammal_sub">
				    			<?php
				    			    $result = $myPDO->query("SELECT * FROM animals_db WHERE Category = 'Arthropoda' ORDER BY Name");
					    			foreach($result as $row)
									{
										echo "<li><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size: 2.6vh; margin-left:40px\">&#8226 " .$row["Name"]. "</a></li>";
									}
								?>
				    		</ul>
				    	</li>
				    </ul>
				</li>
			</ul>
			<ul class="all" id="all_list">
				<?php
				    $result = $myPDO->query("SELECT * FROM animals_db ORDER BY Name");
					foreach($result as $row)
					{
						echo "<li id=\"" .$row["Name"]. "\"><a href=\"#\" onclick=\"showAnimal('" .$row["Filepath"]. "', '" .$row["Name"]. "', '" .$row["ScientificName"]. "', '" .$row["Diet"]. "', '" .$row["Category"]. "')\" style=\"font-size:3.3vh; text-decoration: underline\"> " .$row["Name"]. "</a></li>";
					}
				?>
			</ul>
		</nav>


		<div id="homeScreen">
			<p>Welcome to the WebVR animal encyclopedia!</p>
			<p>VR headsets that our program supports:</p>
			<ul style="list-style-type: circle">
				<li>HTC Vive with controllers and trackers</li>
				<li>Oculus Rift with Touch controllers</li>
				<li>Google Daydream with controller</li>
				<li>Samsung GearVR with controller</li>
				<li>Google Cardboard</li>
			</ul>
		</div>

		<div id="container">			
			<a-scene embedded>
	     		<a-assets>
	     			<img id="sky" src="img/sky.jpg"></img>
	     			<a-asset-item id="optimerBoldFont" src="https://rawgit.com/mrdoob/three.js/dev/examples/fonts/optimer_bold.typeface.json"></a-asset-item>
	     		</a-assets>

				<a-entity id="environment" environment="skyType: atmosphere; skyColor: #cce8ff; groundTexture: none; groundColor: #4f4226; fog:0.1"></a-entity>

		   		<a-plane id="text-background" material="color: #FFF; transparent: true; opacity: 0.5" height="5" width="13" position="0 8.7 -20"></a-plane>

		   		<a-entity id="model" cursor-listener scale="5 5 5" position="0 0 -6" json-model="src: models/tiger1.json" rotation="0 90 0"></a-entity>

				<a-entity auto-detect-controllers></a-entity>

		   		<a-entity id="vr-name" text-geometry="value: Name: ; font: #optimerBoldFont" material="color: #111" position="-5 10 -20" rotation="0 0 0" size="1"></a-entity>
		   		<a-entity id="vr-s-name"  text-geometry="value: Scientific Name: ; font: #optimerBoldFont" material="color: #000" position="-5 9 -20" rotation="0 0 0" size="1"></a-entity>
		   		<a-entity id="vr-diet"  text-geometry="value: Diet: ; font: #optimerBoldFont" material="color: #000" position="-5 8 -20" rotation="0 0 0" size="1"></a-entity>
		   		<a-entity id="vr-category"  text-geometry="value: Category: ; font: #optimerBoldFont" material="color: #000" position="-5 7 -20" rotation="0 0 0" size="1"></a-entity>

		    	<a-entity light="color: #ccccff; intensity: 1; type: ambient;" visible="true"></a-entity>

	    
	      	</a-scene>
        </div>

        <div id="animal-pages">
        	
        </div>

		<div id="info_display" class="lbl">
		Name: <span id="name" class="n"></span><br/>
		Scientific Name: <span id="s_name" class="s"></span><br/>
		Diet: <span id="diet" class="d"></span><br/>
		Category: <span id="category" class="c"></span><br/>
		</div>

		<!--<script src="js/three.js"></script>
		<script src="js/controls/OrbitControls.js"></script>
		<script src="js/renderers/Projector.js"></script>
		<script src="js/renderers/CanvasRenderer.js"></script>
		<script src="js/OBJLoader.js"></script>
		<script src="js/libs/tween.min.js"></script>
		<script>
			var container, menu;
			var controls;

			var camera, scene, renderer, light;
			var group;

			var targetRotation = 0;
			var targetRotationOnMouseDown = 0;

			var mouseX = 0;
			var mouseXOnMouseDown = 0;

			var windowHalfX = window.innerWidth / 2;
			var windowHalfY = window.innerHeight / 2;
			
			var walls, furniture, interactiveObj = [];
			var desk, television, tvcase, sofa, chair, door, book, pen;
			var wallWidth = 1000;
			
			var video, tvImage,imageContext;
			var screenTexture, tvMaterial;
			var clock = new THREE.Clock();
			
			var mixer=new THREE.AnimationMixer(scene);
			var set=false;

			var kfei, idle, rest, run;

			init();
			animate();

			function init() {

				menu = document.getElementById("menu");
				container = document.createElement( 'div' );
				document.body.appendChild(menu);
				document.body.appendChild( container );

				scene = new THREE.Scene();
				scene.background = new THREE.Color( 0xcce0ff );
				scene.fog = new THREE.Fog( 0xcce0ff, 500, 10000 );
				


				// camera
				camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 1, 10000);
				camera.position.set(0, -1000, 10);
				//camera.lookAt( new THREE.Vector3(10,10,10));


				scene.add( new THREE.AmbientLight( 0x404040 ) );
				
				//group
				group = new THREE.Group();
				group.position.y = 50;
				scene.add( group );

				//light
				var directionalLight = new THREE.DirectionalLight(0xffffff);
				directionalLight.position.set( 0, 10, 10 ).normalize();
				directionalLight.castShadow = true;
				directionalLight.shadow.mapSize.width = 2048;
				directionalLight.shadow.mapSize.height = 2048;
				scene.add( directionalLight );

				//book
				var loader = new THREE.JSONLoader();
				loader.load('models/book.json', handle_load);

				function handle_load(geometry, materials){
				    book = new THREE.Mesh(geometry, materials);
				    console.log(materials);
				    book.scale.set(30, 30, 30);
				    book.position.set(0,-700,0);
				   	group.add(book);
				};

				//book title
				var loader = new THREE.FontLoader();
				loader.load( 'fonts/helvetiker_regular.typeface.json', function ( font ) {
					var xMid, text;
					var textShape = new THREE.BufferGeometry();
					var matDark = new THREE.LineBasicMaterial( {
						color: 0x203499,
						side: THREE.DoubleSide
					} );

					var shapes = font.generateShapes( "    Animal\nEncyclopedia", 13, 2 );
					var geometry = new THREE.ShapeGeometry( shapes );
					geometry.translate(5,-600,30);
					textShape.fromGeometry( geometry );
					text = new THREE.Mesh( textShape, matDark );
					group.add( text );
				} ); 

				//house
				var loader1=new THREE.ObjectLoader();
				loader1.load('models/room/house1.json', load_room);


				function load_room(object, materials){
					//var material1 = loader.initMaterials(modelJSON.geometries[i].materials);
					//object.material = new THREE.MultiMaterial(material1);

					object.scale.set(300,300,300);
					object.position.set(0,-300,0);
					object.receiveShadow=true;
					object.rotation.y=3.14;
					object.overdraw=true;
				    group.add(object);
				    
				    room = new THREE.Mesh(geometry, materials);
				    console.log(materials);
				    room.scale.set(300, 300, 300);
				   	room.position.set(0,0,0);
				   	group.add(room);
				}; 

				//WALLS

				/*walls = new THREE.Object3D();
			
				
				var groundMat = new THREE.MeshPhongMaterial({color:0xff33ff, specular: 0x000011});
				var groundGeo = new THREE.PlaneGeometry(wallWidth, wallWidth);
				
				var wallT=new THREE.TextureLoader().load("textures/walls/blocks.jpg");
				var wallTexture = new THREE.MeshBasicMaterial({ map:wallT });
				//wallTexture.map.needsUpdate = true;

				var floorT = new THREE.TextureLoader().load("textures/walls/floor.jpg");
				var floorTexture = new THREE.MeshBasicMaterial({ map: floorT });
				//});
				//floorTexture.map.needsUpdate = true;
				
				var ground = new THREE.Mesh(groundGeo, floorTexture);
				ground.overdraw = true;
				ground.position.set(0, -wallWidth, 0);
				ground.rotation.x = -Math.PI/2;
				walls.add(ground);
				
				var wallleft = new THREE.Mesh(groundGeo, wallTexture);
				wallleft.overdraw = true;
				wallleft.position.set(-wallWidth/2, -wallWidth/2, 0);
				wallleft.rotation.y = Math.PI/2;
				walls.add(wallleft);
				
				var wallright = new THREE.Mesh(groundGeo, wallTexture);
				wallright.overdraw = true;
				wallright.position.set(wallWidth/2, -wallWidth/2, 0);
				wallright.rotation.y = -Math.PI/2;
				walls.add(wallright);
				
				var wallback = new THREE.Mesh(groundGeo, wallTexture);
				wallback.overdraw = true;
				wallback.position.set(0, -wallWidth/2, -wallWidth/2);
				walls.add(wallback);
				
				var wallfront = new THREE.Mesh(groundGeo, wallTexture);
				wallfront.overdraw = true;
				wallfront.position.set(0, -wallWidth/2, wallWidth/2);
				wallfront.rotation.y = -Math.PI;
				walls.add(wallfront);

				group.add(walls); */
				

				//FURNITURE

				furniture = new THREE.Object3D();
				
				//desk
				var bookcaseTexture = new THREE.TextureLoader().load('textures/wood.jpg');
				var loader2 = new THREE.ImageLoader().load('textures/wood.jpg');
//				loader2.load( function ( event ) {
//					bookcaseTexture.image = event.content;
//					//bookcaseTexture.needsUpdate = true;
//				} );
//				loader2.load( 'textures/wood.jpg' );
				
				var loader = new THREE.JSONLoader();
				loader.load( function(geometry, materials){
					desk = new THREE.Mesh(geometry, materials);
					for ( var i = 0, l = desk.children.length; i < l; i ++ ) {
						desk.children[ i ].material.map = bookcaseTexture;
					}
					desk.position.set(wallWidth/2-150,-wallWidth-5, -wallWidth/2 + 160);
					desk.scale.set(80, 80, 80);
					group.add(desk);
					
				});
				loader.load('models/room/Desk.obj');
								
				//tvScreen
			/*	video = document.getElementById( 'video' );
				tvImage = document.createElement('canvas');
				tvImage.crossOrigin = "anonymous";
				tvImage.width = 480;
				tvImage.height = 204;
				
				
				imageContext = tvImage.getContext('2d');
				imageContext.fillStyle='#000000';
				imageContext.fillRect(0,0,480,204);
				
				screenTexture = new THREE.Texture(tvImage);
				screenTexture.minFilter = THREE.LinearFilter;
				screenTexture.maxFilter = THREE.LinearFilter;
				
				tvMaterial = new THREE.MeshBasicMaterial( { map: screenTexture, overdraw: true } );
				
				var screenPlane = new THREE.PlaneGeometry(480, 204, 4, 4);
				var mesh = new THREE.Mesh(screenPlane, tvMaterial);
				mesh.position.set(-wallWidth/2+90,-wallWidth+220, 0);
				mesh.rotation.set(0,Math.PI/2, 0);
				
				walls.add(mesh);*/
				//sofa
				/*var sofaTexture = new THREE.Texture();
				var loader = new THREE.ImageLoader();
				loader.addEventListener( 'load', function ( event ) {
					sofaTexture.image = event.content;
					sofaTexture.needsUpdate = true;
				} );
				loader.load( 'textures/Red.jpg' );
				
				var loader = new THREE.OBJLoader();
				loader.addEventListener('load', function(event){
					sofa = event.content;
					for ( var i = 0, l = sofa.children.length; i < l; i ++ ) {
						sofa.children[ i ].material.map = sofaTexture;
					}
					sofa.position.set(0,-wallWidth+20, -150);
					sofa.rotation.set(0,-Math.PI/2, 0);
					sofa.scale.set(1, 1, 1);
					walls.add(sofa);
					
				});
				loader.load('obj/Sofa_OBJ.obj');
				
				//chair
				var chairTexture = new THREE.Texture();
				var loader = new THREE.ImageLoader();
				loader.addEventListener( 'load', function ( event ) {
					chairTexture.image = event.content;
					chairTexture.needsUpdate = true;
				} );
				loader.load( 'textures/grey.jpg' );
				
				var loader = new THREE.OBJLoader();
				loader.addEventListener('load', function(event){
					chair = event.content;
					for ( var i = 0, l = chair.children.length; i < l; i ++ ) {
						chair.children[ i ].material.map = chairTexture;
					}
					chair.position.set(300,-wallWidth, -300);
					chair.rotation.set(0,-Math.PI/2, 0);
					chair.scale.set(2, 2, 2);
					walls.add(chair);
					
				});
				loader.load('obj/ferrera.obj');
				
				//door
				var doorTexture = new THREE.Texture();
				var loader = new THREE.ImageLoader();
				loader.addEventListener( 'load', function ( event ) {
					doorTexture.image = event.content;
					doorTexture.needsUpdate = true;
				} );
				loader.load( 'textures/InteriorDoor_Diffuce.jpg' );
				
				var loader = new THREE.OBJLoader();
				loader.addEventListener('load', function(event){
					door = event.content;
					for ( var i = 0, l = door.children.length; i < l; i ++ ) {
						door.children[ i ].material.map = doorTexture;
					}
					door.position.set(wallWidth/2,-wallWidth, wallWidth/2-150);
					door.rotation.set(0,Math.PI/2, 0);
					door.scale.set(5, 5, 5);
					walls.add(door);
					
				});
				loader.load('obj/InteriorDoor.obj');
				
				//book
				var bookTexture = new THREE.Texture();
				var loader = new THREE.ImageLoader();
				loader.addEventListener( 'load', function ( event ) {
					bookTexture.image = event.content;
					bookTexture.needsUpdate = true;
				} );
				loader.load( 'textures/books_texture.jpg');
				
				var loader = new THREE.OBJLoader();
				loader.addEventListener( 'load', function ( event ) {
					book = event.content;
					for ( var i = 0, l = book.children.length; i < l; i ++ ) {
						book.children[ i ].material.map = bookTexture;
					}
					book.position.set(wallWidth/2-130,-wallWidth+150, -wallWidth/2 + 160);
					book.rotation.set(0,Math.PI/2, 0);
					book.scale.set(0.5, 0.5, 0.5);
					
					interactiveObj.push(book);
					walls.add(book);
				});
				loader.load( 'obj/books.obj');
				
				
				//pen
				var penTexture = new THREE.Texture();
				var loader = new THREE.ImageLoader();
				loader.addEventListener( 'load', function ( event ) {
					penTexture.image = event.content;
					penTexture.needsUpdate = true;
				} );
				loader.load( 'textures/as_creative_kit_lwf.jpg');
				
				var loader = new THREE.OBJLoader();
				loader.addEventListener( 'load', function ( event ) {
					var object = event.content;
					object.children[0].material.map = penTexture;
					
					pen = new THREE.Mesh(object.children[0].geometry,  object.children[0].material);
					/*for ( var i = 0, l = pen.children.length; i < l; i ++ ) {
						pen.children[ i ].material.map = penTexture;
					}
					pen.position.set(wallWidth/2-100,-wallWidth+150, -wallWidth/2 + 200);
					pen.rotation.set(0,Math.PI/2, 0);
					pen.scale.set(5, 5, 5);
					
					interactiveObj.push(pen);
					walls.add(pen);
				});
				loader.load( 'obj/as_creative_kit.obj'); */


				//renderer
				renderer = new THREE.WebGLRenderer({ antialias: true });
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				renderer.shadowMap.renderSingleSided = false;		

				// controls
				controls = new THREE.OrbitControls( camera, renderer.domElement );
				controls.maxPolarAngle = Math.PI * 0.5;
				//controls.minDistance = 1000;
				//controls.maxDistance = 5000;



				container.appendChild( renderer.domElement );
			
				renderer.shadowMap.enabled = true;

				//container.addEventListener( 'mousedown', onDocumentMouseDown, false );
				//container.addEventListener( 'touchstart', onDocumentTouchStart, false );
				//container.addEventListener( 'touchmove', onDocumentTouchMove, false );

				//

				window.addEventListener( 'resize', onWindowResize, false );

			}

			function showAnimal(filepath, name, scientificName, diet, category) {
				for (var i = group.children.length - 1; i>=0; i--){
					group.remove(group.children[i]);
				}

				var loader=new THREE.JSONLoader();
				var path="models/"+filepath;
				console.log(path);
				/*loader.load(path, function(geometry){
					var material = new THREE.MeshBasicMaterial({skinning: true});
					var skinnedMesh = new THREE.SkinnedMesh(geometry, material);
					scene.add(skinnedMesh);
					console.log(skinnedMesh);

					mixer=new THREE.AnimationMixer(skinnedMesh);
					var walkAction = mixer.clipAction(geometry.animations[0]);
					walkAction.play()
					set=true;
				})
				/*loader.load( path, function( geometry ) {

					var material = new THREE.MeshPhongMaterial( {
						color: 0xffffff,
						morphTargets: true,
						vertexColors: THREE.FaceColors,
						flatShading: true
					} );
					var mesh = new THREE.Mesh( geometry, material );

					mesh.position.x = - 150;
					mesh.position.y = 150;
					mesh.scale.set( 1.5, 1.5, 1.5 );

					scene.add( mesh );

					var mixer = new THREE.AnimationMixer( mesh );
					mixer.clipAction( geometry.animations[ 0 ] ).setDuration( 1 ).play();

					mixers.push( mixer );

				} ); */
				loader.load(path, function(geometry, materials){

					var model = new THREE.Mesh(geometry,materials);
					var mixer = new THREE.AnimationMixer( model );
					var clips = mesh.animations;
					console.log(model)

					// Update the mixer on each frame
					function update () {
						mixer.update( deltaSeconds );
					}

					// Play a specific animation
					var clip = THREE.AnimationClip.findByName( clips, 'Wolf_Walk_cycle_' );
					var action = mixer.clipAction( clip );
					action.play();

					// Play all animations
					clips.forEach( function ( clip ) {
						mixer.clipAction( clip ).play();
					} );
					//var mesh=new THREE.Mesh(geometry, new THREE.MeshFaceMaterial(materials));

					//group.add(mesh);

					//var material=new THREE.MeshNormalMaterial();
					//material.morphTargets=true;
					//material.color.setHex(0xffaaaa);
				   // a_model = new THREE.Mesh(geometry, material);
				    //a_model.scale.set(700, 700, 700);
				    //a_model.position.set(0,-250,0);
				   	

//				   	mixer=new THREE.AnimationMixer(a_model);
//				   	console.log(a_model.animations[0]);
//				   	mixer.ClipAction(a_model.animations[0]).play();
					//var animation=new THREE.Animation(a_model.animations[0]);
					//animation.play();
					/*group.add(a_model);
					console.log(a_model);
					console.log(a_model.materials[0]);
					mixer=new THREE.MorphAnimMesh(a_model, a_model.materials[0]);
					mixer.scale.set(0.1,0.1,0.1);
					mixer.duration=1000;
					mixer.time=1000 * Math.random();*/
				}); 

				var textureLoader=new THREE.TextureLoader();
				var groundTexture = textureLoader.load( 'textures/grass.jpg' );
				groundTexture.wrapS = groundTexture.wrapT = THREE.RepeatWrapping;
				groundTexture.repeat.set( 25, 25 );
				groundTexture.anisotropy = 16;

				var groundMaterial = new THREE.MeshLambertMaterial( { map: groundTexture } );
				var mesh = new THREE.Mesh( new THREE.PlaneBufferGeometry( 20000, 20000 ), groundMaterial );
				mesh.position.y = - 250;
				mesh.rotation.x = - Math.PI / 2;
				mesh.receiveShadow = true;
				group.add( mesh );

				document.getElementById("info_display").style.display="block";

				document.getElementById("name").innerHTML=name;
				document.getElementById("s_name").innerHTML=scientificName;
				document.getElementById("category").innerHTML=category;
				document.getElementById("diet").innerHTML=diet; 
			//});

			}

			function onWindowResize() {

				windowHalfX = window.innerWidth / 2;
				windowHalfY = window.innerHeight / 2;

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

			}

			//

			/*function onDocumentMouseDown( event ) {

				event.preventDefault();

				container.addEventListener( 'mousemove', onDocumentMouseMove, false );
				container.addEventListener( 'mouseup', onDocumentMouseUp, false );
				container.addEventListener( 'mouseout', onDocumentMouseOut, false );

				mouseXOnMouseDown = event.clientX - windowHalfX;
				targetRotationOnMouseDown = targetRotation;

			}

			function onDocumentMouseMove( event ) {

				mouseX = event.clientX - windowHalfX;

				targetRotation = targetRotationOnMouseDown + ( mouseX - mouseXOnMouseDown ) * 0.02;

			}

			function onDocumentMouseUp( event ) {

				container.removeEventListener( 'mousemove', onDocumentMouseMove, false );
				container.removeEventListener( 'mouseup', onDocumentMouseUp, false );
				container.removeEventListener( 'mouseout', onDocumentMouseOut, false );

			}

			function onDocumentMouseOut( event ) {

				container.removeEventListener( 'mousemove', onDocumentMouseMove, false );
				container.removeEventListener( 'mouseup', onDocumentMouseUp, false );
				container.removeEventListener( 'mouseout', onDocumentMouseOut, false );

			}

			function onDocumentTouchStart( event ) {

				if ( event.touches.length == 1 ) {

					event.preventDefault();

					mouseXOnMouseDown = event.touches[ 0 ].pageX - windowHalfX;
					targetRotationOnMouseDown = targetRotation;

				}

			}

			function onDocumentTouchMove( event ) {

				if ( event.touches.length == 1 ) {

					event.preventDefault();

					mouseX = event.touches[ 0 ].pageX - windowHalfX;
					targetRotation = targetRotationOnMouseDown + ( mouseX - mouseXOnMouseDown ) * 0.05;

				}

			}*/
			//

			function animate() {

				requestAnimationFrame( animate );
				//camera.lookAt(new THREE.Vector3(0,-wallWidth/2 +10,10));
				//mixer.update(clock.getDelta() );
				controls.update();
				render();
			}

			function render() {

				//group.rotation.y += ( targetRotation - group.rotation.y ) * 0.05;

				//if(set)
				//mixer.update(clock.getDelta());
				mixer.update( clock.getDelta() );

				renderer.render( scene, camera );
				//requestAnimationFrame(render);
			}
		</script>-->
	</body>
</html>