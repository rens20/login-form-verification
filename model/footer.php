
  <script>
        document.getElementById('burger-menu').addEventListener('click', function () {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('sidebar-hidden');
        });

        document.getElementById('user-menu-button').addEventListener('click', function () {
            var menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        });

        
    </script>
</body>
</html>