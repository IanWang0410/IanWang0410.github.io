const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const context = canvas.getContext('2d');
const output = document.getElementById('output');

function setVideoDimensions() {
    const { width, height } = video.getBoundingClientRect();
    canvas.width = width;
    canvas.height = height;
}

navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
    .then(stream => {
        video.srcObject = stream;
        video.setAttribute('playsinline', true);
        video.play();
        setVideoDimensions(); // Set initial video dimensions
        window.addEventListener('resize', setVideoDimensions); // Update dimensions on window resize
        requestAnimationFrame(tick);
    });

function tick() {
    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height, {
            inversionAttempts: 'dontInvert',
        });
        if (code) {
            output.textContent = code.data;
        }
    }
    requestAnimationFrame(tick);
}
