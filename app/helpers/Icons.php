<?php
/**
 * SVG Icon Helper Functions
 * Returns HTML for common icons used throughout the project
 */
class Icons
{
    private static $defaultClasses = 'w-5 h-5';

    /** ✏️ Edit/Pencil Icon */
    public static function edit($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                     a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>';
    }

    /** 🗑️ Delete/Trash Icon */
    public static function delete($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5
                     7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>';
    }

    /** ➕ Add/Plus Icon */
    public static function add($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>';
    }

    /** 👁️ View/Eye Icon */
    public static function view($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
                     9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>';
    }

    /** 🔍 Filter Icon */
    public static function filter($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0
                     01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4
                     4v-6.586a1 1 0 00-.293-.707L3.293
                     7.293A1 1 0 013 6.586V4z"/>
        </svg>';
    }

    /** ✅ Check/Success Icon */
    public static function check($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>';
    }

    /** 📭 Empty State Icon */
    public static function emptyState($classes = 'mx-auto h-12 w-12')
    {
        return '<svg class="'.$classes.' text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2
                     2v7m16 0v5a2 2 0 01-2 2H6a2 2 0
                     01-2-2v-5m16 0h-2.586a1 1 0
                     00-.707.293l-2.414 2.414a1 1 0
                     01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0
                     006.586 13H4"/>
        </svg>';
    }

    /** 🏷️ Tag Icon */
    public static function tag($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 7h.01M7 3h5c.512 0 1.024.195
                     1.414.586l7 7a2 2 0 010
                     2.828l-7 7a2 2 0
                     01-2.828 0l-7-7A1.994 1.994
                     0 013 12V7a4 4 0 014-4z"/>
        </svg>';
    }

    /** 📄 Document Icon */
    public static function document($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0
                     01-2-2V5a2 2 0 012-2h5.586a1 1 0
                     01.707.293l5.414 5.414a1 1 0
                     01.293.707V19a2 2 0
                     01-2 2z"/>
        </svg>';
    }

    /** 🖼️ Image/Photo Icon */
    public static function image($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2
                     l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0
                     00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>';
    }

    /** 🏠 Home Icon */
    public static function home($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7
                     7M5 10v10a1 1 0 001 1h3m10-11l2
                     2m-2-2v10a1 1 0 01-1
                     1h-3m-6 0a1 1 0 001-1v-4a1 1 0
                     011-1h2a1 1 0 011 1v4a1 1 0
                     001 1m-6 0h6"/>
        </svg>';
    }

    /** 👥 Users Icon */
    public static function users($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4.354a4 4 0 110
                     5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0
                     00-9-5.197M13 7a4 4 0
                     11-8 0 4 4 0 018 0z"/>
        </svg>';
    }

    /** 📦 Box/Product Icon */
    public static function box($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20 7l-8-4-8 4m16 0l-8
                     4m8-4v10l-8 4m0-10L4 7m8
                     4v10M4 7v10l8 4"/>
        </svg>';
    }

    /** 💰 Money Icon */
    public static function money($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8c-1.657 0-3 .895-3
                     2s1.343 2 3 2 3 .895 3
                     2-1.343 2-3 2m0-8c1.11 0
                     2.08.402 2.599 1M12
                     8V7m0 1v8m0 0v1m0-1c-1.11
                     0-2.08-.402-2.599-1M21
                     12a9 9 0 11-18 0 9 9 0
                     0118 0z"/>
        </svg>';
    }

    /** 🛍️ Shopping Bag Icon */
    public static function shoppingBag($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16 11V7a4 4 0 00-8
                     0v4M5 9h14l1 12H4L5
                     9z"/>
        </svg>';
    }

    /** ✅ Check Circle Icon */
    public static function checkCircle($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>';
    }

    /** ❌ X Circle Icon */
    public static function xCircle($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>';
    }

    /** ℹ️ Info Circle Icon */
    public static function infoCircle($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>';
    }

    /** ⬅️ Arrow Left Icon */
    public static function arrowLeft($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>';
    }

    /** ➡️ Arrow Right Icon */
    public static function arrowRight($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>';
    }

    /** ☰ Menu/Hamburger Icon */
    public static function menu($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>';
    }

    /** 🙁 Sad Face Icon */
    public static function sadFace($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>';
    }

