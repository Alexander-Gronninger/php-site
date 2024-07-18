function clearPosts() {
  let postContainer = document.getElementById("postContainer");
  postContainer.innerHTML = ""; // Clear existing posts

  // if clearPosts is executed, then we must reset offset, otherwise the fetchPosts function will act as if there are posts already on the site
  offset = 0;

  // if the user has reached end of posts, then the event listener is removed
  // this ensures it is readded when posts are cleared, after removing it once just in case its already there
  window.removeEventListener("scroll", handleScroll);
  window.addEventListener("scroll", handleScroll);

  let loadingElement = document.getElementById("loading");
  if (loadingElement) {
    loadingElement.innerHTML = "No more posts.";
  } else {
    console.error("Loading element not found.");
  }
}
