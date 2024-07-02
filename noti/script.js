if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('service-worker.js')
    .then(function(registration) {
      console.log('Service Worker registered with scope:', registration.scope);
    }).catch(function(error) {
      console.error('Service Worker registration failed:', error);
    });
  }

  Notification.requestPermission().then(function(permission) {
    if (permission === 'granted') {
      console.log('Notification permission granted.');
    } else {
      console.log('Notification permission denied.');
    }
  });
  
  document.getElementById('normal').addEventListener('click', function() {
    if ('serviceWorker' in navigator && 'PushManager' in window) {
      navigator.serviceWorker.ready.then(function(registration) {
        registration.showNotification('Notification!', {
          body: 'This is a noti',
          icon: 'image0.png'
        });
      });
    }
  });

  document.getElementById('five-seconds').addEventListener('click', function() {
    if ('serviceWorker' in navigator && 'PushManager' in window) {
      navigator.serviceWorker.ready.then(function(registration) {
        setTimeout(function(){
            registration.showNotification('Noti after 5 secs!', {
                body: 'This is another noti',
                icon: 'image0.png'
            });
        },5000);
      });
    }
  });
  