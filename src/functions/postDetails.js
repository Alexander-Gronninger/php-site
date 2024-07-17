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

  let commentsContainer = document.getElementById("commentsContainer");
  commentsContainer.innerHTML = "";
  comments.forEach((comment) => {
    let commentDiv = document.createElement("div");
    commentDiv.className = "bg-gray-100 p-2 mb-2 rounded";
    commentDiv.innerHTML = `
      <div class="text-sm text-gray-600">Comment by ${comment.author} on ${comment.created_at}</div>
      <p class="text-gray-800">${comment.content}</p>
    `;
    commentsContainer.appendChild(commentDiv);
  });
}

function hideFullPost() {
  document.getElementById("fullPostContainer").classList.add("hidden");
  document.getElementById("postContainer").classList.remove("hidden");
}
