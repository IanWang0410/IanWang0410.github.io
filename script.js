// Register the service worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('service-worker.js', {scope : '/'})
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
  document.getElementById('normal').addEventListener('click', function() {
    if ('serviceWorker' in navigator && 'PushManager' in window) {
      navigator.serviceWorker.ready.then(function(registration) {
        registration.showNotification('Notification!', {
          body: 'This is a noti',
          icon: 'https://cdn.discordapp.com/attachments/562974113988214815/1246766268527149177/image.png?ex=665d9512&is=665c4392&hm=5fed14358de299e8a8eeb51cce3748b23fe522aff77bbfc00aa4c5083f05d1d8&'
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
                icon: 'https://cdn.discordapp.com/attachments/562974113988214815/1246766268527149177/image.png?ex=665d9512&is=665c4392&hm=5fed14358de299e8a8eeb51cce3748b23fe522aff77bbfc00aa4c5083f05d1d8&'
            });
        },5000);
      });
    }
  });
  