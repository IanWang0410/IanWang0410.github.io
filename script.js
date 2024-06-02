// Register the service worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('service-worker.js')
    .then(function(registration) {
      console.log('Service Worker registered with scope:', registration.scope);
    }).catch(function(error) {
      console.error('Service Worker registration failed:', error);
    });
  }
  
  // Request Notification permission
  Notification.requestPermission().then(function(permission) {
    if (permission === 'granted') {
      console.log('Notification permission granted.');
    } else {
      console.log('Notification permission denied.');
    }
  });
  
  // Add click event listener to the button
  document.getElementById('notify-btn').addEventListener('click', function() {
    if ('serviceWorker' in navigator && 'PushManager' in window) {
      navigator.serviceWorker.ready.then(function(registration) {
        registration.showNotification('Button Clicked', {
          body: 'This is a noti',
        });
      });
    }
  });
  