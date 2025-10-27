    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="mb-2">© 2025 Job Poster - Job Poster Management System</p>
            <p class="text-sm text-gray-400">Built with PHP, MySQL, Tailwind CSS</p>
        </div>
    </footer>
    
    <?php if (isset($additionalJS)): ?>
        <?php foreach ($additionalJS as $js): ?>
            <script src="<?= $js ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
