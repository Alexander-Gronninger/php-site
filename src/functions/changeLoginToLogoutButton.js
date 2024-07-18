function changeLoginToLogoutButton() {
  document.getElementById("loginButton").textContent = "Logout";
  registerButton.style.display = "none";
  userButton.style.display = "block";
  createPostButton.classList.remove("hidden");
  document
    .getElementById("loginButton")
    .removeEventListener("click", showModal);
  document.getElementById("loginButton").addEventListener("click", logout);
}
