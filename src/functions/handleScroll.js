// if reached bottom of window load more posts
function handleScroll() {
  if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
    fetchPosts();
  }
}
