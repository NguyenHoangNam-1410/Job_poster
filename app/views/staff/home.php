<?php
require_once '../app/views/layouts/auth_header.php';
?>

<div class="list-container">
    <div class="list-content">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Hi,
                <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Staff'); ?>!</h1>
            <p class="text-gray-600">Here's your dashboard overview for today</p>
        </div>

        <!-- Top Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Pending Jobs Card -->
            <div
                class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium mb-1">Pending Job Requests</p>
                        <p class="text-4xl font-bold">8</p>
                        <p class="text-yellow-100 text-xs mt-2">Awaiting review</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Reviewed Reports Card -->
            <div
                class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium mb-1">Reviewed Reports</p>
                        <p class="text-4xl font-bold">15</p>
                        <p class="text-green-100 text-xs mt-2">Complaints handled</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Managed Jobs Card -->
            <div
                class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-1">Managed Jobs</p>
                        <p class="text-4xl font-bold">24</p>
                        <p class="text-blue-100 text-xs mt-2">Total jobs monitored</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Posting Requests Table -->
        <div class="list-table-wrapper mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-800" style="margin-left: 10px;">Recent Job Requests</h2>
                <a href="/Job_poster/public/approvals"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    View All
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="table-header">Job Title</th>
                            <th class="table-header">Company</th>
                            <th class="table-header">Status</th>
                            <th class="table-header">Submitted On</th>
                            <th class="table-header">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="table-cell font-medium text-gray-900">Backend Developer</td>
                            <td class="table-cell">TechNova Ltd</td>
                            <td class="table-cell">
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            </td>
                            <td class="table-cell">2025-11-05</td>
                            <td class="table-cell">
                                <div class="flex gap-3">
                                    <a href="/Job_poster/public/approvals/detail/1"
                                        class="inline-flex items-center text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Review
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="table-cell font-medium text-gray-900">UI/UX Designer</td>
                            <td class="table-cell">DesignHub Co</td>
                            <td class="table-cell">
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            </td>
                            <td class="table-cell">2025-11-06</td>
                            <td class="table-cell">
                                <div class="flex gap-3">
                                    <a href="/Job_poster/public/approvals/detail/2"
                                        class="inline-flex items-center text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Review
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reports & Complaints Table -->
        <div class="list-table-wrapper">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-800" style="margin-left: 10px;">Recent Feedback</h2>
                <a href="/Job_poster/public/feedbacks"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    View All
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="table-header">Report Title</th>
                            <th class="table-header">Submitted By</th>
                            <th class="table-header">Status</th>
                            <th class="table-header">Submitted On</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="table-cell font-medium text-gray-900">Job Posting Spam</td>
                            <td class="table-cell">Employer123</td>
                            <td class="table-cell">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Reviewed
                                </span>
                            </td>
                            <td class="table-cell">2025-11-04</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="table-cell font-medium text-gray-900">Incorrect Salary Info</td>
                            <td class="table-cell">Employer456</td>
                            <td class="table-cell">
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            </td>
                            <td class="table-cell">2025-11-06</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../app/views/layouts/auth_footer.php';
?>