<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=`device-width`, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sofia+Sans+Extra+Condensed:wght@300&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
    <script async src="https://unpkg.com/es-module-shims@1.3.6/dist/es-module-shims.js"></script>
      <script type="importmap">
          {
            "imports": {
              "three": "https://unpkg.com/three@0.97.0/build/three.module.js"
            }
          }
      </script>
    <title>Potato Service</title>
</head>
<body>
<h1>Potato Service</h1>
<div class="login-form">
      <form>
        <label>Username:</label>
        <input type="text" name="username"><br />
        <label>Password:</label>
        <input type="password" name="password"><br />
        <input type="submit" value="Login">
      </form>
    </div>
    <canvas id="c"></canvas>
    <script type="module">
        import { OBJLoader } from 'https://unpkg.com/three@0.119.1/examples/jsm/loaders/OBJLoader.js';
        import { GLTFLoader } from 'https://unpkg.com/three@0.119.1/examples/jsm/loaders/GLTFLoader.js';
        import { OrbitControls } from 'https://unpkg.com/three@0.119.1/examples/jsm/controls/OrbitControls.js';
import * as THREE from 'https://unpkg.com/three@0.119.1/build/three.module.js';
    function main() {
  const canvas = document.querySelector('#c');
  const renderer = new THREE.WebGLRenderer({canvas,alpha: true});

  const fov = 50;
  const aspect = 1; 
  const near = 0.1;
  const far = 0;
  const camera = new THREE.PerspectiveCamera(fov, aspect, near, far);
  camera.position.z = 5;
        const controls = new OrbitControls(camera, renderer.domElement);
let potato;
  const scene = new THREE.Scene();
        const light = new THREE.AmbientLight( 0xffffff ); 
scene.add( light );
        const gltfloader = new GLTFLoader();
        gltfloader.load('./3d/ziemniak.gltf',
	function ( gltf ) {
            potato = gltf.scene;
		scene.add( gltf.scene );
		gltf.animations;
		gltf.scene;
		gltf.scenes;
		gltf.cameras; 
		gltf.asset; 
               gltf.scene.position.x += 0;
               gltf.scene.position.y += 0;
               gltf.scene.position.z -= 0;
            gltf.scene.scale.x = 0.5;
            gltf.scene.scale.y = 0.5;
            gltf.scene.scale.z = 0.5;
            requestAnimationFrame(render);
	},
	function ( xhr ) {
	},
	function ( error ) {
		console.log( 'An error happened' );
	}
);

  function render(time) {
potato.rotation.x += 0.01;
potato.rotation.y += 0.01;
      
     renderer.setClearColor( 0x000000, 0 );
    renderer.render(scene, camera);

    requestAnimationFrame(render);
  }

        
}

main();
    </script>
  </body>
</html>