<?php
	$db = mysqli_connect('localhost','root','','animals')
	or die('Error connecting to MySQL server.');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>three.js webgl - multiple elements</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<style>		
			*{
				padding:0; margin:0;
				box-sizing: border-box;
				-moz-box-sizing: border-box;
			}


			body {
				color: #000;
				font-family:Monospace;
				font-size:13px;

				background-color: #fff;
				margin: 0px;
			}

			#info {
				position: absolute;
				top: 0px; width: 100%;
				padding: 5px;
				text-align:center;
			}

			#content {
				position: absolute;
				top: 0px; width: 100%;
				z-index: 1;
				padding: 3em 0 0 0;
			}

			.c a {
				color: #0080ff;
			}

			#c {
				position: fixed;
				left: 0px;
				width: 100%;
				height: 100%;
			}

			.list-item {
				display: inline-block;
				margin: 1em;
				padding: 1em;
				box-shadow: 1px 2px 4px 0px rgba(0,0,0,0.25);
			}

			.list-item .scene {
				width: 200px;
				height: 200px;
			}

			.list-item .description {
				color: #888;
				font-family: sans-serif;
				font-size: large;
				width: 200px;
				margin-top: 0.5em;
			}


			.sidenav {
			    height: 100%;
			    width: 250px;
			    position: fixed;
			    z-index: 1;
			    top: 0;
			    left: 0;
			    background-color: #111;
			    overflow-x: hidden;
			    transition: 0.5s;
			    padding-top: 60px;
			}

			.sidenav a {
			    text-decoration: none;
			    font-size: 25px;
			    color: #B0B0B0;
			    display: block;
			    transition: 0.3s;
			}

			.sidenav a:hover {
			    color: #f1f1f1;
			    background-color: #222
			}

			.main_nav_ul a {
				margin:0;
				text-decoration: none;
				display: block;
				padding: 10px 0 10px 20px;
				border-bottom:1px grey;
			}

			.sidenav li {list-style: none;}
			.sidenav {width: 0px; margin: 0;}
			.sidenav .closebtn {
			    position: absolute;
			    top: 0;
			    right: 10px;
			    padding: 2px 10px 2px 10px;
			    font-size: 36px;
			    margin-left: 50px;
			    border-bottom: none;
			}
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
				font-size: 20px;
			}

			.i_sub li a {
				margin-left: 25px;
				font-size: 20px;
			}

			@media screen and (max-height: 450px) {
			  .sidenav {padding-top: 15px;}
			  .sidenav a {font-size: 18px;}
			}

			input[type=text] {
			    width: 100%;
			    box-sizing: border-box;
			    border: 2px solid #ccc;
			    border-radius: 4px;
			    font-size: 16px;
			    background-color: white;
			    background-image: url('img/searchIcon.png');
			    background-size: 20px 20px;
			    background-position: 10px 10px; 
			    background-repeat: no-repeat;
			    padding: 12px 20px 12px 40px;
			}

		</style>
	</head>
	<body>
		<?php
		   $sql = 'SELECT Name, id, In/Ver FROM animal';
		   mysql_select_db('animals');
		   $retval = mysql_query( $sql, $db );
		   
		   if(! $retval ) {
		      die('Could not get data: ' . mysql_error());
		   }
		   
		   while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) {
		      echo "name :{$row['Name']}  <br> ".
		         "id : {$row['id']} <br> ".
		         "category : {$row['In/Ver']} <br> ".
		         "--------------------------------<br>";
		   }
		   
		   echo "Fetched data successfully\n";
		   
		   mysql_close($db);
		?>
		<canvas id="c"></canvas>

		<div id="content">
			<div id="info"><a href="#" target="_blank"></a></div>
		</div>

		<div style="float: left;" id="menu">
			<nav id="mySidenav" class="sidenav">
				<ul class="main_nav_ul">
					<input type="text" name="search" placeholder="Search.."></input>
				 	<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
					<li><a href="#" onclick="$(this).parent().find('ul.v_sub').toggle();">Vertebrates<span class="sub-arrow"></span></a>
						<ul class="v_sub">
					    	<li><a href="#">Fishes</a></li>
					    	<li><a href="#">Amphibians</a></li>
					    	<li><a href="#">Reptiles</a></li>
					    	<li><a href="#">Birds</a></li>
					    	<li><a href="#">Mammals</a></li>
					    </ul>
					</li>
					<li><a href="#" onclick="$(this).parent().find('ul.i_sub').toggle();">Invertebrates<span class="sub-arrow1"></span></a>
						<ul class="i_sub">
					    	<li><a href="#">Porifera</a></li>
					    	<li><a href="#">Cnidarians</a></li>
					    	<li><a href="#">Plathyhelminthes</a></li>
					    	<li><a href="#">Nematoda</a></li>
					    	<li><a href="#">Annelida</a></li>
					    	<li><a href="#">Echinodermata</a></li>
					    	<li><a href="#">Mollusca</a></li>
					    	<li><a href="#">Arthropoda</a></li>
					    </ul>
					</li>
				</ul>
			</nav>

			<span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
		</div>

		<script src="js/three.js"></script>
		<script src="js/controls/OrbitControls.js"></script>

		<script src="js/Detector.js"></script>

		<script id="template" type="notjs">
			<div class="scene"></div>
			<div class="description">Fish $</div>
		</script>
		<script>

			function openNav() {
			    document.getElementById("mySidenav").style.width = "250px";
			}

			function closeNav() {
			    document.getElementById("mySidenav").style.width = "0";
			}

			if ( ! Detector.webgl ) Detector.addGetWebGLMessage();

			var canvas;

			var scenes = [], renderer;

			init();
			animate();

			function init() {

				canvas = document.getElementById( "c" );

				var geometries = [
					new THREE.BoxGeometry( 1, 1, 1 ),
					new THREE.SphereGeometry( 0.5, 12, 8 ),
					new THREE.DodecahedronGeometry( 0.5 ),
					new THREE.CylinderGeometry( 0.5, 0.5, 1, 12 )
				];

				var template = document.getElementById( "template" ).text;
				var content = document.getElementById( "content" );
				content.appendChild(document.getElementById("menu"));

				for ( var i =  0; i < 40; i ++ ) {

					var scene = new THREE.Scene();

					// make a list item
					var element = document.createElement( "div" );
					element.className = "list-item";
					element.innerHTML = template.replace( '$', i + 1 );

					// Look up the element that represents the area
					// we want to render the scene
					scene.userData.element = element.querySelector( ".scene" );
					content.appendChild( element );

					var camera = new THREE.PerspectiveCamera( 50, 1, 1, 10 );
					camera.position.z = 2;
					scene.userData.camera = camera;

					var controls = new THREE.OrbitControls( scene.userData.camera, scene.userData.element );
					controls.minDistance = 2;
					controls.maxDistance = 5;
					controls.enablePan = false;
					controls.enableZoom = false;
					scene.userData.controls = controls;

					// add one random mesh to each scene
					var geometry = geometries[ geometries.length * Math.random() | 0 ];

					var material = new THREE.MeshStandardMaterial( {

						color: new THREE.Color().setHSL( Math.random(), 1, 0.75 ),
						roughness: 0.5,
						metalness: 0,
						flatShading: true

					} );

					scene.add( new THREE.Mesh( geometry, material ) );

					scene.add( new THREE.HemisphereLight( 0xaaaaaa, 0x444444 ) );

					var light = new THREE.DirectionalLight( 0xffffff, 0.5 );
					light.position.set( 1, 1, 1 );
					scene.add( light );

					scenes.push( scene );

				}


				renderer = new THREE.WebGLRenderer( { canvas: canvas, antialias: true } );
				renderer.setClearColor( 0xffffff, 1 );
				renderer.setPixelRatio( window.devicePixelRatio );

			}

			function updateSize() {

				var width = canvas.clientWidth;
				var height = canvas.clientHeight;

				if ( canvas.width !== width || canvas.height != height ) {

					renderer.setSize( width, height, false );

				}

			}

			function animate() {

				render();
				requestAnimationFrame( animate );

			}

			function render() {

				updateSize();

				renderer.setClearColor( 0xffffff );
				renderer.setScissorTest( false );
				renderer.clear();

				renderer.setClearColor( 0xe0e0e0 );
				renderer.setScissorTest( true );

				scenes.forEach( function( scene ) {

					// so something moves
					scene.children[0].rotation.y = Date.now() * 0.001;

					// get the element that is a place holder for where we want to
					// draw the scene
					var element = scene.userData.element;

					// get its position relative to the page's viewport
					var rect = element.getBoundingClientRect();

					// check if it's offscreen. If so skip it
					if ( rect.bottom < 0 || rect.top  > renderer.domElement.clientHeight ||
						 rect.right  < 0 || rect.left > renderer.domElement.clientWidth ) {

						return;  // it's off screen

					}

					// set the viewport
					var width  = rect.right - rect.left;
					var height = rect.bottom - rect.top;
					var left   = rect.left;
					var top    = rect.top;

					renderer.setViewport( left, top, width, height );
					renderer.setScissor( left, top, width, height );

					var camera = scene.userData.camera;

					//camera.aspect = width / height; // not changing in this example
					//camera.updateProjectionMatrix();

					//scene.userData.controls.update();

					renderer.render( scene, camera );

				} );

			}

		</script>

	</body>
</html>
