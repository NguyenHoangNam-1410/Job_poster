    </main>
    
    <script>
        // Sidebar toggle for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
        
        // Toast notification function (reusable)
        function showToast(message, isError = false) {
            const toast = document.createElement('div');
            toast.className = 'toast' + (isError ? ' error' : '');
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.toggle-sidebar');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    sidebar.classList.remove('active');
                    document.getElementById('sidebarOverlay').classList.remove('active');
                }
            }
        });
    </script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</body>
</html>
