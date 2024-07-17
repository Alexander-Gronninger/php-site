function clearPosts() {
  let postContainer = document.getElementById("postContainer");
  postContainer.innerHTML = ""; // Clear existing posts

  // if clearPosts is executed, then we must reset offset, otherwise the fetchPosts function will act as if there are posts already on the site
  offset = 0;

  // to account for an issue where an empty search results in scrolling not working. The search button always executes clearPosts
  window.removeEventListener("scroll", handleScroll);
  window.addEventListener("scroll", handleScroll);

  let loadingElement = document.getElementById("loading");
  if (loadingElement) {
    loadingElement.innerHTML = "No more posts.";
  } else {
    console.error("Loading element not found.");
  }
}
