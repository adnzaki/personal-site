<?php
if(! function_exists('osdate')) {
    /**
     * A shortcut to instantly initiate OstiumDate object
     * 
     * @return \OstiumDate
     */
    function osdate()
    {
        return new \OstiumDate();
    }
}

if(! function_exists('wp')) {
    /**
     * A shortcut to instantly initiate WpAdapter object
     * 
     * @return \WpAdapter
     */
    function wp()
    {
        return new \WpAdapter(env('wordpress_url'));
    }
}