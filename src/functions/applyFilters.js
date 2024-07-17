// filters.js
function applyFilters() {
  currentSort = document.getElementById("sortDropdown").value;
  currentCategory = document.getElementById("categoryDropdown").value;
  offset = 0;
  document.getElementById("postContainer").innerHTML = "";
  fetchPosts();
}
