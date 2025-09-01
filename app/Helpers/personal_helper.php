<?php

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