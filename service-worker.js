self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    // Handle the notification click event
    event.waitUntil(
      clients.openWindow('https://cdn.discordapp.com/attachments/562974113988214815/1246766268527149177/image.png?ex=665d9512&is=665c4392&hm=5fed14358de299e8a8eeb51cce3748b23fe522aff77bbfc00aa4c5083f05d1d8&') // Redirect to your website
    );
  });
  