<?php 
require_once '../app/views/layouts/staff_header.php';
?>
<!-- Staff Home View (Dashboard) -->
<main class="flex min-h-screen bg-gray-100">
  <!-- Main Dashboard Content -->
  <section class="flex-1 p-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-2">Hi, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Staff'); ?></h1>

    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm font-medium mb-2">Pending Job Requests</h3>
        <p class="text-2xl font-semibold text-gray-800">8</p>
      </div>
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm font-medium mb-2">Reviewed Reports/Complaints</h3>
        <p class="text-2xl font-semibold text-gray-800">15</p>
      </div>
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-gray-500 text-sm font-medium mb-2">Managed Jobs</h3>
        <p class="text-2xl font-semibold text-gray-800">24</p>
      </div>
    </div>

    <!-- Job Posting Requests Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden mb-8">
      <div class="flex justify-between items-center px-6 py-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Job Posting Requests</h2>
        <a href="/staff/job-requests" class="text-sm text-blue-600 hover:underline">View All</a>
      </div>
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 border-b text-gray-600">
          <tr>
            <th class="text-left py-3 px-6">Job Title</th>
            <th class="text-left py-3 px-6">Company</th>
            <th class="text-left py-3 px-6">Status</th>
            <th class="text-left py-3 px-6">Submitted On</th>
            <th class="text-left py-3 px-6">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-6">Backend Developer</td>
            <td class="py-3 px-6">TechNova Ltd</td>
            <td class="py-3 px-6 text-yellow-600 font-semibold">Pending</td>
            <td class="py-3 px-6">2025-11-05</td>
            <td class="py-3 px-6">
              <a href="/staff/job-requests/approve/1" class="text-green-600 hover:underline">Approve</a> | 
              <a href="/staff/job-requests/reject/1" class="text-red-600 hover:underline">Reject</a>
            </td>
          </tr>
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-6">UI/UX Designer</td>
            <td class="py-3 px-6">DesignHub Co</td>
            <td class="py-3 px-6 text-yellow-600 font-semibold">Pending</td>
            <td class="py-3 px-6">2025-11-06</td>
            <td class="py-3 px-6">
              <a href="/staff/job-requests/approve/2" class="text-green-600 hover:underline">Approve</a> | 
              <a href="/staff/job-requests/reject/2" class="text-red-600 hover:underline">Reject</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Reports & Complaints Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <div class="flex justify-between items-center px-6 py-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Reports & Complaints</h2>
        <a href="/staff/reports" class="text-sm text-blue-600 hover:underline">View All</a>
      </div>
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 border-b text-gray-600">
          <tr>
            <th class="text-left py-3 px-6">Report Title</th>
            <th class="text-left py-3 px-6">Submitted By</th>
            <th class="text-left py-3 px-6">Status</th>
            <th class="text-left py-3 px-6">Submitted On</th>
          </tr>
        </thead>
        <tbody>
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-6">Job Posting Spam</td>
            <td class="py-3 px-6">Employer123</td>
            <td class="py-3 px-6 text-green-600 font-semibold">Reviewed</td>
            <td class="py-3 px-6">2025-11-04</td>
          </tr>
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-6">Incorrect Salary Info</td>
            <td class="py-3 px-6">Employer456</td>
            <td class="py-3 px-6 text-yellow-600 font-semibold">Pending</td>
            <td class="py-3 px-6">2025-11-06</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>

<?php 
require_once '../app/views/layouts/staff_footer.php';
?>
