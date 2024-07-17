// initialize.js
document.addEventListener("DOMContentLoaded", function () {
  fetchCategories();
  fetchPosts();
  window.addEventListener("scroll", handleScroll);
});
