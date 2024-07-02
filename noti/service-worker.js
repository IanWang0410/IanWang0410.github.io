self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    event.waitUntil(
      clients.openWindow('https://ianwang0410.github.io/')
    );
  });
  