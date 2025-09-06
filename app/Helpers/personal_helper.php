<?php
if (! function_exists('insert_visitor')) {
    /**
     * Insert new site visitor
     *
     * @return void
     */
    function insert_visitor()
    {
        $visitor = new \App\Models\SiteVisitorModel;
        $visitor->insertVisitor();
    }
}

if (! function_exists('filter_disallowed_words')) {
    /**
     * Check if a given text contains disallowed words
     *
     * @param string $text
     * @return bool Returns false if disallowed word found, true otherwise
     */
    function filter_disallowed_words(string $text): bool
    {
        $model = new \App\Models\WpOptionsModel();
        $raw = $model->getDisallowedKeys()->option_value ?? '';
        if (empty($raw)) {
            return true; // no rule, always allowed
        }

        // Split by comma and trim spaces
        $words = array_map('trim', explode(',', $raw));

        foreach ($words as $word) {
            if ($word === '') continue;

            // case-insensitive match
            if (stripos($text, $word) !== false) {
                return false;
            }
        }

        return true;
    }
}


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