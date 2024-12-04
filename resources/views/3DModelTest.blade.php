<x-app-layout>
    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div id="model-container" class="flex justify-center items-center"></div>
        </div>
    </div>
</x-app-layout>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

<script>
    // Scene setup
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ alpha: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setClearColor(0x000000, 0); // Set clear color to black with zero alpha
    document.getElementById('model-container').appendChild(renderer.domElement);

    // Lighting
    const frontLight = new THREE.DirectionalLight(0xffffff, 3); // Increase intensity
    frontLight.position.set(-5, 5, -5).normalize();
    scene.add(frontLight);

    const backLight = new THREE.DirectionalLight(0xffffff, 3); // Increase intensity
    backLight.position.set(5, -5, 5).normalize();
    scene.add(backLight);

    const ambientLight = new THREE.AmbientLight(0xffffff, 2); // Increase intensity
    scene.add(ambientLight);

    // Add PointLight for light in all directions
    const pointLight = new THREE.PointLight(0xffffff, 1, 100); // Adjust intensity and distance as needed
    pointLight.position.set(0, 10, 0);
    scene.add(pointLight);

    // Load GLTF model
    const loader = new THREE.GLTFLoader();
    loader.load('/Models/Frog.gltf', function (gltf) {
        const model = gltf.scene;
        scene.add(model);
        renderer.render(scene, camera);
    }, undefined, function (error) {
        console.error(error);
    });

    // Camera position
    camera.position.z = 100; // Move the camera closer to the model

    // OrbitControls
    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true; // an animation loop is required when either damping or auto-rotation are enabled
    controls.dampingFactor = 0.25;
    controls.screenSpacePanning = false;
    controls.maxPolarAngle = Math.PI / 2;

    // Animation loop
    function animate() {
        requestAnimationFrame(animate);
        controls.update(); // only required if controls.enableDamping = true, or if controls.autoRotate = true
        renderer.render(scene, camera);
    }
    animate();
</script>
<div class="controls">
    <label for="brightness">Brightness:</label>
    <input type="range" id="brightness" name="brightness" min="0" max="2" step="0.01" value="1.5"> <!-- Increase initial value -->
    <label for="saturation">Saturation:</label>
    <input type="range" id="saturation" name="saturation" min="0" max="2" step="0.01" value="1.5"> <!-- Increase initial value -->
</div>

<script>
    const brightnessSlider = document.getElementById('brightness');
    const saturationSlider = document.getElementById('saturation');

    function updateModelAppearance() {
        const brightness = parseFloat(brightnessSlider.value);
        const saturation = parseFloat(saturationSlider.value);

        scene.traverse(function (child) {
            if (child.isMesh) {
                child.material.color.setHSL(0, saturation, brightness);
            }
        });
    }

    brightnessSlider.addEventListener('input', updateModelAppearance);
    saturationSlider.addEventListener('input', updateModelAppearance);
</script>
