
//remove previous model
if (document.getElementById("model") != null){
	$('#model').remove();
}

//save all the elements in a javascript variable (scene, vr text, and the new animal model)
var scene=document.querySelector('a-scene');
var plane=document.querySelector('#text-background');
var model=document.createElement('a-entity');
scene.appendChild(model);

//environment 
var envi = document.querySelector('#environment')
envi.setAttribute('environment',{
	active: 'true',
	shadow:'true',
	shadowSize: '30',
	ground: 'flat',
	skyType: 'gradient',
	skyColor: '#D3D3D3',
	groundTexture: 'none',
	groundColor: '#333',
	fog: 0.5
}, true);

model.setAttribute('id',"model");
model.setAttribute('json-model', 'src', "models/error.json");
model.setAttribute('scale', "0.5 0.5 0.5");
model.setAttribute('cursor-listener');
model.setAttribute('position',"0 1 -4");
model.setAttribute('rotation', "0 0 0");

plane.setAttribute('position', '0 8.7 -20');
plane.setAttribute('width', '13');
plane.setAttribute('height', '5');