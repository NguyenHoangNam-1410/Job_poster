<?php 
$pageTitle = 'Homepage | Book Store';
include __DIR__ . '/../layouts/public_header.php';
?>

    <!-- Hero Section -->
    <section class="gradient-bg text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-5xl font-bold mb-4">Welcome</h2>
            <p class="text-xl mb-8 opacity-90">Find your dream job</p>
            <div class="flex justify-center space-x-4">
                <a href="/Job_poster/public/jobs" 
                   class="bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-yellow-300 hover:text-purple-700 transition duration-300">
                    View Job
                </a>
                <a href="/Job_poster/public/users" 
                   class="bg-transparent border-2 border-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition duration-300">
                    User Management
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center mb-12 text-gray-800">Features</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- User Management -->
                <div class="bg-white rounded-lg shadow-md p-8 card-hover">
                    <div class="text-center">
                        <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <?= Icons::users('w-8 h-8 text-purple-600') ?>
                        </div>
                        <h4 class="text-xl font-bold mb-2 text-gray-800">User Management</h4>
                        <p class="text-gray-600 mb-4">Manage user information, assign Admin/Customer roles</p>
                        <a href="/Job_poster/public/users" class="text-purple-600 hover:text-purple-800 font-semibold">
                            View More
                        </a>
                    </div>
                </div>

                <!-- Product Management -->
                <div class="bg-white rounded-lg shadow-md p-8 card-hover">
                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <?= Icons::box('w-8 h-8 text-blue-600') ?>
                        </div>
                        <h4 class="text-xl font-bold mb-2 text-gray-800">Discount Management</h4>
                        <p class="text-gray-600 mb-4">Add, edit, delete discounts with detailed information</p>
                        <a href="/Job_poster/public/discounts" class="text-blue-600 hover:text-blue-800 font-semibold">
                            View More
                        </a>
                    </div>
                </div>

                <!-- Modern UI -->
                <div class="bg-white rounded-lg shadow-md p-8 card-hover">
                    <div class="text-center">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <?= Icons::lightbulb('w-8 h-8 text-green-600') ?>
                        </div>
                        <h4 class="text-xl font-bold mb-2 text-gray-800">Modern UI</h4>
                        <p class="text-gray-600 mb-4">AJAX, Toast notifications, Catchy Icons design</p>
                        <a href="/Job_poster/public/carts" class="text-green-600 hover:text-green-800 font-semibold">
                            Check it out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php include __DIR__ . '/../layouts/public_footer.php'; ?>
