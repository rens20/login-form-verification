
        window.onclick = function(event) {
            if (!event.target.matches('.block')) {
                var dropdowns = document.getElementsByClassName("dropdown-menu");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === 'block') {
                        openDropdown.style.display = 'none';
                    }
                }
            }
        }
    document.getElementById('sidebar-toggle').addEventListener('click', function () {
        var sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('sidebar-hidden');
        sidebar.classList.toggle('sidebar-visible');
        var content = document.querySelector('.main-content');
        content.classList.toggle('ml-64');
        this.textContent = this.textContent === '>' ? '<' : '>';
    });

    function toggleDropdown(event, dropdownId) {
        event.preventDefault();
        var dropdown = document.getElementById(dropdownId);
        dropdown.classList.toggle('visible');
    }

    function editProfilePopup() {
        Swal.fire({
            title: 'Edit Profile',
            html: `
                <form id="edit-profile-form" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="profile_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
                        <input type="file" name="profile_image" id="profile_image" class="mt-1 block w-full">
                    </div>
                    <div class="mb-4">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save Changes</button>
                </form>
            `,
            showConfirmButton: false
        });

        document.getElementById('edit-profile-form').addEventListener('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);

            fetch('../public/utils/update-profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('Failed')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Profile updated successfully!'
                    }).then(() => {
                        window.location.reload();
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating the profile.'
                });
            });
        });
    }
//for create community 
function createCommunity() {
        Swal.fire({
            title: 'Edit Profile',
            html: `
                <form id="create-comunnity-form" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="profile_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
                        <input type="file" name="profile_image" id="profile_image" class="mt-1 block w-full">
                    </div>
                    <div class="mb-4">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save Changes</button>
                </form>
            `,
            showConfirmButton: false
        });

        document.getElementById('create-comunnity-form').addEventListener('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);

            fetch('../public/utils/update-profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('Failed')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Profile updated successfully!'
                    }).then(() => {
                        window.location.reload();
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating the profile.'
                });
            });
        });
    }
