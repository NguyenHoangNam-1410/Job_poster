<?php 
require_once '../app/views/layouts/employer_header.php';
?>
<!-- Employer Home View (Dashboard) -->
<main class="flex min-h-screen bg-gray-100">
  <!-- Main Dashboard Content -->
  <section class="flex-1 p-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Welcome, Employer 👋</h1>

    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm font-medium mb-2">My Posted Jobs</h3>
        <p class="text-2xl font-semibold text-gray-800">12</p>
      </div>
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm font-medium mb-2">Active Applications</h3>
        <p class="text-2xl font-semibold text-gray-800">37</p>
      </div>
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm font-medium mb-2">Pending Approvals</h3>
        <p class="text-2xl font-semibold text-gray-800">3</p>
      </div>
    </div>

    <!-- Company Profile Summary -->
    <div class="bg-white rounded-xl shadow overflow-hidden mb-8">
      <div class="flex justify-between items-center px-6 py-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">My Company Profile</h2>
        <a href="/Job_poster/public/employer/company" class="text-sm text-blue-600 hover:underline">Edit Profile</a>
      </div>
      <div class="p-6">
        <div class="flex items-center space-x-6">
          <img src="/Job_poster/public/uploads/company-logo.png" alt="Company Logo" class="w-16 h-16 rounded-lg object-cover bg-gray-200">
          <div>
            <h3 class="text-xl font-bold text-gray-800">TechNova Ltd</h3>
            <p class="text-gray-600 text-sm">Contact: employer@technova.com | (0123) 456 789</p>
            <p class="text-gray-500 text-sm mt-1">Building innovative web solutions for businesses worldwide.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Job Management Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <div class="flex justify-between items-center px-6 py-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Recent Job Posts</h2>
        <a href="/Job_poster/public/employer/my-jobs" class="text-sm text-blue-600 hover:underline">Manage All</a>
      </div>
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 border-b text-gray-600">
          <tr>
            <th class="text-left py-3 px-6">Job Title</th>
            <th class="text-left py-3 px-6">Category</th>
            <th class="text-left py-3 px-6">Status</th>
            <th class="text-left py-3 px-6">Deadline</th>
          </tr>
        </thead>
        <tbody>
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-6">Frontend Developer</td>
            <td class="py-3 px-6">IT / Software</td>
            <td class="py-3 px-6 text-green-600 font-semibold">Approved</td>
            <td class="py-3 px-6">2025-12-01</td>
          </tr>
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-6">UI/UX Designer</td>
            <td class="py-3 px-6">Design</td>
            <td class="py-3 px-6 text-yellow-600 font-semibold">Pending</td>
            <td class="py-3 px-6">2025-11-20</td>
          </tr>
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-6">Project Manager</td>
            <td class="py-3 px-6">Management</td>
            <td class="py-3 px-6 text-red-600 font-semibold">Rejected</td>
            <td class="py-3 px-6">2025-11-10</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php 
require_once '../app/views/layouts/employer_footer.php';
?>
