<button id="userButton" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4 hidden">User Page</button>

<div id="userPage" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white p-8 rounded shadow-lg max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">User Page</h2>
                <button id="closeUserPage" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="userContent">
                <!-- User's Posts Section -->
                <h3 class="text-md font-bold mb-2">My Posts</h3>
                <div id="userPosts"></div>

                <!-- User's Comments Section -->
                <h3 class="text-md font-bold mb-2 mt-4">My Comments</h3>
                <div id="userComments"></div>

                <!-- Edit Account Section -->
                <h3 class="text-md font-bold mb-2 mt-4">Edit Account</h3>
                <form id="editAccountForm" class="space-y-4">
                    <div>
                        <label for="newPassword" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" id="newPassword" name="newPassword" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                            Change Password
                        </button>
                    </div>
                </form>

                <!-- Delete Account Section -->
                <h3 class="text-md font-bold mb-2 mt-4">Delete Account</h3>
                <form id="deleteAccountForm" class="space-y-4">
                    <div>
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-full">
                            Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to show/hide user page modal
    document.getElementById('closeUserPage').addEventListener('click', function() {
        document.getElementById('userPage').classList.add('hidden');
    });

    // Fetch user's posts and comments when the modal is shown
    function loadUserData() {
        fetch('src/fetches/getUserData.php')
        .then(response => response.json())
        .then(data => {
            // Populate posts and comments sections
            const userPosts = document.getElementById('userPosts');
            userPosts.innerHTML = data.posts.map(post => `<p><a href="#" onclick="fetchPostDetails(${post.id}); document.getElementById('userPage').classList.add('hidden');">${post.title}</a></p>`).join('');
            
            const userComments = document.getElementById('userComments');
            userComments.innerHTML = data.comments.map(comment => `<p><a href="#" onclick="fetchPostDetails(${comment.post_id}); document.getElementById('userPage').classList.add('hidden');">${comment.content}</a></p>`).join('');
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    document.getElementById('editAccountForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('src/fetches/handleEditAccount.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Password changed successfully');
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    document.getElementById('deleteAccountForm').addEventListener('submit', function(event) {
        event.preventDefault();
        fetch('src/fetches/handleDeleteAccount.php', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Account deleted successfully');
                window.location.href = 'index.php';
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Show user page modal when userButton is clicked
    const userButton = document.getElementById('userButton');
    userButton.addEventListener('click', function() {
        document.getElementById('userPage').classList.remove('hidden');
        loadUserData();
    });  
</script>
