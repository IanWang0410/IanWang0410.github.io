document.addEventListener("DOMContentLoaded", () => {
    // Fetch and display user information upon page load
    fetchUserInfo();

    const logoutButton = document.getElementById('logout_button');
    logoutButton.addEventListener('click', () => {
        logoutUser();
    });
});

function fetchUserInfo() {
    let userEmail = "123'@'123.com";

    fetch('http://localhost/gas/api/Find_Customer_ID.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        // Send the necessary information, like customer ID, as JSON
        body: 'email=' + encodeURIComponent(userEmail),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
    })
    .catch(error => {
        console.error('Error fetching user info:', error);
    });
}

function logoutUser() {
    // Here you can clear session storage, cookies or any authentication tokens
    sessionStorage.removeItem('userToken'); // For example
    
    // Redirect to login page or perform cleanup
    window.location.href = 'login.html'; // Modify as necessary
}