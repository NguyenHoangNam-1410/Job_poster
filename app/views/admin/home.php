<?php 
require_once '../app/views/layouts/admin_header.php';
?>
<!-- Admin Home View (Dashboard) -->
<main class="flex min-h-screen bg-gray-100">
  <!-- Main Dashboard Content -->
  <section class="flex-1 p-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Welcome, Admin 👋</h1>

    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm font-medium mb-2">Total Jobs</h3>
        <p class="text-2xl font-semibold text-gray-800">256</p>
      </div>
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm font-medium mb-2">Active Employers</h3>
        <p class="text-2xl font-semibold text-gray-800">42</p>
      </div>
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm font-medium mb-2">Pending Requests</h3>
        <p class="text-2xl font-semibold text-gray-800">7</p>
      </div>
    </div>

    <!-- Job Request Table Preview -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <div class="flex justify-between items-center px-6 py-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Recent Job Requests</h2>
        <a href="/admin/requests" class="text-sm text-blue-600 hover:underline">View all</a>
      </div>
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 border-b text-gray-600">
          <tr>
            <th class="text-left py-3 px-6">Company</th>
            <th class="text-left py-3 px-6">Job Title</th>
            <th class="text-left py-3 px-6">Submitted</th>
            <th class="text-left py-3 px-6">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-6">TechNova</td>
            <td class="py-3 px-6">Frontend Developer</td>
            <td class="py-3 px-6">2025-11-08</td>
            <td class="py-3 px-6 text-yellow-600 font-semibold">Pending</td>
          </tr>
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-6">HealthCorp</td>
            <td class="py-3 px-6">Data Analyst</td>
            <td class="py-3 px-6">2025-11-07</td>
            <td class="py-3 px-6 text-green-600 font-semibold">Approved</td>
          </tr>
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-6">EduVision</td>
            <td class="py-3 px-6">Teacher</td>
            <td class="py-3 px-6">2025-11-06</td>
            <td class="py-3 px-6 text-red-600 font-semibold">Rejected</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php 
require_once '../app/views/layouts/admin_footer.php';
?>