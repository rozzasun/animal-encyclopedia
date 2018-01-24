/*
Description: create a 3D scene using the three.js library (a node.js module)
This could be replaced or used with A-Frame
Author: Rosa Sun, Karen Fei
Date: January 17th, 2018
*/

var container, menu; //HTML elements
var controls; //THREE orbit controls

var camera, scene, renderer, light; //3D scene elements
var group; //THREE.Group()

//used to rotate the scene
var targetRotation = 0;
var targetRotationOnMouseDown = 0;

var mouseX = 0;
var mouseXOnMouseDown = 0;

//half dimension of window
var windowHalfX = window.innerWidth / 2;
var windowHalfY = window.innerHeight / 2;

var clock = new THREE.Clock();

var mixer=new THREE.AnimationMixer(scene); //needed to play animation
var set=false;

init();
animate();

function init() {

	//create HTML elements to contain the scene
	menu = document.getElementById("menu");
	container = document.createElement( 'div' );
	document.body.appendChild(menu);
	document.body.appendChild( container );

	//create a new scene using the THREE.Scene() constructor
	scene = new THREE.Scene();
	scene.background = new THREE.Color( 0xcce0ff ); //set background attribute
	scene.fog = new THREE.Fog( 0xcce0ff, 500, 10000 ); //create fog


	// new THREE.PerspectiveCamera() (user view)
	camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 1, 10000);
	camera.position.set(0, -1000, 10);


	//need a light to light up the scene (otherwise materials won't display)
	scene.add( new THREE.AmbientLight( 0x404040 ) );
	
	//THREE.Group() which contains  
	group = new THREE.Group();
	group.position.y = 50;
	scene.add( group );

	//light
	var directionalLight = new THREE.DirectionalLight(0xffffff);
	directionalLight.position.set( 0, 10, 10 ).normalize();
	directionalLight.castShadow = true; //cast shadow
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

	//FontLoader() loads fonts when they're given a font type as a parameter
	loader.load( 'fonts/helvetiker_regular.typeface.json', function ( font ) {
		var text;
		//create BufferGeometry for textShape
		var textShape = new THREE.BufferGeometry();

		//material of the font (currently just a simple color)
		var matDark = new THREE.LineBasicMaterial( {
			color: 0x203499,
			side: THREE.DoubleSide
		} );

		//generate the letters
		var shapes = font.generateShapes( "    Animal\nEncyclopedia", 13, 2 );

		//turn letters into Mesh()
		var geometry = new THREE.ShapeGeometry( shapes );
		geometry.translate(5,-600,30);
		textShape.fromGeometry( geometry );
		text = new THREE.Mesh( textShape, matDark );

		//add text to group
		group.add( text );
	} ); 
	

	//renderer
	renderer = new THREE.WebGLRenderer({ antialias: true });
	renderer.setPixelRatio( window.devicePixelRatio );
	renderer.setSize( window.innerWidth, window.innerHeight );
	renderer.shadowMap.renderSingleSided = false;		

	// controls
	controls = new THREE.OrbitControls( camera, renderer.domElement );
	controls.maxPolarAngle = Math.PI * 0.5;
	container.appendChild( renderer.domElement );

	renderer.shadowMap.enabled = true;

	window.addEventListener( 'resize', onWindowResize, false );

}

function showAnimal(filepath, name, scientificName, diet, category) {
	for (var i = group.children.length - 1; i>=0; i--){
		group.remove(group.children[i]);
	}

	var loader=new THREE.JSONLoader();
	var path="models/"+filepath;
	console.log(path);

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
	}); 

	//load grass texture

	//texture loader
	var textureLoader=new THREE.TextureLoader();

	//load image
	var groundTexture = textureLoader.load( 'textures/grass.jpg' );
	groundTexture.wrapS = groundTexture.wrapT = THREE.RepeatWrapping; //RepeatWrapping makes the ground look endless
	groundTexture.repeat.set( 25, 25 );
	groundTexture.anisotropy = 16;

	//create ground material by using the texture loaded above
	var groundMaterial = new THREE.MeshLambertMaterial( { map: groundTexture } );
	var mesh = new THREE.Mesh( new THREE.PlaneBufferGeometry( 20000, 20000 ), groundMaterial );
	mesh.position.y = - 250;
	mesh.rotation.x = - Math.PI / 2;
	mesh.receiveShadow = true;
	group.add( mesh );

}

//change width/height of the scene and some camera attributes when the window resizes
function onWindowResize() {

	windowHalfX = window.innerWidth / 2;
	windowHalfY = window.innerHeight / 2;

	camera.aspect = window.innerWidth / window.innerHeight;
	camera.updateProjectionMatrix();

	renderer.setSize( window.innerWidth, window.innerHeight );

}

//move scene in the direction of the mouse when the user clicks
function onDocumentMouseDown( event ) {

	event.preventDefault();

	container.addEventListener( 'mousemove', onDocumentMouseMove, false );
	container.addEventListener( 'mouseup', onDocumentMouseUp, false );
	container.addEventListener( 'mouseout', onDocumentMouseOut, false );

	mouseXOnMouseDown = event.clientX - windowHalfX;
	targetRotationOnMouseDown = targetRotation;

}

//move scene in the direction of the mouse when the user clicks and drags
function onDocumentMouseMove( event ) {

	mouseX = event.clientX - windowHalfX;

	targetRotation = targetRotationOnMouseDown + ( mouseX - mouseXOnMouseDown ) * 0.02;

}

//stop moving the scene by removing the event listeners
function onDocumentMouseUp( event ) {

	container.removeEventListener( 'mousemove', onDocumentMouseMove, false );
	container.removeEventListener( 'mouseup', onDocumentMouseUp, false );
	container.removeEventListener( 'mouseout', onDocumentMouseOut, false );

}

//animate using requestAnimationFrame() function declared in the three.js library
function animate() {

	requestAnimationFrame( animate );
	controls.update();
	render(); //render the scene again by calling on the render function below
}

//update position/shape of the animated model
function render() {

	mixer.update( clock.getDelta() );
	renderer.render( scene, camera );
}