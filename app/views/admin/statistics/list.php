<?php 
require_once __DIR__ . '/../../layouts/auth_header.php';
require_once __DIR__ . '/../../../helpers/Icons.php';
?>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Hi, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Admin'); ?>!</h1>
    </div>

    <?php if (isset($error)): ?>
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">Error loading statistics: <?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($stats)): ?>
        <!-- User Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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

            <!-- Current Month Feedback -->
            <div class="bg-gradient-to-br from-red-300 to-red-400 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium uppercase">This Month Feedback</p>
                        <p class="text-4xl font-bold mt-2"><?php echo number_format($stats['feedback_count']); ?></p>
                    </div>
                    <div class="bg-red-400 bg-opacity-30 rounded-full p-4">
                        <?php echo Icons::comment('w-12 h-12'); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Status and Trending Categories -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Job Status Distribution - Circle Chart -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Job Status Distribution</h2>
                <div class="flex flex-col items-center">
                    <canvas id="jobStatusChart" width="200" height="200"></canvas>
                    <div class="mt-6 w-full">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-yellow-500 mr-2"></div>
                                <span class="text-sm text-gray-700">Pending: <?php echo number_format($stats['job_counts']['pending']); ?></span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-green-500 mr-2"></div>
                                <span class="text-sm text-gray-700">Approved: <?php echo number_format($stats['job_counts']['approved']); ?></span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-red-500 mr-2"></div>
                                <span class="text-sm text-gray-700">Rejected: <?php echo number_format($stats['job_counts']['rejected']); ?></span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-pink-500 mr-2"></div>
                                <span class="text-sm text-gray-700">Overdue: <?php echo number_format($stats['job_counts']['overdue']); ?></span>
                            </div>
                            <div class="flex items-center col-span-2">
                                <div class="w-4 h-4 rounded-full bg-gray-500 mr-2"></div>
                                <span class="text-sm text-gray-700">Deleted: <?php echo number_format($stats['job_counts']['soft_deleted']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Trending Categories - Column Chart -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Top Trending Categories</h2>
                <div class="flex flex-col items-center">
                    <canvas id="trendingCategoriesChart" width="400" height="300"></canvas>
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

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Job Status Pie Chart
    const jobStatusCtx = document.getElementById('jobStatusChart');
    if (jobStatusCtx) {
        new Chart(jobStatusCtx, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Approved', 'Rejected', 'Overdue', 'Deleted'],
                datasets: [{
                    data: [
                        <?php echo $stats['job_counts']['pending']; ?>,
                        <?php echo $stats['job_counts']['approved']; ?>,
                        <?php echo $stats['job_counts']['rejected']; ?>,
                        <?php echo $stats['job_counts']['overdue']; ?>,
                        <?php echo $stats['job_counts']['soft_deleted']; ?>
                    ],
                    backgroundColor: [
                        '#EAB308', // yellow-500
                        '#22C55E', // green-500
                        '#EF4444', // red-500
                        '#EC4899', // pink-500
                        '#6B7280'  // gray-500
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Trending Categories Bar Chart
    const trendingCategoriesCtx = document.getElementById('trendingCategoriesChart');
    if (trendingCategoriesCtx) {
        new Chart(trendingCategoriesCtx, {
            type: 'bar',
            data: {
                labels: [
                    <?php 
                    if (!empty($stats['trending_categories'])) {
                        foreach ($stats['trending_categories'] as $cat) {
                            echo "'" . addslashes($cat['name']) . "',";
                        }
                    }
                    ?>
                ],
                datasets: [{
                    label: 'Number of Jobs',
                    data: [
                        <?php 
                        if (!empty($stats['trending_categories'])) {
                            foreach ($stats['trending_categories'] as $cat) {
                                echo $cat['job_count'] . ',';
                            }
                        }
                        ?>
                    ],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',  // blue
                        'rgba(16, 185, 129, 0.8)',  // green
                        'rgba(251, 146, 60, 0.8)',  // orange
                        'rgba(139, 92, 246, 0.8)',  // purple
                        'rgba(236, 72, 153, 0.8)'   // pink
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(251, 146, 60)',
                        'rgb(139, 92, 246)',
                        'rgb(236, 72, 153)'
                    ],
                    borderWidth: 2,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11
                            },
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Jobs: ' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>
