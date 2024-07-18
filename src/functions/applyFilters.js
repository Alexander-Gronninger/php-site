// on filters update(category or sort by), refetch posts with said filters, and reset offset
function applyFilters() {
  currentSort = document.getElementById("sortDropdown").value;
  currentCategory = document.getElementById("categoryDropdown").value;
  offset = 0;
  document.getElementById("postContainer").innerHTML = "";
  fetchPosts();
}
