<?php
session_start();
include('../config/conn.php');
include('../model/header.php');
include('../model/sidebar.php');
?>

<div class="flex">
    <div class="w-1/4">
        <!-- Sidebar content -->
    </div>
    <div class="w-3/4 flex justify-center">
        <div class="p-4 w-full max-w-lg">
            <h1 class="text-2xl font-bold mb-4">Create a Community</h1>
            <form id="create-community-form" class="p-4 bg-gray-100 rounded-lg shadow-md">
                <input type="text" name="name" placeholder="Community Name" class="mb-2 p-2 w-full border rounded" required>
                <textarea name="description" placeholder="Community Description" class="mb-2 p-2 w-full border rounded"></textarea>
                <button type="submit" class="bg-blue-500 text-white p-2 rounded">Create Community</button>
            </form>

            <h1 class="text-2xl font-bold mt-8 mb-4">Search for Communities</h1>
            <input type="text" id="search-query" placeholder="Search Communities" class="p-2 w-full border rounded">
            <div id="search-results" class="mt-4 space-y-2"></div>
        </div>
    </div>
</div>

<script>
document.getElementById('create-community-form').addEventListener('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(this);

    fetch('libs/create_community.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => alert(data))
    .catch(error => console.error('Error:', error));
});

document.getElementById('search-query').addEventListener('input', function() {
    var query = this.value;

    fetch('libs/search_community.php?query=' + query)
    .then(response => response.json())
    .then(data => {
        var results = document.getElementById('search-results');
        results.innerHTML = '';
        data.forEach(community => {
            var div = document.createElement('div');
            div.className = 'p-4 bg-white rounded-lg shadow-md';
            div.innerHTML = `<h3 class="text-xl font-bold">${community.name}</h3><p class="text-gray-700">${community.description}</p><button onclick="joinCommunity(${community.id})" class="mt-2 bg-green-500 text-white p-2 rounded">Join</button>`;
            results.appendChild(div);
        });
    })
    .catch(error => console.error('Error:', error));
});

function joinCommunity(communityId) {
    fetch('libs/join_community.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'community_id=' + communityId
    })
    .then(response => response.text())
    .then(data => alert(data))
    .catch(error => console.error('Error:', error));
}
</script>

<?php include('../model/footer.php'); ?>
