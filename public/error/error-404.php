<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Error</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        let countdown = 5;
        function updateCountdown() {
            document.getElementById('countdown').innerText = countdown;
            if (countdown === 0) {
                window.location.href = '../user-post.php';
            } else {
                countdown--;
                setTimeout(updateCountdown, 1000);
            }
        }
        document.addEventListener('DOMContentLoaded', updateCountdown);
    </script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-red-500">404 ERROR</h1>
        <p class="mt-4 text-xl text-gray-700">Page not found</p>
        <p class="mt-2 text-gray-600">Redirecting in <span id="countdown" class="font-bold">5</span> seconds...</p>
    </div>
</body>
</html>
