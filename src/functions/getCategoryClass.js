// each category has its own CSS styles, as defined in header.php - this translates the categories into the css classes, although tbf the css classes could just be the category name *shrug*
function getCategoryClass(categoryName) {
  switch (categoryName.toLowerCase()) {
    case "general":
      return "category-general";
    case "cars":
      return "category-cars";
    case "food":
      return "category-food";
    default:
      return "";
  }
}
