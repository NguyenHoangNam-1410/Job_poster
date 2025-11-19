<?php 
require_once __DIR__ . '/../../layouts/public_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';
?>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Dashboard Statistics</h1>
        <p class="text-gray-600">Overview of system metrics and performance</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">Error loading statistics: <?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($stats)): ?>
        <!-- User Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Employers Count -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium uppercase">Total Employers</p>
                        <p class="text-4xl font-bold mt-2"><?php echo number_format($stats['employer_count']); ?></p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-full p-4">
                        <?php echo Icons::building('w-12 h-12'); ?>
                    </div>
                </div>
            </div>

            <!-- Staff Count -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium uppercase">Total Staff</p>
                        <p class="text-4xl font-bold mt-2"><?php echo number_format($stats['staff_count']); ?></p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-4">
                        <?php echo Icons::team('w-12 h-12'); ?>
                    </div>
                </div>
            </div>

            <!-- Total Jobs -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium uppercase">Total Jobs</p>
                        <p class="text-4xl font-bold mt-2"><?php echo number_format($stats['total_jobs']); ?></p>
                    </div>
                    <div class="bg-purple-400 bg-opacity-30 rounded-full p-4">
                        <?php echo Icons::document('w-12 h-12'); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Status Tracking -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Job Status Tracking</h2>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Pending -->
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-700 text-xs font-semibold uppercase mb-1">Pending</p>
                            <p class="text-3xl font-bold text-yellow-800"><?php echo number_format($stats['job_counts']['pending']); ?></p>
                        </div>
                        <div class="bg-yellow-200 rounded-full p-3">
                            <?php echo Icons::clock('w-8 h-8 text-yellow-600'); ?>
                        </div>
                    </div>
                </div>

                <!-- Approved -->
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-700 text-xs font-semibold uppercase mb-1">Approved</p>
                            <p class="text-3xl font-bold text-green-800"><?php echo number_format($stats['job_counts']['approved']); ?></p>
                        </div>
                        <div class="bg-green-200 rounded-full p-3">
                            <?php echo Icons::checkCircle('w-8 h-8 text-green-600'); ?>
                        </div>
                    </div>
                </div>

                <!-- Rejected -->
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-700 text-xs font-semibold uppercase mb-1">Rejected</p>
                            <p class="text-3xl font-bold text-red-800"><?php echo number_format($stats['job_counts']['rejected']); ?></p>
                        </div>
                        <div class="bg-red-200 rounded-full p-3">
                            <?php echo Icons::xCircle('w-8 h-8 text-red-600'); ?>
                        </div>
                    </div>
                </div>

                <!-- Overdue -->
                <div class="bg-pink-50 border-l-4 border-pink-500 p-4 rounded">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-pink-700 text-xs font-semibold uppercase mb-1">Overdue</p>
                            <p class="text-3xl font-bold text-pink-800"><?php echo number_format($stats['job_counts']['overdue']); ?></p>
                        </div>
                        <div class="bg-pink-200 rounded-full p-3">
                            <?php echo Icons::warning('w-8 h-8 text-pink-600'); ?>
                        </div>
                    </div>
                </div>

                <!-- Soft Deleted -->
                <div class="bg-gray-50 border-l-4 border-gray-500 p-4 rounded">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-700 text-xs font-semibold uppercase mb-1">Deleted</p>
                            <p class="text-3xl font-bold text-gray-800"><?php echo number_format($stats['job_counts']['soft_deleted']); ?></p>
                        </div>
                        <div class="bg-gray-200 rounded-full p-3">
                            <?php echo Icons::delete('w-8 h-8 text-gray-600'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-6">
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Job Status Distribution</span>
                    <span><?php echo number_format($stats['total_jobs']); ?> Total</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4 flex overflow-hidden">
                    <?php 
                    $total = $stats['total_jobs'] > 0 ? $stats['total_jobs'] : 1;
                    $pendingPercent = ($stats['job_counts']['pending'] / $total) * 100;
                    $approvedPercent = ($stats['job_counts']['approved'] / $total) * 100;
                    $rejectedPercent = ($stats['job_counts']['rejected'] / $total) * 100;
                    $overduePercent = ($stats['job_counts']['overdue'] / $total) * 100;
                    $deletedPercent = ($stats['job_counts']['soft_deleted'] / $total) * 100;
                    ?>
                    <?php if ($pendingPercent > 0): ?>
                        <div class="bg-yellow-500 h-full" style="width: <?php echo $pendingPercent; ?>%" title="Pending: <?php echo number_format($pendingPercent, 1); ?>%"></div>
                    <?php endif; ?>
                    <?php if ($approvedPercent > 0): ?>
                        <div class="bg-green-500 h-full" style="width: <?php echo $approvedPercent; ?>%" title="Approved: <?php echo number_format($approvedPercent, 1); ?>%"></div>
                    <?php endif; ?>
                    <?php if ($rejectedPercent > 0): ?>
                        <div class="bg-red-500 h-full" style="width: <?php echo $rejectedPercent; ?>%" title="Rejected: <?php echo number_format($rejectedPercent, 1); ?>%"></div>
                    <?php endif; ?>
                    <?php if ($overduePercent > 0): ?>
                        <div class="bg-pink-500 h-full" style="width: <?php echo $overduePercent; ?>%" title="Overdue: <?php echo number_format($overduePercent, 1); ?>%"></div>
                    <?php endif; ?>
                    <?php if ($deletedPercent > 0): ?>
                        <div class="bg-gray-500 h-full" style="width: <?php echo $deletedPercent; ?>%" title="Deleted: <?php echo number_format($deletedPercent, 1); ?>%"></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Top Performers Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top 3 Staff by Workload -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div class="bg-indigo-100 rounded-full p-3 mr-4">
                        <?php echo Icons::trendingUp('w-8 h-8 text-indigo-600'); ?>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Top Staffs</h2>
                </div>
                
                <?php if (!empty($stats['top_staff'])): ?>
                    <div class="space-y-4">
                        <?php foreach ($stats['top_staff'] as $index => $staff): ?>
                            <div class="flex items-center p-4 bg-gradient-to-r from-indigo-50 to-transparent rounded-lg border border-indigo-100 hover:shadow-md transition duration-200">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br <?php 
                                        echo $index === 0 ? 'from-yellow-400 to-yellow-500' : 
                                            ($index === 1 ? 'from-gray-300 to-gray-400' : 'from-pink-400 to-pink-500'); 
                                    ?> flex items-center justify-center text-white font-bold text-lg">
                                        <?php echo $index + 1; ?>
                                    </div>
                                </div>
                                <div class="flex-grow">
                                    <h3 class="font-bold text-gray-800 text-lg"><?php echo htmlspecialchars($staff['name']); ?></h3>
                                    <p class="text-sm text-gray-600">
                                        <span class="inline-block px-2 py-1 bg-indigo-100 text-indigo-800 rounded text-xs font-semibold mr-2">
                                            <?php echo htmlspecialchars($staff['role']); ?>
                                        </span>
                                        ID: #<?php echo htmlspecialchars($staff['id']); ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-bold text-indigo-600"><?php echo number_format($staff['action_count']); ?></p>
                                    <p class="text-xs text-gray-500 uppercase">Actions</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <?php echo Icons::emptyState('w-16 h-16 mx-auto mb-4 text-gray-300'); ?>
                        <p>No staff activity data available</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Top 3 Employers by Jobs Posted -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                        <?php echo Icons::award('w-8 h-8 text-blue-600'); ?>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Top Employers</h2>
                </div>
                
                <?php if (!empty($stats['top_employers'])): ?>
                    <div class="space-y-4">
                        <?php foreach ($stats['top_employers'] as $index => $employer): ?>
                            <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-transparent rounded-lg border border-blue-100 hover:shadow-md transition duration-200">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br <?php 
                                        echo $index === 0 ? 'from-yellow-400 to-yellow-500' : 
                                            ($index === 1 ? 'from-gray-300 to-gray-400' : 'from-pink-400 to-pink-500'); 
                                    ?> flex items-center justify-center text-white font-bold text-lg">
                                        <?php echo $index + 1; ?>
                                    </div>
                                </div>
                                <div class="flex-grow">
                                    <h3 class="font-bold text-gray-800 text-lg"><?php echo htmlspecialchars($employer['company_name']); ?></h3>
                                    <p class="text-sm text-gray-600">
                                        <?php echo htmlspecialchars($employer['user_name']); ?> 
                                        <span class="text-gray-400">• ID: #<?php echo htmlspecialchars($employer['user_id']); ?></span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-bold text-blue-600"><?php echo number_format($employer['job_count']); ?></p>
                                    <p class="text-xs text-gray-500 uppercase">Jobs</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <?php echo Icons::emptyState('w-16 h-16 mx-auto mb-4 text-gray-300'); ?>
                        <p>No employer data available</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../layouts/public_footer.php'; ?>
