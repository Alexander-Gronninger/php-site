<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'src/partials/header.php'; ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="src/functions/clearPosts.js"></script>
    <script src="src/functions/changeLoginToLogoutButton.js"></script>
    <script src="src/functions/fetchPosts.js"></script>
    <script src="src/functions/applyFilters.js"></script>
    <script src="src/functions/fetchCategories.js"></script>
    <script src="src/functions/spinner.js"></script>
    <script src="src/functions/handleScroll.js"></script>
    <script src="src/functions/appendPosts.js"></script>
    <script src="src/functions/getCategoryClass.js"></script>
    <script src="src/functions/initialize.js"></script>
    <script src="src/partials/postDetails.js"></script>
    <script>
        const loggedInUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
        console.log(loggedInUserId);
        document.addEventListener('DOMContentLoaded', () => {
    if (loggedInUserId) {
        changeLoginToLogoutButton();
    }
});
    </script>

</head>
<body>
    <div class="bg-gray-300 max-w-[720px] m-auto p-2">
        <h1 class="m-auto max-w-fit bold text-xl">MyMediaSite</h1>

        <div class="flex flex-row my-4 ml-auto w-fit">
            <!-- login button and modal -->
            <?php include 'src/pages/login_modal.php'; ?>
            <!-- distance between buttons -->
            <div class="w-4"></div>
            <!-- user button and modal -->
            <?php include 'src/pages/user_page.php'; ?>
            <!-- register account button and modal -->
            <?php include 'src/pages/register_modal.php'; ?>
        </div>
        <?php include 'src/pages/createPost.php'; ?>

<!-- search for posts -->
<form id="searchForm">
    <div>
        <input type="text" id="searchInput" placeholder="Search...">
        <button class="border-gray-500 border-2 bg-white p-[2px] rounded-xl"type="button" onclick="clearPosts(); fetchPosts();">Search</button>
    </div>
    <div class="flex flex-col">
        <label class="w-fit"><input type="checkbox" checked id="searchTitleCheckbox"> Search in Title</label>
        <label class="w-fit"><input type="checkbox" checked id="searchContentCheckbox"> Search in Content</label>
        <label class="w-fit"><input type="checkbox" checked id="searchAuthorCheckbox"> Search by Author</label>
    </div>
</form>

    <!-- Sort and Category dropdowns -->
    <?php include 'src/partials/dropdowns.php'; ?>

    <!-- Posts will be from fetching added here -->
     <div>
         <div id="postContainer">
    </div>

    <!-- Loading spinner -->
    <p class="text-center p-[10px] bold" id="loading">Loading...</p>

    <div id="fullPostContainer" class="hidden bg-white p-4 mb-4 rounded shadow">
        <button onclick="hideFullPost()">Back</button>
        <div id="fullPostContent"></div>
        <div id="commentsContainer"></div>
    </div>
</div>
</body>
</html>
