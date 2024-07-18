<!-- Login button to trigger modal -->
<button id="loginButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
    Login
</button>

<div id="loginModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white p-8 rounded shadow-lg max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Login</h2>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="loginForm" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>
                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // JavaScript to show/hide modal
    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('loginModal').classList.add('hidden');
    });

    document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    let username = document.getElementById('username').value;
    let password = document.getElementById('password').value;

    // Create FormData object to send data via POST
    let formData = new FormData();
    formData.append('username', username);
    formData.append('password', password);

    // Send AJAX request to handleLogin.php
    fetch('../src/fetches/handleLogin.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Handle successful login response
        console.log(data);
        if (data.success) {
            // Update UI to show logged in state
            changeLoginToLogoutButton()

            // Close the modal
            document.getElementById('loginModal').classList.add('hidden');
        } else {
            // Handle login failure (display error message, etc.)
            console.log('Login failed:', data.error);
            // Show error message to the user
            alert(data.error);
        }
    })
    .catch(error => {
        // Handle network errors or server-side errors
        console.error('There was a problem with the fetch operation:', error);
        // Show error message to the user
        alert('An error occurred while trying to log in. Please try again later.');
    });
});

    // Show modal when loginButton is clicked
    document.getElementById('loginButton').addEventListener('click', showModal);

    function showModal() {
        document.getElementById('loginModal').classList.remove('hidden');
        document.getElementById('username').focus(); // Optional: Focus on username input
    }

    function logout() {
        // Send AJAX request to handlelogout.php
        fetch('../src/fetches/handlelogout.php', {
            method: 'GET'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            window.location.href = '../../index.php'; // Redirect to the home page after logging out
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            // Show error message to the user
            alert('An error occurred while trying to log out. Please try again later.');
        });
    }
</script>
