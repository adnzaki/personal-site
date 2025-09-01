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

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }
    
    public function setSinglePostUrl(string $url)
    {
        $this->singlePostBaseUrl = $url;

        return $this;
    }

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
     * Get posts
     * 
     * @param int $page The page being searched based on total post
     * @param string $search Limit results based on search parameter
     * @param string $taxonomy Category | Tag
     * @param string $filter Category or tag name being searched
     * 
     * @return array
     */
    public function getPosts(int $page, $search = '', string $taxonomy = '', string $filter = '')
    {
        $endpoint = 'posts?page=' . $page . '&per_page=' . $this->perPage;

        if (! empty($search)) $endpoint .= '&search=' . urlencode($search);

        if (! empty($taxonomy)) {
            if ($taxonomy === 'category') {
                $categories = $this->call('categories?slug=' . $filter)[0];
                $endpoint .= '&categories=' . $categories->id;
            } elseif ($taxonomy === 'tag') {
                $tag = $this->call('tags?slug=' . $filter)[0];
                $endpoint .= '&tags=' . $tag->id;
            }
        }

        $posts = $this->call($endpoint);

        $formatted = [];
        $status = '';

        if (is_array($posts)) {
            if (count($posts) > 0) {
                $status = 'post_found';

                foreach ($posts as $p) {
                    $formatted[] = $this->getPostDetail($p);
                }
            } else {
                $status = 'post_empty';
            }
        } else {
            $status = 'post_not_found';
        }

        $response = [
            'status'    => $status,
            'data'      => $formatted,
        ];

        return $response;
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
     * @return array A list of objects containing category details, including
     *               IDs, names, and URLs.
     */
    public function getCategories()
    {
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
    public function getTags()
    {
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
            $postImage = $media->media_details->sizes->large->source_url;
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

        return (object)[
            'id'                => $post->id,
            'title'             => $post->title->rendered,
            'excerpt'           => substr(strip_tags($post->content->rendered), 0, $this->excerptLength),
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
     * Make request to WordPress REST API
     * 
     * @param string $path Path to REST API endpoint
     * @param mixed $associative whether to return decoded JSON as associative array or not
     * 
     * @return object
     */
    public function call(string $path)
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

        // $output contains the output string 
        $output = curl_exec($ch);

        curl_close($ch);

        return json_decode($output, $this->responseAsArray);
    }
}