    /** 💡 Lightbulb Icon */
    public static function lightbulb($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4
                     12H3m3.343-5.657l-.707-.707m2.828
                     9.9a5 5 0 117.072 0l-.548.547A3.374
                     3.374 0 0014 18.469V19a2 2 0
                     11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
        </svg>';
    }

    /** 💼 Briefcase Icon */
    public static function briefcase($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2m3 0h-12a2 2 0 00-2 2v9a2 2 0 002 2h12a2 2 0 002-2v-9a2 2 0 00-2-2z"/>
        </svg>';
    }

    /** 🗒️ Checklist Icon */
    public static function checklist($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m-6 8h8a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2h1"/>
        </svg>';
    }

    /** 🕘 History/Clockwise Arrow Icon */
    public static function history($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>';
    }

    /** 🌀 General/Generic Icon */
    public static function general($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>';
    }

    /** 💬 Comment Icon */
    public static function comment($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>';
    }

    /** 📈 Statistic/Pie Icon */
    public static function statistic($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20.488 9H15V3.512a9.025 9.025 0 015.488 5.488z"/>
        </svg>';
    }

    /** 🕒 Clock/Pending Icon */
    public static function clock($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>';
    }

    /** ⚠️ Warning Icon */
    public static function warning($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>';
    }

    /** 📈 Trending Up Icon */
    public static function trendingUp($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
        </svg>';
    }

    /** 🏆 Award/Badge Icon */
    public static function award($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
        </svg>';
    }

    /** 👨‍👩‍👧‍👦 Team/People Icon */
    public static function team($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>';
    }

    /** 🏢 Building/Company Icon */
    public static function building($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>';
    }

    /** 📊 Bar Chart Icon (snake_case) */
    public static function bar_chart($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
            <path d="M3 20.5H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <rect x="5" y="11" width="3" height="8" rx="0.5" stroke="currentColor" stroke-width="1.5"/>
            <rect x="10.5" y="6" width="3" height="13" rx="0.5" stroke="currentColor" stroke-width="1.5"/>
            <rect x="16" y="13" width="3" height="6" rx="0.5" stroke="currentColor" stroke-width="1.5"/>
        </svg>';
    }

    /** 📊 Bar Chart Icon (camelCase alias) */
    public static function barChart($classes = null)
    {
        return self::bar_chart($classes);
    }

    /** ⚡ Zap/Lightning Icon */
    public static function zap($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M13 2L3 14h7v8l11-12h-8z"/>
        </svg>';
    }

    /** 🛡️ Shield Check Icon (snake_case) */
    public static function shield_check($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10zm-2-6l-2-2 1.5-1.5L10 13l3.5 3.5L18 12l1.5 1.5-6 6z"/>
        </svg>';
    }

    /**
     * Info Icon (for information/about)
     */
    public static function info($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="'.$classes.'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>';
    }

    /**
     * Settings/Gear Icon (for profile settings)
     */
    public static function settings($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>';
    }

    /**
     * Logout/Sign Out Icon
     */
    public static function logout($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
        </svg>';
    }

    /** 🛡️ Shield Check Icon (camelCase alias) */
    public static function shieldCheck($classes = null)
    {
        return self::shield_check($classes);
    }
    public static function flag($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M5 5v14M5 5l7-2 7 2-7 2-7-2z"></path>
        </svg>';
    }
    public static function profile($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;
        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>';
    }

    public static function save($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;

        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 3h-2a1 1 0 00-1 1v3H6a1 1 0 00-1 1v11a2 2 0 002 2h10a2 2 0 002-2V5.83a1 1 0 00-.29-.71l-2.83-2.83A1 1 0 0017 3z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 13h10M7 17h10M15 4v4" />
        </svg>';
    }

    public static function send($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;

        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3.5 12l16-8-6 8 6 8-16-8z" />
        </svg>';
    }

    public static function refresh($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;

        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4.5 4.5v5h5M19.5 19.5v-5h-5M5 19a8.5 8.5 0 0014-6.5M19 5a8.5 8.5 0 00-14 6.5" />
        </svg>';
    }

    public static function exclamationCircle($classes = null)
    {
        $classes = $classes ?? self::$defaultClasses;

        return '<svg class="' . $classes . '" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
        </svg>';
    }

}