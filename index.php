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
import * as THREE from 'https://unpkg.com/three@0.119.1/build/three.module.js';
    function main() {
  const canvas = document.querySelector('#c');
  const renderer = new THREE.WebGLRenderer({canvas,alpha: true});

  const fov = 75;
  const aspect = 2; 
  const near = 0.1;
  const far = 5;
  const camera = new THREE.PerspectiveCamera(fov, aspect, near, far);
  camera.position.z = 2;

  const scene = new THREE.Scene();

  const boxWidth = 1;
  const boxHeight = 1;
  const boxDepth = 1;
        const objloader = new OBJLoader();
  const geometry = new THREE.BoxGeometry(boxWidth, boxHeight, boxDepth);

  const cubes = [];  // just an array we can use to rotate the cubes
  const loader = new THREE.TextureLoader();

  const material = new THREE.MeshBasicMaterial({
    map: loader.load('https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/Flag_of_Kazakhstan_%283-2%29.svg/2560px-Flag_of_Kazakhstan_%283-2%29.svg.png'),
  });
  const cube = new THREE.Mesh(geometry, material);
  scene.add(cube);
  cubes.push(cube);  // add to our list of cubes to rotate

  function resizeRendererToDisplaySize(renderer) {
    const canvas = renderer.domElement;
    const width = canvas.clientWidth;
    const height = canvas.clientHeight;
    const needResize = canvas.width !== width || canvas.height !== height;
    if (needResize) {
      renderer.setSize(width, height, false);
    }
    return needResize;
  }

  function render(time) {
    time *= 0.001;

    if (resizeRendererToDisplaySize(renderer)) {
      const canvas = renderer.domElement;
      camera.aspect = canvas.clientWidth / canvas.clientHeight;
      camera.updateProjectionMatrix();
    }

    cubes.forEach((cube, ndx) => {
      const speed = .2 + ndx * .1;
      const rot = time * speed;
      cube.rotation.x = rot;
      cube.rotation.y = rot;
    });

     renderer.setClearColor( 0x000000, 0 );
    renderer.render(scene, camera);

    requestAnimationFrame(render);
  }

  requestAnimationFrame(render);
}

main();
    </script>
  </body>
</html>