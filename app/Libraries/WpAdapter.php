<?php

/**
 * Class WpAdapter
 * 
 * WpAdapter is a PHP class designed to connect to the WordPress REST API, currently limited to fetching public data
 * such as displaying posts and performing search queries. This class provides basic methods for retrieving 
 * publicly accessible content without authentication.
 * 
 * Usage Example:
 * $wp = new WpAdapter('https://yourwordpresssite.com');
 * $posts = $wp->setPerPage(5)->getPosts(1); // get posts from page 1 with 5 posts per page
 * 
 * @author      Adnan Zaki
 * @version     1.0
 * @package     Libraries
 * @license     MIT
 * @since       2024
 */
class WpAdapter
{
    /**
     * The base url of the WordPress REST API
     * 
     * @var string
     */
    private $baseUrl;

    /**
     * The number of posts per page
     * 
     * @var int
     */
    private $perPage = 5;

    /**
     * When users click on a post, they will be redirected using this base url
     * 
     * @var string
     */
    private $singlePostBaseUrl = 'read-post';

    /**
     * The length of the excerpt
     * 
     * @var int
     */
    private $excerptLength = 150;

    /**
     * Response as array
     * 
     * @var bool|null
     */
    private $responseAsArray = null;

    /**
     * The sort of the posts
     * 
     * @var string
     */
    private $sort = 'desc';

    /**
     * The order by of the posts
     * 
     * @var string
     */
    private $orderBy = 'date';

    /**
     * Limit results to these IDs
     * 
     * @var array
     */
    private $ids = [];

