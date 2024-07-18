<!-- Button to open the modal -->
<button id="createPostButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded hidden">
  Create Post
</button>

<!-- Modal structure -->
<div id="createPostModal" class="hidden fixed z-10 inset-0 overflow-y-auto">
  <div class="flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-lg p-6 max-w-lg w-full shadow-lg">
      <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold">Create New Post</h2>
        <button id="closeModalButton" class="text-gray-500 hover:text-gray-700">&times;</button>
      </div>
      <form id="createPostForm" class="mt-4">
        <div class="mb-4">
          <label for="postTitle" class="block text-sm font-medium text-gray-700">Title</label>
          <input type="text" id="postTitle" name="title" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
        </div>
        <div class="mb-4">
          <label for="postContent" class="block text-sm font-medium text-gray-700">Content</label>
          <textarea id="postContent" name="content" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
        </div>
        <div class="mb-4">
          <label for="postCategory" class="block text-sm font-medium text-gray-700">Category</label>
          <select id="postCategory" name="category" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
            <!-- Categories will be dynamically populated here -->
          </select>
        </div>
        <div class="flex justify-end">
          <button type="button" id="cancelPostButton" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Cancel</button>
          <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", () => {
  // get var for elements
  const createPostButton = document.getElementById("createPostButton");
  const createPostModal = document.getElementById("createPostModal");
  const closeModalButton = document.getElementById("closeModalButton");
  const cancelPostButton = document.getElementById("cancelPostButton");
  const createPostForm = document.getElementById("createPostForm");
  const postCategorySelect = document.getElementById("postCategory");

  // Show the button only if the user is logged in
  if (loggedInUserId) {
    createPostButton.classList.remove("hidden");
  }

  createPostButton.addEventListener("click", () => {
    createPostModal.classList.remove("hidden");
    fetchCategories(); // Fetch categories when the modal is opened
  });

  // hide modal
  closeModalButton.addEventListener("click", () => {
    createPostModal.classList.add("hidden");
  });

  // cancel creation
  cancelPostButton.addEventListener("click", () => {
    createPostModal.classList.add("hidden");
  });

  // submit post
  createPostForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const formData = new FormData(createPostForm);

    fetch("src/fetches/handleCreatePost.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Post created successfully");
          location.reload(); // Reload the page to see the new post
        } else {
          alert("Error: " + data.error);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });

  // fetch categories and append to category dropdown, could probably DRY the category dropdown but vanilla javascript sucks
  function fetchCategories() {
    fetch("src/fetches/fetch_categories.php")
      .then(response => response.json())
      .then(data => {
        if (data.categories) {
          postCategorySelect.innerHTML = '<option value="" disabled selected>Select a category</option>'; // Default option
          data.categories.forEach(category => {
            const option = document.createElement("option");
            option.value = category.id;
            option.textContent = category.name;
            postCategorySelect.appendChild(option);
          });
        } else {
          console.error("Failed to fetch categories.");
        }
      })
      .catch(error => console.error("Error fetching categories:", error));
  }
});
</script>
