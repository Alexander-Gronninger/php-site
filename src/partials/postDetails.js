// postDetails.js

function fetchPostDetails(postId) {
  showLoadingSpinner();
  let xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    `../src/fetches/fetch_post_details.php?post_id=${postId}`,
    true
  );
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
  xhr.onload = function () {
    if (this.status >= 200 && this.status < 400) {
      let response = this.responseText;
      try {
        let data = JSON.parse(response);
        if (data.error) {
          console.error("Error from server: " + data.error);
          alert("Failed to load post details.");
        } else {
          displayFullPost(data.post, data.comments);
        }
      } catch (e) {
        console.error("Failed to parse JSON: " + e.message);
        console.error("Response was: " + response);
      }
    } else {
      console.error("Error loading post details.");
    }
    hideLoadingSpinner();
  };
  xhr.send();
}

function displayFullPost(post, comments) {
  document.getElementById("postContainer").classList.add("hidden");
  document.getElementById("fullPostContainer").classList.remove("hidden");

  let fullPostContent = document.getElementById("fullPostContent");
  fullPostContent.innerHTML = `
    <h2 class="text-lg font-bold">${post.title}</h2>
    <div class="text-sm text-gray-600">Posted by ${post.author} on ${post.created_at}</div>
    <p class="text-gray-800">${post.content}</p>
  `;

  // Display edit and delete buttons for the post if logged-in user is the author
  if (post.author_id === loggedInUserId) {
    let editControls = `
      <button id="editPostButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">Edit Post</button>
      <button id="deletePostButton" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-2 ml-2">Delete Post</button>
      <div id="editPostForm" class="hidden mt-4 space-y-4">
        <input type="text" id="editPostTitle" value="${post.title}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
        <textarea id="editPostContent" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">${post.content}</textarea>
        <button type="button" id="savePostChanges" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        <button type="button" id="cancelEdit" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Cancel</button>
      </div>
    `;
    fullPostContent.insertAdjacentHTML("beforeend", editControls);

    // Attach event listeners for edit functionality
    document
      .getElementById("editPostButton")
      .addEventListener("click", function () {
        document.getElementById("editPostForm").classList.remove("hidden");
        this.classList.add("hidden");
      });

    document
      .getElementById("cancelEdit")
      .addEventListener("click", function () {
        document.getElementById("editPostForm").classList.add("hidden");
        document.getElementById("editPostButton").classList.remove("hidden");
      });

    document
      .getElementById("savePostChanges")
      .addEventListener("click", function () {
        let editedTitle = document.getElementById("editPostTitle").value;
        let editedContent = document.getElementById("editPostContent").value;
        savePostChanges(post.id, editedTitle, editedContent);
      });

    document
      .getElementById("deletePostButton")
      .addEventListener("click", function () {
        deletePost(post.id);
      });
  }

  // Comment section
  let commentsContainer = document.getElementById("commentsContainer");
  commentsContainer.innerHTML = "";

  // Display comments
  comments.forEach((comment) => {
    let commentDiv = document.createElement("div");
    commentDiv.className = "bg-gray-100 p-2 mb-2 rounded";
    commentDiv.innerHTML = `
      <div class="text-sm text-gray-600">Comment by ${comment.author} on ${comment.created_at}</div>
      <p class="text-gray-800">${comment.content}</p>
    `;

    // Display edit and delete buttons for the comment if logged-in user is the author
    if (comment.author_id === loggedInUserId) {
      let editControls = `
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mt-1">Edit</button>
        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded mt-1 ml-1">Delete</button>
        <div class="hidden mt-2 space-y-2" id="editCommentForm${comment.id}">
          <textarea class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="editCommentContent${comment.id}">${comment.content}</textarea>
          <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="saveCommentChanges(${comment.id})">Save Changes</button>
          <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded" onclick="cancelEditComment(${comment.id})">Cancel</button>
        </div>
      `;
      commentDiv.insertAdjacentHTML("beforeend", editControls);

      commentDiv
        .querySelector(".bg-blue-500")
        .addEventListener("click", function () {
          document
            .getElementById(`editCommentForm${comment.id}`)
            .classList.remove("hidden");
          this.classList.add("hidden");
        });

      commentDiv
        .querySelector(".bg-red-500")
        .addEventListener("click", function () {
          deleteComment(comment.id);
        });
    }

    commentsContainer.appendChild(commentDiv);
  });

  // Add comment form only if it doesn't already exist
  if (loggedInUserId) {
    if (!document.getElementById("commentFormContainer")) {
      let commentForm = `
        <div id="commentFormContainer" class="mt-4">
          <textarea id="newCommentContent" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" placeholder="Add a comment..."></textarea>
          <button id="submitCommentButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">Submit Comment</button>
        </div>
      `;
      commentsContainer.insertAdjacentHTML("beforeend", commentForm);

      document
        .getElementById("submitCommentButton")
        .addEventListener("click", function () {
          let commentContent =
            document.getElementById("newCommentContent").value;
          submitComment(post.id, commentContent);
        });
    }
  }
}

function hideFullPost() {
  document.getElementById("fullPostContainer").classList.add("hidden");
  document.getElementById("postContainer").classList.remove("hidden");
}

function savePostChanges(postId, editedTitle, editedContent) {
  let formData = new FormData();
  formData.append("post_id", postId);
  formData.append("title", editedTitle);
  formData.append("content", editedContent);

  fetch("../src/fetches/handleEditPost.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Post updated successfully");
        fetchPostDetails(postId); // Refresh post details after editing
      } else {
        alert("Error: " + data.error);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

function deletePost(postId) {
  let formData = new FormData();
  formData.append("post_id", postId);

  fetch("../src/fetches/handleDeletePost.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Post deleted successfully");
        hideFullPost();
      } else {
        alert("Error: " + data.error);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

function submitComment(postId, content) {
  let formData = new FormData();
  formData.append("post_id", postId);
  formData.append("content", content);

  fetch("../src/fetches/handleAddComment.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Comment added successfully");
        fetchPostDetails(postId); // Refresh post details after adding comment
      } else {
        alert("Error: " + data.error);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

function saveCommentChanges(commentId) {
  let editedContent = document.getElementById(
    `editCommentContent${commentId}`
  ).value;

  let formData = new FormData();
  formData.append("comment_id", commentId);
  formData.append("content", editedContent);

  fetch("../src/fetches/handleEditComment.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Comment updated successfully");
        fetchPostDetails(data.post_id); // Refresh post details after editing comment
      } else {
        alert("Error: " + data.error);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

function cancelEditComment(commentId) {
  document
    .getElementById(`editCommentForm${commentId}`)
    .classList.add("hidden");
  document.querySelector(`.bg-blue-500`).classList.remove("hidden");
}

function deleteComment(commentId) {
  let formData = new FormData();
  formData.append("comment_id", commentId);

  fetch("../src/fetches/handleDeleteComment.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Comment deleted successfully");
        fetchPostDetails(data.post_id); // Refresh post details after deleting comment
      } else {
        alert("Error: " + data.error);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}