    /**
     * Create a new instance of the WpAdapter class
     * 
     * @param string $baseUrl The base URL of the WordPress REST API
     */
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * Set the number of posts per page
     * 
     * @param int $perPage The number of posts per page
     * 
     * @return $this
     */
    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }
    
    /**
     * Set the base URL for single post.
     * 
     * The base URL is used to generate the URL for each post.
     * 
     * @param string $url The base URL for single post.
     * 
     * @return $this
     */
    public function setSinglePostUrl(string $url)
    {
        $this->singlePostBaseUrl = $url;

        return $this;
    }

    /**
     * Set the excerpt length.
     * 
     * The excerpt length is the length of the excerpt in the post object.
     * 
     * @param int $length The length of the excerpt.
     * 
     * @return $this
     */
    public function setExcerptLength(int $length)
    {
        $this->excerptLength = $length;

        return $this;
    }

    public function setResponseAsArray(bool $responseAsArray)
    {
        $this->responseAsArray = $responseAsArray;

        return $this;
    }

    /**
     * Set the order of the posts
     * 
     * @param string $orderBy The column to order by
     * @param string $sort The direction of the order
     * 
     * @return WpAdapter
     */
    public function setOrder(string $orderBy, string $sort = 'desc')
    {
        $this->orderBy = $orderBy;
        $this->sort = $sort;

        return $this;
    }

    /**
     * Set the IDs of the posts to fetch.
     * 
     * @param array $ids The IDs of the posts to fetch.
     * 
     * @return $this
     */
    public function setIds(array $ids)
    {
        $this->ids = $ids;

        return $this;
    }

    /**
     * Get posts
     * 
     * @param int $page             The page being searched based on total post
     * @param string|null $search   Limit results based on search parameter
     * @param string $taxonomy      Category | Tag
     * @param string $filter        Category or tag name being searched
     * 
     * @return array
     */
    public function getPosts(int $page, ?string $search = '', string $taxonomy = '', string $filter = ''): array
    {
        // Base query
        $query = [
            'page'     => $page,
            'per_page' => $this->perPage,
            'orderby'  => $this->orderBy,
            'order'    => $this->sort,
        ];

        // Include specific IDs if available
        if (!empty($this->ids)) {
            $query['include'] = implode(',', $this->ids);
        }

        // Add search if provided
        if (!empty($search)) {
            $query['search'] = $search;
        }

        // Handle taxonomy filters
        if (!empty($taxonomy) && !empty($filter)) {
            switch ($taxonomy) {
                case 'category':
                    $categories = $this->getCategories($filter);
                    if (!empty($categories[0]->id)) {
                        $query['categories'] = $categories[0]->id;
                    }
                    break;

                case 'tag':
                    $tags = $this->getTags($filter);
                    if (!empty($tags[0]->id)) {
                        $query['tags'] = $tags[0]->id;
                    }
                    break;
            }
        }

        // Build endpoint
        $endpoint = 'posts?' . http_build_query($query);

        // Call API
        $posts = $this->call($endpoint);

        // Format response
        $formatted = [];
        $status    = 'post_not_found';

        if (is_array($posts)) {
            if (!empty($posts)) {
                $status = 'post_found';
                foreach ($posts as $p) {
                    $formatted[] = $this->getPostDetail($p);
                }
            } else {
                $status = 'post_empty';
            }
        }

        return [
            'status' => $status,
            'data'   => $formatted,
            'query'  => $endpoint,
        ];
    }


    /**
     * Get a single post by slug
     *
     * @param string $slug
     * @param int $perPage
     * 
     * @return array
     */
    public function readPost($slug, $perPage = null)
    {
        $post = $this->call('posts?slug=' . $slug)[0];
        $postDetail = $this->getPostDetail($post);

        // get recent posts to display it in the sidebar
        $this->perPage = $perPage ?? $this->perPage;
        $recentPosts = $this->getPosts(1);

        $contentData = [
            'post'          => $postDetail,
            'recentPosts'   => $recentPosts['data'], // recent posts data
            'recentStatus'  => $recentPosts['status'], // recent posts status
            'categories'    => $this->call('categories'),
            'tags'          => $this->call('tags'),
        ];

        $data = [
            'id'            => $postDetail->id,
            'pageTitle'     => $postDetail->title,
            'contents'      => $contentData,
            'wpURL'         => $this->baseUrl,
        ];

        return $data;
    }
    /**
     * Get total posts from WordPress REST API
     * 
     * @param array $args Query arguments (search, categories, tags, etc)
     * @return int|null
     */
    public function getTotalPost(array $args = []): ?int
    {
        // Build query string
        $query = http_build_query($args);

        // Endpoint posts + query
        $endpoint = 'posts' . (!empty($query) ? '?' . $query : '');

        // Call API with total enabled
        $result = $this->call($endpoint, true);

        // Return total if available
        return $result['total'] ?? null;
    }


    /**
     * Retrieve media details by post ID.
     *
     * This function calls the WordPress REST API to fetch media associated with
     * a specific post ID. It formats the media details to include various image
     * sizes such as thumbnail, medium, large, medium_large, and full.
     *
     * @param int $id The ID of the post whose media is being retrieved.
     * @return array An array of objects containing media details, including
     *               IDs and URLs for different image sizes.
     */
    public function getMediaByPostId($id)
    {
        $media = $this->call('media?id=' . $id);
        $formatted = [];
        foreach($media as $m) {
            $sizes = $m->media_details->sizes;
            $formatted[] = (object)[
                'id'            => $m->id,
                'url'           => $m->guid->rendered,
                'thumbnail'     => $sizes->thumbnail->source_url,
                'medium'        => $sizes->medium->source_url ?? '',
                'large'         => $sizes->large->source_url ?? '',
                'medium_large'  => $sizes->medium_large->source_url ?? '',
                'full'          => $sizes->full->source_url
            ];
        }

        return $formatted;
    }

    /**
     * Retrieves a list of all categories.
     *
     * Calls the WordPress REST API to fetch all categories.
     *
     * @param string|null $slug An optional category slug to filter by.
     * @return array A list of objects containing category details, including
     *               IDs, names, and URLs.
     */
    public function getCategories(?string $slug = null)
    {
        if ($slug) {
            return $this->call('categories?slug=' . $slug);
        } 
        
        return $this->call('categories');
    }

    /**
     * Retrieves a list of all tags.
     *
     * Calls the WordPress REST API to fetch all tags.
     *
     * @return array A list of objects containing tag details, including
     *               IDs, names, and URLs.
     */
    public function getTags(?string $slug = null)
    {
        if ($slug) {
            return $this->call('tags?slug=' . $slug);
        }

        return $this->call('tags');
    }

    /**
     * Get a single post by id
     *
     * @param object $post
     *
     * @return object
     */
    private function getPostDetail($post)
    {
        $postImage = '';
        $singlePostImage = '';
        $thumbnail = '';

        if ($post->featured_media !== 0) {
            $media = $this->call('media/' . $post->featured_media);
            $postImage = $media->media_details->sizes->large->source_url ?? $media->media_details->sizes->full->source_url;
            $thumbnail = $media->media_details->sizes->medium->source_url;
            $singlePostImage = $media->media_details->sizes->full->source_url;
        }

        $author = $this->call('users/' . $post->author);
        $date = explode('T', $post->date)[0];
        $comments = $this->call('comments?post=' . $post->id);

        $categories = 'Tidak berkategori';
        $categoriesAsArray = [];
        $tags = [];

        if (count($post->categories) > 0) {
            $postCategories = $this->call('categories?post=' . $post->id);
            $categoriesAsArray = $postCategories;

            if (count($postCategories) < 2) {
                $categories = $postCategories[0]->name;
            } else {
                $categoriesToArray = [];
                foreach ($postCategories as $pc) {
                    $categoriesToArray[] = $pc->name;
                }

                $categories = implode(', ', $categoriesToArray);
            }
        }

        if (count($post->tags) > 0) {
            $tags = $this->call('tags?post=' . $post->id);
        }

        $postURL = base_url($this->singlePostBaseUrl . '/' . $post->slug);

        $renderedContent = strip_tags($post->content->rendered);
        $ellipsis = strlen($renderedContent) > $this->excerptLength ? '...' : '';

        return (object)[
            'id'                => $post->id,
            'title'             => $post->title->rendered,
            'excerpt'           => substr($renderedContent, 0, $this->excerptLength) . $ellipsis,
            'content'           => $post->content->rendered,
            'media'             => $postImage,
            'thumbnail'         => $thumbnail,
            'singlePostImage'   => $singlePostImage,
            'categories'        => $categories, // categories rendered to string for post list
            'categoriesArray'   => $categoriesAsArray, // categories using array of objects for single post
            'tags'              => $tags, // tags using array of objects
            'url'               => $postURL,
            'author'            => $author->name,
            'authorBio'         => $author->description,
            'date'              => $date,
            'comments'          => count($comments),
        ];
    }
    
    /**
     * Get raw posts from WordPress REST API. This function does not format posts
     * to be used directly in views. It is used by the getPosts function to format
     * posts.
     *
     * @param int $page The page being searched based on total post
     * 
     * @return array
     */
    public function getRawPosts($page)
    {
        $endpoint = 'posts?page=' . $page . '&per_page=' . $this->perPage;
        $posts = $this->call($endpoint);
        foreach($posts as $post) {
            if ($post->featured_media !== 0) {
                $post->media = $this->call('media/' . $post->featured_media);
            }
        }

        return $posts;
    }

    /**
     * Make request to WordPress REST API
     * 
     * @param string $path Path to REST API endpoint
     * @param boolean $withTotal whether to include total post count or not
     * 
     * @return array|object
     */
    public function call(string $path, bool $withTotal = false) 
    {

        // prepare curl
        $ch = curl_init();

        // set url 
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . '/wp-json/wp/v2/' . $path);

        // fix error with SSL certificate
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        // return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // get response header
        curl_setopt($ch, CURLOPT_HEADER, 1);

        // $output contains the output string 
        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);


        curl_close($ch);

        // Get X-WP-Total from header
        // Get X-WP-Total from header (more robust)
        if (preg_match('/X-WP-Total:\s*(\d+)/i', $header, $matches)) {
            $totalPosts = (int) trim($matches[1]);
        } else {
            $totalPosts = null;
        }

        $bodyResult = json_decode($body, $this->responseAsArray);

        $output = $withTotal ? [
            'total' => $totalPosts,
            'data' => $bodyResult,
        ] : $bodyResult;

        return $output;
    }
}
