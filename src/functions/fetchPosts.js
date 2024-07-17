// fetchPosts.js

let offset = 0;
let limit = 0;
let isLoading = false;
let currentSort = "newest";
let currentCategory = "all";

function fetchPosts() {
  if (isLoading) return;
  isLoading = true;
  showLoadingSpinner();

  let searchQuery = document.getElementById("searchInput").value;
  let searchThroughTitle = document.getElementById("searchTitleCheckbox")
    .checked
    ? 1
    : 0;
  let searchThroughContent = document.getElementById("searchContentCheckbox")
    .checked
    ? 1
    : 0;
  let searchThroughAuthor = document.getElementById("searchAuthorCheckbox")
    .checked
    ? 1
    : 0;

  let xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    `../src/fetches/fetch_posts.php?offset=${offset}&sort=${currentSort}&category=${currentCategory}&search=${encodeURIComponent(
      searchQuery
    )}&search_title=${searchThroughTitle}&search_content=${searchThroughContent}&search_author=${searchThroughAuthor}`,
    true
  );
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
  xhr.onload = function () {
    if (this.status >= 200 && this.status < 400) {
      let data = JSON.parse(this.responseText);
      limit = data.limit;
      if (data.posts.length > 0) {
        appendPosts(data.posts);
        offset += limit;
        hideLoadingSpinner();

        setTimeout(() => {
          if (document.body.scrollHeight <= window.innerHeight) {
            fetchPosts();
          }
        }, 500);
      } else {
        console.log("no posts available");
        window.removeEventListener("scroll", handleScroll);
        showLoadingSpinner();
        //if postContainer has children, then there are posts
        let postContainer = document.getElementById("postContainer");
        let loadingElement = document.getElementById("loading");
        if (loadingElement) {
          if (searchQuery && postContainer.childElementCount == 0) {
            loadingElement.innerHTML =
              "No posts match search query: " + searchQuery;
          } else if (postContainer.childElementCount > 0) {
            loadingElement.innerHTML = "No more posts available";
          } else {
            loadingElement.innerHTML = "No more posts.";
          }
        } else {
          console.error("Loading element not found.");
        }
      }
    } else {
      console.error("Error loading posts.");
    }
    isLoading = false;
  };
  xhr.send();
}
