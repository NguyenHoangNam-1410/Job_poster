<?php
/**
 * SEO Helper Class
 * Provides functions for generating SEO meta tags, Open Graph, Twitter Cards, and structured data
 */
class SEO
{
    private static $baseUrl = 'http://localhost/Worknest/public';
    private static $siteName = 'WorkNest';
    private static $defaultDescription = 'WorkNest - A smart and efficient job management system built for modern recruiters and job seekers. Find your dream job or the perfect candidate today.';
    private static $defaultImage = '/Worknest/public/images/og-image.jpg';
    
    /**
     * Set base URL (should be called from config)
     */
    public static function setBaseUrl($url)
    {
        self::$baseUrl = rtrim($url, '/');
    }
    
    /**
     * Generate canonical URL
     */
    public static function canonical($path = '')
    {
        $canonical = self::$baseUrl . $path;
        return '<link rel="canonical" href="' . htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') . '">' . "\n";
    }
    
    /**
     * Generate meta description
     */
    public static function metaDescription($description = '')
    {
        $desc = !empty($description) ? $description : self::$defaultDescription;
        $desc = htmlspecialchars(strip_tags($desc), ENT_QUOTES, 'UTF-8');
        // Limit to 160 characters for optimal SEO
        if (strlen($desc) > 160) {
            $desc = substr($desc, 0, 157) . '...';
        }
        return '<meta name="description" content="' . $desc . '">' . "\n";
    }
    
    /**
     * Generate meta keywords
     */
    public static function metaKeywords($keywords = [])
    {
        $defaultKeywords = ['job board', 'job search', 'recruitment', 'careers', 'jobs Vietnam', 'hiring', 'job opportunities'];
        $keywords = array_merge($defaultKeywords, $keywords);
        $keywords = array_unique($keywords);
        $keywordsStr = implode(', ', array_slice($keywords, 0, 10)); // Limit to 10 keywords
        return '<meta name="keywords" content="' . htmlspecialchars($keywordsStr, ENT_QUOTES, 'UTF-8') . '">' . "\n";
    }
    
    /**
     * Generate robots meta tag
     */
    public static function robots($directive = 'index, follow')
    {
        return '<meta name="robots" content="' . htmlspecialchars($directive, ENT_QUOTES, 'UTF-8') . '">' . "\n";
    }
    
    /**
     * Generate author meta tag
     */
    public static function author($author = 'WorkNest Team')
    {
        return '<meta name="author" content="' . htmlspecialchars($author, ENT_QUOTES, 'UTF-8') . '">' . "\n";
    }
    
    /**
     * Generate Open Graph tags
     */
    public static function openGraph($data = [])
    {
        $title = $data['title'] ?? self::$siteName;
        $description = $data['description'] ?? self::$defaultDescription;
        $image = isset($data['image']) ? (strpos($data['image'], 'http') === 0 ? $data['image'] : self::$baseUrl . $data['image']) : self::$baseUrl . self::$defaultImage;
        $url = isset($data['url']) ? (strpos($data['url'], 'http') === 0 ? $data['url'] : self::$baseUrl . $data['url']) : self::$baseUrl . $_SERVER['REQUEST_URI'];
        $type = $data['type'] ?? 'website';
        
        $html = '';
        $html .= '<meta property="og:type" content="' . htmlspecialchars($type, ENT_QUOTES, 'UTF-8') . '">' . "\n";
        $html .= '<meta property="og:title" content="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '">' . "\n";
        $html .= '<meta property="og:description" content="' . htmlspecialchars(substr(strip_tags($description), 0, 200), ENT_QUOTES, 'UTF-8') . '">' . "\n";
        $html .= '<meta property="og:url" content="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">' . "\n";
        $html .= '<meta property="og:image" content="' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . '">' . "\n";
        $html .= '<meta property="og:image:width" content="1200">' . "\n";
        $html .= '<meta property="og:image:height" content="630">' . "\n";
        $html .= '<meta property="og:site_name" content="' . htmlspecialchars(self::$siteName, ENT_QUOTES, 'UTF-8') . '">' . "\n";
        $html .= '<meta property="og:locale" content="en_US">' . "\n";
        
        return $html;
    }
    
