<?php
/**
 * Dynamic Sitemap Generator
 * Generates XML sitemap with static pages and dynamic job listings
 */
header('Content-Type: application/xml; charset=utf-8');

require_once __DIR__ . '/../app/dao/JobDAO.php';

// Get base URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . '/Worknest/public';
$currentDate = date('Y-m-d');

// Get approved jobs from database
$jobDAO = new JobDAO();
$jobs = $jobDAO->getAll('', [], [], ['approved'], 1000, 0); // Get up to 1000 approved jobs

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n\n";

// Homepage
echo "  <url>\n";
echo "    <loc>" . htmlspecialchars($baseUrl . '/', ENT_XML1) . "</loc>\n";
echo "    <lastmod>{$currentDate}</lastmod>\n";
echo "    <changefreq>daily</changefreq>\n";
echo "    <priority>1.0</priority>\n";
echo "  </url>\n\n";

// Jobs Listing
echo "  <url>\n";
echo "    <loc>" . htmlspecialchars($baseUrl . '/jobs', ENT_XML1) . "</loc>\n";
echo "    <lastmod>{$currentDate}</lastmod>\n";
echo "    <changefreq>daily</changefreq>\n";
echo "    <priority>0.9</priority>\n";
echo "  </url>\n\n";

// Static Pages
$staticPages = [
    ['url' => '/about', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['url' => '/contact', 'priority' => '0.6', 'changefreq' => 'monthly'],
    ['url' => '/help-center', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['url' => '/terms-of-service', 'priority' => '0.3', 'changefreq' => 'yearly'],
    ['url' => '/privacy-policy', 'priority' => '0.3', 'changefreq' => 'yearly']
];

foreach ($staticPages as $page) {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($baseUrl . $page['url'], ENT_XML1) . "</loc>\n";
    echo "    <lastmod>{$currentDate}</lastmod>\n";
    echo "    <changefreq>{$page['changefreq']}</changefreq>\n";
    echo "    <priority>{$page['priority']}</priority>\n";
    echo "  </url>\n\n";
}

// Dynamic Job Detail Pages
foreach ($jobs as $job) {
    $jobId = $job->getId();
    $lastModified = $job->getCreatedAt() ? date('Y-m-d', strtotime($job->getCreatedAt())) : $currentDate;
    
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($baseUrl . '/jobs/show/' . $jobId, ENT_XML1) . "</loc>\n";
    echo "    <lastmod>{$lastModified}</lastmod>\n";
    echo "    <changefreq>weekly</changefreq>\n";
    echo "    <priority>0.8</priority>\n";
    
    // Add job image if available
    $logo = $job->getLogo() ?? '';
    if (!empty($logo)) {
        $imageUrl = (strpos($logo, 'http') === 0) ? $logo : $baseUrl . '/' . ltrim($logo, '/');
        echo "    <image:image>\n";
        echo "      <image:loc>" . htmlspecialchars($imageUrl, ENT_XML1) . "</image:loc>\n";
        echo "      <image:title>" . htmlspecialchars($job->getTitle(), ENT_XML1) . "</image:title>\n";
        echo "    </image:image>\n";
    }
    
    echo "  </url>\n\n";
}

echo '</urlset>';

