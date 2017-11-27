<?php
	 $db = mysqli_connect('localhost','root','','animals')
	 or die('Error connecting to MySQL server.');

	 die("hi");
?>

<html>
	<head>
		<meta charset=utf-8>
		<title>WebVr Animal Encyclopedia</title>
		<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript">
			function openNav() {
			    document.getElementById("mySidenav").style.width = "250px";
			}

			function closeNav() {
			    document.getElementById("mySidenav").style.width = "0";
			}
		</script>
		<style type="text/css">
		canvas {width:100%; height:100%;}
		body {
		    font-family: "Lato", sans-serif;
		}

		*{padding:0; margin:0;}

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

		<script src="js/curves/NURBSCurve.js"></script>
		<script src="js/curves/NURBSUtils.js"></script>

		<script src="js/renderers/Projector.js"></script>
		<script src="js/renderers/CanvasRenderer.js"></script>

		<script src="js/libs/stats.min.js"></script>

		<script>
			
			var container, menu;

			var camera, scene, renderer;
			var group;

			var targetRotation = 0;
			var targetRotationOnMouseDown = 0;

			var mouseX = 0;
			var mouseXOnMouseDown = 0;

			var windowHalfX = window.innerWidth / 2;
			var windowHalfY = window.innerHeight / 2;

			init();
			animate();

			function init() {

				menu = document.getElementById("menu");
				container = document.createElement( 'div' );
				document.body.appendChild(menu);
				document.body.appendChild( container );


				/* var info = document.createElement( 'div' );
				info.style.position = 'absolute';
				info.style.top = '30px';
				info.style.width = '100%';
				info.style.textAlign = 'center';
				info.innerHTML = 'Drag to spin';
				container.appendChild( info ); */


				camera = new THREE.PerspectiveCamera( 50, window.innerWidth / window.innerHeight, 1, 1000 );
				camera.position.set( 0, 150, 500 );

				scene = new THREE.Scene();
				scene.background = new THREE.Color( 0xf0f0f0 );

				group = new THREE.Group();
				group.position.y = 50;
				scene.add( group );
				
				var loader = new THREE.JSONLoader();
				loader.load('Encyclopedia2.json', function(geometry) {
				    mesh = new THREE.Mesh(geometry);
				    mesh.scale.set(30, 30, 30);
				    mesh.position.y=40;
				    mesh.position.x=-50;
				   	group.add(mesh);
				});

				var loader = new THREE.FontLoader();

				loader.load( 'fonts/helvetiker_regular.typeface.json', function ( font ) {

					var geometry = new THREE.TextGeometry( 'WebVR Animal Encyclopedia.js!', {
						font: font,
						size: 80,
						height: 40,
						curveSegments: 12,
						bevelEnabled: true,
						bevelThickness: 10,
						bevelSize: 8,
						bevelSegments: 5
					} );
					group.add(geometry);
				} );
				/*// NURBS curve

				var nurbsControlPoints = [];
				var nurbsKnots = [];
				var nurbsDegree = 3;

				for ( var i = 0; i <= nurbsDegree; i ++ ) {

					nurbsKnots.push( 0 );

				}

				for ( var i = 0, j = 20; i < j; i ++ ) {

					nurbsControlPoints.push(
						new THREE.Vector4(
							Math.random() * 400 - 200,
							Math.random() * 400,
							Math.random() * 400 - 200,
							1 // weight of control point: higher means stronger attraction
						)
					);

					var knot = ( i + 1 ) / ( j - nurbsDegree );
					nurbsKnots.push( THREE.Math.clamp( knot, 0, 1 ) );

				}

				var nurbsCurve = new THREE.NURBSCurve(nurbsDegree, nurbsKnots, nurbsControlPoints);

				var nurbsGeometry = new THREE.Geometry();
				nurbsGeometry.vertices = nurbsCurve.getPoints( 200 );
				var nurbsMaterial = new THREE.LineBasicMaterial( { linewidth: 10, color: 0x333333 } );

				var nurbsLine = new THREE.Line( nurbsGeometry, nurbsMaterial );
				nurbsLine.position.set( 0, -100, 0 );

				var nurbsControlPointsGeometry = new THREE.Geometry();
				nurbsControlPointsGeometry.vertices = nurbsCurve.controlPoints;
				var nurbsControlPointsMaterial = new THREE.LineBasicMaterial( { linewidth: 2, color: 0x333333, opacity: 0.25 } );

				var nurbsControlPointsLine = new THREE.Line( nurbsControlPointsGeometry, nurbsControlPointsMaterial );
				nurbsControlPointsLine.position.copy( nurbsLine.position );

				group.add( nurbsLine, nurbsControlPointsLine ); */
				// this also works:
				// group.add( nurbsLine ).add( nurbsControlPointsLine );

				// 

				renderer = new THREE.CanvasRenderer();
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				container.appendChild( renderer.domElement );


				container.addEventListener( 'mousedown', onDocumentMouseDown, false );
				container.addEventListener( 'touchstart', onDocumentTouchStart, false );
				container.addEventListener( 'touchmove', onDocumentTouchMove, false );

				//

				window.addEventListener( 'resize', onWindowResize, false );

			}

			function onWindowResize() {

				windowHalfX = window.innerWidth / 2;
				windowHalfY = window.innerHeight / 2;

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

			}

			//

			function onDocumentMouseDown( event ) {

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

			}

			//

			function animate() {

				requestAnimationFrame( animate );

				render();
			}

			function render() {

				group.rotation.y += ( targetRotation - group.rotation.y ) * 0.05;
				renderer.render( scene, camera );

			}

		</script>
	</body>
</html>