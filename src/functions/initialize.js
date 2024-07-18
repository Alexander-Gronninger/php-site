// once document is loaded we need to fetch:
// categories for the category drop down
// posts for the posts page
document.addEventListener("DOMContentLoaded", function () {
  fetchCategories();
  fetchPosts();
  window.addEventListener("scroll", handleScroll);
});
