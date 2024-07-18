// lazyloading, not really a spinner, meh
function showLoadingSpinner() {
  let loadingElement = document.getElementById("loading");
  if (loadingElement) {
    loadingElement.style.display = "block";
  }
}

function hideLoadingSpinner() {
  let loadingElement = document.getElementById("loading");
  if (loadingElement) {
    loadingElement.style.display = "none";
  }
}