    /**
     * Generate Twitter Card tags
     */
    public static function twitterCard($data = [])
    {
        $title = $data['title'] ?? self::$siteName;
        $description = $data['description'] ?? self::$defaultDescription;
        $image = isset($data['image']) ? (strpos($data['image'], 'http') === 0 ? $data['image'] : self::$baseUrl . $data['image']) : self::$baseUrl . self::$defaultImage;
        $card = $data['card'] ?? 'summary_large_image';
        
        $html = '';
        $html .= '<meta name="twitter:card" content="' . htmlspecialchars($card, ENT_QUOTES, 'UTF-8') . '">' . "\n";
        $html .= '<meta name="twitter:title" content="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '">' . "\n";
        $html .= '<meta name="twitter:description" content="' . htmlspecialchars(substr(strip_tags($description), 0, 200), ENT_QUOTES, 'UTF-8') . '">' . "\n";
        $html .= '<meta name="twitter:image" content="' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . '">' . "\n";
        
        return $html;
    }
    
    /**
     * Generate structured data (JSON-LD) for Organization
     */
    public static function organizationSchema($data = [])
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $data['name'] ?? self::$siteName,
            'url' => $data['url'] ?? self::$baseUrl,
            'logo' => $data['logo'] ?? self::$baseUrl . '/images/logo.png',
            'description' => $data['description'] ?? self::$defaultDescription,
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $data['city'] ?? 'Ho Chi Minh City',
                'addressCountry' => $data['country'] ?? 'VN'
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'Customer Service',
                'email' => $data['email'] ?? 'contact@worknest.com'
            ],
            'sameAs' => $data['socialLinks'] ?? []
        ];
        
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }
    
    /**
     * Generate structured data (JSON-LD) for JobPosting
     */
    public static function jobPostingSchema($job)
    {
        $baseUrl = self::$baseUrl;
        $jobUrl = $baseUrl . '/jobs/show/' . $job['id'];
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'JobPosting',
            'title' => $job['title'] ?? '',
            'description' => strip_tags($job['description'] ?? ''),
            'identifier' => [
                '@type' => 'PropertyValue',
                'name' => self::$siteName,
                'value' => (string)($job['id'] ?? '')
            ],
            'datePosted' => isset($job['created_at']) ? date('c', strtotime($job['created_at'])) : date('c'),
            'validThrough' => isset($job['deadline']) ? date('c', strtotime($job['deadline'])) : date('c', strtotime('+30 days')),
            'employmentType' => 'FULL_TIME',
            'hiringOrganization' => [
                '@type' => 'Organization',
                'name' => $job['company'] ?? $job['company_name'] ?? '',
                'sameAs' => $job['website'] ?? ''
            ],
            'jobLocation' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $job['location'] ?? '',
                    'addressCountry' => 'VN'
                ]
            ],
            'baseSalary' => isset($job['salary']) && $job['salary'] > 0 ? [
                '@type' => 'MonetaryAmount',
                'currency' => 'VND',
                'value' => [
                    '@type' => 'QuantitativeValue',
                    'value' => $job['salary'],
                    'unitText' => 'MONTH'
                ]
            ] : null,
            'url' => $jobUrl
        ];
        
        // Remove null values
        $schema = array_filter($schema, function($value) {
            return $value !== null;
        });
        
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }
    
    /**
     * Generate structured data (JSON-LD) for BreadcrumbList
     */
    public static function breadcrumbSchema($items)
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => []
        ];
        
        $position = 1;
        foreach ($items as $item) {
            $schema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $item['name'],
                'item' => isset($item['url']) ? (strpos($item['url'], 'http') === 0 ? $item['url'] : self::$baseUrl . $item['url']) : ''
            ];
        }
        
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }
    
    /**
     * Generate structured data (JSON-LD) for WebSite with SearchAction
     */
    public static function websiteSchema($searchUrl = '/jobs')
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => self::$siteName,
            'url' => self::$baseUrl,
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => self::$baseUrl . $searchUrl . '?q={search_term_string}'
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ];
        
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }
    
    /**
     * Generate all SEO tags for a page
     */
    public static function generateAll($data = [])
    {
        $html = '';
        
        // Basic meta tags
        $html .= self::metaDescription($data['description'] ?? '');
        $html .= self::metaKeywords($data['keywords'] ?? []);
        $html .= self::robots($data['robots'] ?? 'index, follow');
        $html .= self::author($data['author'] ?? '');
        
        // Canonical URL
        $html .= self::canonical($data['canonical'] ?? $_SERVER['REQUEST_URI']);
        
        // Open Graph
        $html .= self::openGraph([
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? '',
            'url' => $data['url'] ?? '',
            'type' => $data['og_type'] ?? 'website'
        ]);
        
        // Twitter Card
        $html .= self::twitterCard([
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? '',
            'card' => $data['twitter_card'] ?? 'summary_large_image'
        ]);
        
        return $html;
    }
}

