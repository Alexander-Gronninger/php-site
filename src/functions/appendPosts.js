// receives an array of posts and spits it out in HTML
function appendPosts(posts) {
  let postContainer = document.getElementById("postContainer");

  posts.forEach((post) => {
    let postDiv = document.createElement("div");
    postDiv.className = "bg-white p-4 mb-4 rounded shadow";
    postDiv.innerHTML = `
      <h2 class="text-lg font-bold">${post.title}</h2>
      <p class="text-sm text-gray-600">Author: ${post.author}</p>
      <div class="text-sm text-gray-400 bold">
        Posted on ${post.created_at}
      </div>

      <div class="relative text-sm border-solid border-black border-[1px] rounded-lg h-fit w-fit uppercase p-[2px] ${getCategoryClass(
        post.category_name
      )} group">
  ${post.category_name}
  <!-- Tooltip/Description -->
  <span class="absolute bottom-[125%] left-1/2 transform -translate-x-1/2 bg-black text-white text-center rounded-md py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
    ${post.category_description}
  </span>
  </div>
      <div class="post-content">
        <p class="text-gray-800">${truncatePostContent(post.content)}</p>
        ${
          post.content.length > 465
            ? '<a href="#" class="read-more bold underline underline-offset-2">Read more...</a>'
            : ""
        }
      </div>
    `;
    postDiv.addEventListener("click", () => fetchPostDetails(post.id));
    postContainer.appendChild(postDiv);
  });

  // Function to truncate post content if necessary
  function truncatePostContent(content) {
    if (content.length > 465) {
      return `${content.slice(0, 465)}...`;
    } else {
      return content;
    }
  }
}
