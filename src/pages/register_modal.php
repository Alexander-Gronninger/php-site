    <!-- Registration button to trigger modal -->
    <button id="registerButton" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4 block">
        Register
    </button>

    <!-- Registration Modal -->
    <div id="registerModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white p-8 rounded shadow-lg max-w-md w-full">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold">Register</h2>
                    <button id="closeRegisterModal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="registerForm" class="space-y-4">
                    <div>
                        <label for="registerUsername" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" id="registerUsername" name="registerUsername" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="registerEmail" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="registerEmail" name="registerEmail" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="registerPassword" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="registerPassword" name="registerPassword" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    <div>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // JavaScript to show/hide register modal
document.getElementById('closeRegisterModal').addEventListener('click', function() {
    document.getElementById('registerModal').classList.add('hidden');
});

document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    fetch('src/fetches/handleRegister.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Registration successful and you are now logged in');
            document.getElementById('registerModal').classList.add('hidden');
            
            // Update UI for logged-in state
            changeLoginToLogoutButton()
        } else {
            alert('Registration failed: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

// Show register modal when registerButton is clicked
document.getElementById('registerButton').addEventListener('click', function() {
    document.getElementById('registerModal').classList.remove('hidden');
    document.getElementById('registerUsername').focus(); // Optional: Focus on username input
});
    </script>
