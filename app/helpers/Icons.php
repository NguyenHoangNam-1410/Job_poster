<?php
/**
 * SVG Icon Helper Functions
 * Returns HTML for common icons used throughout the admin panel
 */

class Icons
{
    private static $defaultClasses = 'w-5 h-5';

    /**
     * Edit/Pencil Icon
     */
    public static function edit($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>';
    }

    /**
     * Delete/Trash Icon
     */
    public static function delete($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>';
    }

    /**
     * Add/Plus Icon
     */
    public static function add($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>';
    }

    /**
     * View/Eye Icon
     */
    public static function view($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>';
    }

    /**
     * Filter Icon
     */
    public static function filter($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
        </svg>';
    }

    /**
     * Check/Success Icon
     */
    public static function check($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>';
    }

    /**
     * Empty State Icon (for tables)
     */
    public static function emptyState($classes = 'mx-auto h-12 w-12')
    {
        return '<svg class="' . $classes . ' text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
        </svg>';
    }

    /**
     * Tag Icon (for discounts)
     */
    public static function tag($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
        </svg>';
    }

    /**
     * Document Icon (for orders)
     */
    public static function document($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>';
    }

    /**
     * Image/Photo Icon
     */
    public static function image($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>';
    }

    /**
     * Home Icon
     */
    public static function home($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>';
    }

    /**
     * Users Icon
     */
    public static function users($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>';
    }

    /**
     * Box/Product Icon
     */
    public static function box($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>';
    }

    /**
     * Money/Dollar Icon (for revenue)
     */
    public static function money($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>';
    }

    /**
     * Shopping Bag Icon (for orders)
     */
    public static function shoppingBag($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        </svg>';
    }

    /**
     * Check Circle Icon (for completed/success)
     */
    public static function checkCircle($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>';
    }

    /**
     * X Circle Icon (for cancelled/error)
     */
    public static function xCircle($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>';
    }

    /**
     * Info Circle Icon (for information)
     */
    public static function infoCircle($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>';
    }

    /**
     * Arrow Left Icon (for back navigation)
     */
    public static function arrowLeft($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>';
    }

    /**
     * Arrow Right Icon (for forward navigation)
     */
    public static function arrowRight($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>';
    }

    /**
     * Menu/Hamburger Icon (for mobile menu)
     */
    public static function menu($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>';
    }

    /**
     * Sad Face Icon (for 404/empty states)
     */
    public static function sadFace($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>';
    }

    /**
     * Lightbulb Icon (for ideas/features)
     */
    public static function lightbulb($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
        </svg>';
    }

    /**
     * Briefcase Icon (for jobs or business)
     */
    public static function briefcase($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2m3 0h-12a2 2 0 00-2 2v9a2 2 0 002 2h12a2 2 0 002-2v-9a2 2 0 00-2-2z" />
    </svg>';
    }

    /**
     * Checklist Icon (for approved features)
     */
    public static function checklist($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m-6 8h8a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2h1" />
    </svg>';
    }
}
?>