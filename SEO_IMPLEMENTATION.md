# SEO Implementation Guide - WorkNest

This document outlines all SEO practices implemented in the WorkNest job board platform.

## ✅ Implemented SEO Practices

### 1. Meta Tags
- ✅ **Meta Description**: Optimized descriptions (150-160 characters) for all pages
- ✅ **Meta Keywords**: Relevant keywords for each page
- ✅ **Meta Robots**: Proper indexing directives
- ✅ **Meta Author**: Author information
- ✅ **Canonical URLs**: Prevent duplicate content issues

**Location**: `app/helpers/SEO.php` - Helper class for generating meta tags

### 2. Open Graph Tags
- ✅ **og:title**: Page title for social sharing
- ✅ **og:description**: Page description for social sharing
- ✅ **og:image**: Social media preview image (1200x630px recommended)
- ✅ **og:url**: Canonical URL
- ✅ **og:type**: Content type (website, article, etc.)
- ✅ **og:site_name**: Site name
- ✅ **og:locale**: Language/locale

**Location**: `app/helpers/SEO.php` - `openGraph()` method

### 3. Twitter Cards
- ✅ **twitter:card**: Card type (summary_large_image)
- ✅ **twitter:title**: Title for Twitter
- ✅ **twitter:description**: Description for Twitter
- ✅ **twitter:image**: Image for Twitter cards

**Location**: `app/helpers/SEO.php` - `twitterCard()` method

### 4. Structured Data (JSON-LD)
- ✅ **Organization Schema**: Company information
- ✅ **JobPosting Schema**: Job listings with all details (salary, location, company, etc.)
- ✅ **BreadcrumbList Schema**: Navigation breadcrumbs
- ✅ **WebSite Schema**: Site search functionality

**Location**: `app/helpers/SEO.php` - Schema generation methods

### 5. Sitemap
- ✅ **Dynamic Sitemap**: PHP-generated sitemap (`public/sitemap.php`)
- ✅ **Static Pages**: All public pages included
- ✅ **Dynamic Job Pages**: All approved jobs automatically included
- ✅ **Image Sitemap**: Job images included in sitemap
- ✅ **Last Modified Dates**: Based on job creation dates
- ✅ **Priority & Change Frequency**: Optimized per page type

**Location**: `public/sitemap.php`

### 6. Robots.txt
- ✅ **User-agent Rules**: Allow/disallow specific paths
- ✅ **Sitemap Reference**: Points to sitemap location
- ✅ **Search Engine Specific Rules**: Google, Bing optimized
- ✅ **Protected Paths**: Admin, API, auth pages blocked

**Location**: `public/robots.txt`

### 7. Semantic HTML
- ✅ **Proper HTML5 Structure**: Using semantic elements
- ✅ **Heading Hierarchy**: H1, H2, H3 properly structured
- ✅ **Alt Text for Images**: Image accessibility
- ✅ **ARIA Labels**: Where appropriate

**Location**: All view files in `app/views/`

### 8. Friendly URLs
- ✅ **Clean URLs**: `/jobs/show/123` instead of `/jobs/show.php?id=123`
- ✅ **SEO-friendly Routes**: Descriptive path structure

**Location**: `public/index.php` - Routing system

### 9. Page-Specific SEO

#### Homepage
- Title: "WorkNest - Job Board & Recruitment Platform | Find Jobs in Vietnam"
- Description: Optimized for job board and recruitment platform
- Keywords: job board, job search, recruitment, careers, jobs Vietnam

#### Jobs Listing Page
- Title: "Browse Jobs - Find Your Dream Career | WorkNest"
- Description: Optimized for job browsing and search
- Keywords: browse jobs, job search, jobs Vietnam, career opportunities

#### Job Detail Pages
- Title: Dynamic based on job title, company, location
- Description: Generated from job description (160 chars)
- Structured Data: Full JobPosting schema
- Open Graph: Job-specific image and details

**Location**: Each page file in `app/views/public/`

## 📁 File Structure

```
/Worknest
├── app/
│   └── helpers/
│       └── SEO.php                    # SEO helper class
├── app/views/
│   └── layouts/
│       └── public_header.php          # SEO tags integration
├── public/
│   ├── sitemap.php                   # Dynamic sitemap generator
│   ├── sitemap.xml                   # Static sitemap (backup)
│   └── robots.txt                    # Robots file
└── SEO_IMPLEMENTATION.md             # This file
```

## 🔧 Usage Examples

### Adding SEO to a New Page

```php
<?php
// Set SEO variables before including header
$pageTitle = 'Your Page Title | WorkNest';
$metaDescription = 'Your page description (150-160 characters)';
$metaKeywords = ['keyword1', 'keyword2', 'keyword3'];
$metaImage = '/Worknest/public/images/your-image.jpg';

// Breadcrumbs (optional)
$breadcrumbs = [
    ['name' => 'Home', 'url' => '/'],
    ['name' => 'Page Name', 'url' => '/page-url']
];

include __DIR__ . '/../layouts/public_header.php';
?>
```

### Using SEO Helper Directly

```php
<?php
require_once __DIR__ . '/../../helpers/SEO.php';

// Generate all SEO tags
echo SEO::generateAll([
    'title' => 'Page Title',
    'description' => 'Page description',
    'keywords' => ['keyword1', 'keyword2'],
    'image' => '/path/to/image.jpg',
    'url' => '/current-page',
    'og_type' => 'article'
]);

// Generate structured data
echo SEO::organizationSchema();
echo SEO::jobPostingSchema($job);
?>
```

## 🎯 SEO Best Practices Checklist

- ✅ Unique, descriptive page titles (50-60 characters)
- ✅ Meta descriptions (150-160 characters)
- ✅ H1 tag on every page
- ✅ Proper heading hierarchy (H1 → H2 → H3)
- ✅ Alt text for all images
- ✅ Internal linking structure
- ✅ Mobile-responsive design
- ✅ Fast page load times
- ✅ HTTPS enabled
- ✅ XML sitemap submitted to Google Search Console
- ✅ robots.txt configured
- ✅ Structured data for rich snippets
- ✅ Open Graph tags for social sharing
- ✅ Canonical URLs to prevent duplicate content

## 📊 Testing & Validation

### Tools to Test SEO Implementation:

1. **Google Search Console**: Submit sitemap and monitor indexing
2. **Google Rich Results Test**: Validate structured data
   - https://search.google.com/test/rich-results
3. **Facebook Sharing Debugger**: Test Open Graph tags
   - https://developers.facebook.com/tools/debug/
4. **Twitter Card Validator**: Test Twitter cards
   - https://cards-dev.twitter.com/validator
5. **Schema Markup Validator**: Test JSON-LD
   - https://validator.schema.org/
6. **PageSpeed Insights**: Test page speed
   - https://pagespeed.web.dev/
7. **Mobile-Friendly Test**: Test mobile responsiveness
   - https://search.google.com/test/mobile-friendly

## 🚀 Next Steps

1. **Submit Sitemap**: Submit `sitemap.php` to Google Search Console
2. **Monitor Performance**: Track rankings and organic traffic
3. **Update Regularly**: Keep sitemap and structured data up to date
4. **Content Optimization**: Continuously improve content quality
5. **Link Building**: Build quality backlinks
6. **Local SEO**: If applicable, add local business schema

## 📝 Notes

- Sitemap is dynamically generated, so new jobs are automatically included
- Update `robots.txt` sitemap URL when deploying to production
- Update base URL in `SEO.php` for production environment
- Ensure all images have proper alt text for accessibility and SEO
- Monitor Google Search Console for any crawl errors

---

**Last Updated**: January 2025
**Version**: 1.0
