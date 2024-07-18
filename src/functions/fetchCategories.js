function fetchCategories() {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "../src/fetches/fetch_categories.php", true);
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
  xhr.onload = function () {
    if (this.status >= 200 && this.status < 400) {
      let data = JSON.parse(this.responseText);
      let categoryDropdown = document.getElementById("categoryDropdown");
      data.categories.forEach((category) => {
        let option = document.createElement("option");
        option.value = category.name;
        option.text =
          category.name.charAt(0).toUpperCase() + category.name.slice(1);
        categoryDropdown.appendChild(option);
      });
    } else {
      console.error("Error fetching categories.");
    }
  };
  xhr.send();
}
