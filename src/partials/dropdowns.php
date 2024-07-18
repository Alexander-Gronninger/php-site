<!-- main page sort by and category dropdowns -->
<div class="flex flex-row m-2 justify-around">
    <div class="w-fit text-nowrap h-fit flex flex-row space-x-4">
        <label for="sortDropdown" class="block text-sm font-medium text-gray-700 text-center w-fit h-10 leading-10">Sort By:</label>
        <select id="sortDropdown" class="block w-full h-10 p-2 m-0 ml-[0px] border border-gray-300 rounded-xl" onchange="applyFilters()">
            <option value="newest">Newest Posts</option>
            <option value="oldest">Oldest Posts</option>
            <option value="alphabetical">Title Alphabetical</option>
        </select>
    </div>
    <div class="w-fit h-fit flex flex-row space-x-4">
        <label for="categoryDropdown" class="block text-sm font-medium text-gray-700 text-center w-fit h-10 leading-10">Category:</label>
        <select id="categoryDropdown" class="block w-full h-10 p-2 m-0 ml-[0px] border border-gray-300 rounded-xl" onchange="applyFilters()">
            <option value="all">All Categories</option>
        </select>
    </div>
</div>
