<x-app-layout>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div id="model-container" class="w-full h-[550px] flex justify-center items-center"></div>
            <div class="flex justify-center mt-4">
                <button id="change-model" class="px-4 py-2 bg-black text-white rounded">Change Model</button>
            </div>
            <!-- <div class="flex justify-center mt-4">
                <label for="saturation-slider" class="mr-2">Saturation:</label>
                <input class="accent-black" type="range" id="saturation-slider" min="-0.99999" max="1" step="0.01" value="0">
            </div>
            <div class="flex justify-center mt-4">
                <label for="brightness-slider" class="mr-2">Brightness:</label>
                <input class="accent-black" type="range" id="brightness-slider" min="-0.99999" max=".99999" step="0.01" value="0">
            </div> -->
        </div>
    </div>
</x-app-layout>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
<script>
    // Scene setup
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / 500, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ alpha: true });
    renderer.setSize(window.innerWidth, 500);
    renderer.setClearColor(0x000000, 0); // Set background to transparent
    document.getElementById('model-container').appendChild(renderer.domElement);

    // Lights
    const directionalLight1 = new THREE.DirectionalLight(0xffffff, 3);
    directionalLight1.position.set(5, 5, 5).normalize();
    scene.add(directionalLight1);

    const directionalLight2 = new THREE.DirectionalLight(0xffffff, 3);
    directionalLight2.position.set(-5, 5, 5).normalize();
    scene.add(directionalLight2);

    const directionalLight3 = new THREE.DirectionalLight(0xffffff, 2);
    directionalLight3.position.set(5, -5, 5).normalize();
    scene.add(directionalLight3);

    const directionalLight4 = new THREE.DirectionalLight(0xffffff, 2);
    directionalLight4.position.set(5, 5, -5).normalize();
    scene.add(directionalLight4);

    // Load GLTF model
    let frog;
    const loader = new THREE.GLTFLoader();
    const models = [
        '/Models/Frog.gltf',
        '/Models/Hamburger.gltf',
        '/Models/Bulbasaur.gltf',
        '/Models/Pokeball.gltf'
    ];
    let randomModel = models[Math.floor(Math.random() * models.length)];
    loader.load(randomModel, function (gltf) {
        frog = gltf.scene;
        frog.userData.currentModel = randomModel;
        setModelProperties(randomModel);
        scene.add(frog);
        renderer.render(scene, camera);
    }, undefined, function (error) {
        console.error(error);
    });

    camera.position.z = 50; // Move the camera far away

    // Animation loop
    function animate() {
        requestAnimationFrame(animate);
        if (frog) {
            frog.rotation.y += 0.004; // Rotate the frog more slowly
        }
        renderer.render(scene, camera);
    }
    animate();

    // Interaction
    let isDragging = false;
    let previousMousePosition = {
        x: 0,
        y: 0
    };

    renderer.domElement.addEventListener('mousedown', function(e) {
        isDragging = true;
    });

    renderer.domElement.addEventListener('mousemove', function(e) {
        if (isDragging) {
            const deltaMove = {
                x: e.offsetX - previousMousePosition.x,
                y: e.offsetY - previousMousePosition.y
            };

            const deltaRotationQuaternion = new THREE.Quaternion()
                .setFromEuler(new THREE.Euler(
                    toRadians(deltaMove.y * 1),
                    toRadians(deltaMove.x * 1),
                    0,
                    'XYZ'
                ));

            frog.quaternion.multiplyQuaternions(deltaRotationQuaternion, frog.quaternion);
        }

        previousMousePosition = {
            x: e.offsetX,
            y: e.offsetY
        };
    });

    renderer.domElement.addEventListener('mouseup', function(e) {
        isDragging = false;
    });

    renderer.domElement.addEventListener('mouseleave', function(e) {
        isDragging = false;
    });

    function toRadians(angle) {
        return angle * (Math.PI / 180);
    }

    function setModelProperties(model) {
        if (model === '/Models/Hamburger.gltf' || model === '/Models/Bulbasaur.gltf') {
            frog.traverse((node) => {
                if (node.isMesh) {
                    node.material.color.offsetHSL(0, 0, 0); // Reduce saturation by half
                }
            });
            if (model === '/Models/Hamburger.gltf') {
                frog.scale.set(1.5, 1.5, 1.5); // Make the hamburger model smaller
                camera.position.z = 25; // Move the camera closer for the hamburger model
                camera.position.y = 5; // Move the camera lower for the hamburger model
            } else if (model === '/Models/Bulbasaur.gltf') {
                frog.scale.set(30, 30, 30); // Make the Snorlax model smaller
                camera.position.z = 40; // Move the camera closer for the Snorlax model
                camera.position.y = 5; // Move the camera lower for the Snorlax model
            } else if (model === '/Models/Pokeball.gltf') {
                frog.scale.set(75, 75, 75); // Make the Snorlax model smaller
                camera.position.z = 25; // Move the camera closer for the Snorlax model
                camera.position.y = 5; // Move the camera lower for the Snorlax model
            }
        } else if (model === '/Models/Pokeball.gltf') {
            frog.scale.set(40, 40, 40); // Make the Snorlax model smaller
            camera.position.z = 25; // Move the camera closer for the Snorlax model
            camera.position.y = 0; // Move the camera lower for the Snorlax model
        } else {
            frog.scale.set(1, 1, 1); // Default scale for other models
            camera.position.z = 50; // Default camera position
            camera.position.y = 0; // Default camera height
        }
    }
</script>
<script>
    document.getElementById('change-model').addEventListener('click', function() {
        let randomModel;
        do {
            randomModel = models[Math.floor(Math.random() * models.length)];
        } while (randomModel === frog.userData.currentModel);

        loader.load(randomModel, function (gltf) {
            scene.remove(frog);
            frog = gltf.scene;
            frog.userData.currentModel = randomModel;
            setModelProperties(randomModel);
            scene.add(frog);
            renderer.render(scene, camera);

            // Reset sliders to default values
            document.getElementById('saturation-slider').value = 0;
            document.getElementById('brightness-slider').value = 0;
        }, undefined, function (error) {
            console.error(error);
        });
    });
</script>
<script>
    const saturationSlider = document.getElementById('saturation-slider');
    const brightnessSlider = document.getElementById('brightness-slider');

    saturationSlider.addEventListener('input', function() {
        if (frog) {
            frog.traverse((node) => {
                if (node.isMesh) {
                    node.material.color.setHSL(
                        node.material.color.getHSL().h,
                        Math.max(0, 1 + parseFloat(saturationSlider.value)),
                        node.material.color.getHSL().l
                    );
                }
            });
            renderer.render(scene, camera);
        }
    });

    brightnessSlider.addEventListener('input', function() {
        if (frog) {
            frog.traverse((node) => {
                if (node.isMesh) {
                    node.material.color.setHSL(
                        node.material.color.getHSL().h,
                        node.material.color.getHSL().s,
                        Math.max(0, 1 + parseFloat(brightnessSlider.value))
                    );
                }
            });
            renderer.render(scene, camera);
        }
    });
</script>