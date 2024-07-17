// getCategoryClass.js
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
