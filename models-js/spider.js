
//remove previous model
if (document.getElementById("model") != null){
	$('#model').remove();
}

var scene=document.querySelector('a-scene');
var model=document.createElement('a-entity');
scene.appendChild(model);

var envi = document.querySelector('#environment')
envi.setAttribute('environment',{
	active: 'true',
	shadow:'true',
	shadowSize: '30',
	ground: 'hills',
	groundYScale: 5,
	skyType: 'gradient',
	skyColor: '#cce8ff',
	groundTexture: 'none',
	groundColor: '#4f4226',
	fog: 0.5
}, true);

model.setAttribute('id',"model");
model.setAttribute('json-model', 'src', "models/spider1.json");
model.setAttribute('scale', "0.02 0.02 0.02");
model.setAttribute('animation-mixer', 'clip', "run_ani_vor");
model.setAttribute('cursor-listener');
model.setAttribute('position',"2 0 -4");
model.setAttribute('rotation', "0 45 0");