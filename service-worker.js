self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    // Handle the notification click event
    event.waitUntil(
      clients.openWindow('https://ianwang0410.github.io/')
    );
  });
  