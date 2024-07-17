// scroll.js
function handleScroll() {
  if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
    fetchPosts();
  }
}
