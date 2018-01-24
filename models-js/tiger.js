
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
	ground: 'noise',
	skyType: 'gradient',
	skyColor: '#cce8ff',
	groundTexture: 'none',
	groundColor: '#4f4226',
	fog: 0.5
}, true);

model.setAttribute('id',"model");
model.setAttribute('json-model', 'src', "models/tiger1.json");
model.setAttribute('scale', "3 3 3");
model.setAttribute('animation-mixer', 'clip', "Walk");
model.setAttribute('cursor-listener');
model.setAttribute('position',"2 0 -4");
model.setAttribute('rotation', "0 10 0");