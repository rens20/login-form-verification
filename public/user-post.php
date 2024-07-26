<?php
session_start();
include('../config/conn.php');
include('../model/header.php');
include('../model/sidebar.php');
?>

<div class="container mx-auto p-6 bg-white shadow-md rounded-lg max-w-3xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-4 text-center">Create Post</h1>

    <form action="./functions/post-process.php" method="post" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label for="community" class="block text-sm font-medium text-gray-700">Community:</label>
            <select id="community" name="community" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <!-- Options will be populated via JavaScript -->
            </select>
        </div>

        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
            <input type="text" id="title" name="title" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div>
            <label for="text" class="block text-sm font-medium text-gray-700">Text:</label>
            <textarea id="text" name="text" rows="4" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
        </div>

        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">Image (optional):</label>
            <input type="file" id="image" name="image" accept="image/*"
                class="mt-1 block w-full text-gray-500 file:py-2 file:px-4 file:border file:border-gray-300 file:rounded-md file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
        </div>

        <div class="text-center">
            <button type="submit" class="w-1/2 px-4 py-2 bg-black text-white font-semibold rounded-md shadow-sm">Post</button>
        </div>
    </form>
</div>

<?php include('../model/footer.php'); ?>

<script>
    // Fetch and populate the communities dropdown
    fetch('../community/get-user-communities.php')
        .then(response => response.json())
        .then(data => {
            const communitySelect = document.getElementById('community');
            data.forEach(community => {
                const option = document.createElement('option');
                option.value = community.id;
                option.textContent = community.name;
                communitySelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching communities:', error);
        });
</script>
