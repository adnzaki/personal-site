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
     * The offset of the posts
     * 
     * @var int
     */
    private $offset = 0;

    /**
     * The tag slug
     * 
     * @var string
     */
    private $tag = '';

    /**
     * The category slug
     * 
     * @var string
     */
    private $category = '';

    /**
     * The ellipsis
     * 
     * @var string
     */
    private $ellipsis = '...';

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
     * Set the ellipsis string used when excerpts are truncated.
     *
     * @param string $ellipsis The ellipsis string to use.
     *
     * @return $this
     */
    public function setEllipsis(string $ellipsis)
    {
        $this->ellipsis = $ellipsis;

        return $this;
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
     * Set the offset of the posts. This is useful for pagination.
     * 
     * @param int $offset The offset of the posts
     * 
     * @return $this
     */
    public function startFrom(int $offset)
    {
        $this->offset = $offset;

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
     * @param string $scope         Limit query based on scope
     * @param string|null $search   Limit results based on search parameter
     * @param string $taxonomy      Category | Tag
     * @param string $filter        Category or tag name being searched
     * 
     * @return array
     */
    public function getPosts(int $page, array $include, ?string $search = '', string $taxonomy = '', string $filter = ''): array
    {
        // Base query
        $query = [
            'page'     => $page,
            'offset'   => $this->offset,
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
                    $categories = $this->categorySlug($filter)->getCategories(1);
                    if (!empty($categories[0]->id)) {
                        $query['categories'] = $categories[0]->id;
                    }
                    break;

                case 'tag':
                    $tags = $this->tagSlug($filter)->getTags(1);
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
                    $formatted[] = $this->getPostDetail($p, $include);
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
     * 
     * @return object
     */
    public function readPost($slug)
    {
        $postDetail = [];
        $post = $this->call('posts?slug=' . $slug);
        if(! empty($post)) {
            $postDetail = $this->getPostDetail($post[0], ['author', 'media', 'comment', 'category', 'tag']);
        }

        return $postDetail;
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
     * Get comments for a post with nested replies (unlimited depth)
     *
     * @param int $postId
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function getCommentsWithReplies(int $postId, int $page = 1, int $perPage = 10)
    {
        // Get top-level comments (parent = 0)
        $comments = $this->call("comments?post={$postId}&parent=0&page={$page}&per_page={$perPage}");

        // Attach replies recursively
        foreach ($comments as &$comment) {
            $comment->replies = $this->fetchRepliesRecursive($comment->id);
        }

        return $comments;
    }

    /**
     * Recursively fetch replies for a comment
     *
     * @param int $parentId
     * @return array
     */
    private function fetchRepliesRecursive(int $parentId): array
    {
        $replies = $this->call("comments?parent={$parentId}") ?? [];

        foreach ($replies as &$reply) {
            $reply->replies = $this->fetchRepliesRecursive($reply->id);
        }

        return $replies;
    }



    /**
     * Add a new comment
     *
     * @param int $postId The ID of the post to comment on
     * @param string $content The comment content
     * @param array $authorData Optional. For guest comments: 
     *                          ['author_name' => '', 'author_email' => '']
     * @param int $parentId Optional. The parent comment ID (0 for top-level)
     * 
     * @return array|object
     */
    public function addComment(int $postId, string $content, array $authorData = [], int $parentId = 0)
    {
        $data = [
            'post'    => $postId,
            'content' => $content,
            'parent'  => $parentId,
        ];

        // Guest comment handling
        if (!empty($authorData)) {
            if (isset($authorData['author_name'])) {
                $data['author_name'] = $authorData['author_name'];
            }
            if (isset($authorData['author_email'])) {
                $data['author_email'] = $authorData['author_email'];
            }
        }

        return $this->call('comments', false, 'POST', $data);
    }


    /**
     * Update an existing comment
     *
     * @param int $commentId The ID of the comment to update
     * @param string $content The updated content
     * 
     * @return array|object
     */
    public function updateComment(int $commentId, string $content)
    {
        $data = [
            'content' => $content,
        ];

        return $this->call('comments/' . $commentId, false, 'POST', $data);
    }

    /**
     * Delete a comment
     *
     * @param int $commentId The ID of the comment to delete
     * @param bool $force Whether to force delete (true) or move to trash (false)
     * 
     * @return array|object
     */
    public function deleteComment(int $commentId, bool $force = true)
    {
        $endpoint = 'comments/' . $commentId . '?force=' . ($force ? 'true' : 'false');

        return $this->call($endpoint, false, 'DELETE');
    }

    /**
     * Reply to a specific comment
     *
     * @param int $postId   The ID of the parent post
     * @param int $parentId The ID of the comment being replied to
     * @param string $content The reply content
     * @param array $authorData Optional. For guest comments: ['author_name' => '', 'author_email' => '']
     * 
     * @return array|object
     */
    public function replyComment(int $postId, int $parentId, string $content, array $authorData = [])
    {
        $data = [
            'post'    => $postId,
            'parent'  => $parentId,
            'content' => $content,
        ];

        // Guest comment handling
        if (!empty($authorData)) {
            if (isset($authorData['author_name'])) {
                $data['author_name'] = $authorData['author_name'];
            }
            if (isset($authorData['author_email'])) {
                $data['author_email'] = $authorData['author_email'];
            }
        }

        return $this->call('comments', false, 'POST', $data);
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
     * Sets the category slug to be used in the next API call.
     *
     * @param string $slug The category slug to be used.
     * @return self
     */
    public function categorySlug(string $slug)
    {
        $this->category = $slug;

        return $this;
    }

    /**
     * Retrieves a list of all categories.
     *
     * Calls the WordPress REST API to fetch all categories.
     *
     * @param int $perPage The number of categories to retrieve per page.
     * @param string $orderBy The field to order the categories by.
     * @param string $order The order of the categories (asc or desc).
     * 
     * @return array A list of objects containing category details, including
     *               IDs, names, and URLs.
     */
    public function getCategories(int $perPage = 10, string $orderBy = 'name', string $order = 'asc')
    {
        $params = [
            'per_page' => $perPage,
            'orderby'  => $orderBy,
            'order'    => $order,
        ];

        if ($this->category) {
            $params['slug'] = $this->category;
        } 

        $query = http_build_query($params);
        
        return $this->call('categories?' . $query);
    }

    /**
     * Sets the tag slug to be used in subsequent calls to getTags or getPosts.
     *
     * @param string $slug The slug of the tag to be used.
     *
     * @return $this
     */
    public function tagSlug(string $slug)
    {
        $this->tag = $slug;

        return $this;
    }

    /**
     * Retrieves a list of all tags.
     *
     * Calls the WordPress REST API to fetch all tags.
     * 
     * @param int $perPage The number of tags to retrieve per page.
     * @param string $orderBy The field to order the tags by.
     * @param string $order The order of the tags (asc or desc).
     *
     * @return array A list of objects containing tag details, including
     *               IDs, names, and URLs.
     */
    public function getTags(int $perPage = 10, string $orderBy = 'count', string $order = 'desc')
    {
        $params = [
            'per_page' => $perPage,
            'orderby'  => $orderBy,
            'order'    => $order,
        ];

        if ($this->tag) {
            $params['slug'] = $this->tag;
        }

        $query = http_build_query($params);
        return $this->call('tags?' . $query);
    }

    /**
     * Get a single post by id
     *
     * @param object $post
     * @param array $include Limit query to be included in the response
     *
     * @return object
     */
    private function getPostDetail($post, array $include = [])
    {
        $postImage = '';
        $singlePostImage = '';
        $thumbnail = '';

        if(in_array('media', $include)) {
            if ($post->featured_media !== 0) {
                $media = $this->call('media/' . $post->featured_media);
                if(! empty($media->media_details)) {
                    $postImage = $media->media_details->sizes->large->source_url ?? $media->media_details->sizes->full->source_url;
                    $thumbnail = $media->media_details->sizes->medium->source_url;
                    $singlePostImage = $media->media_details->sizes->full->source_url;
                }
            }
        }

        if(in_array('author', $include)) {
            $author = $this->call('users/' . $post->author);
        }

        $date = explode('T', $post->date)[0];

        $comments = [];

        if(in_array('comment', $include)) {
            $comments = $this->call('comments?post=' . $post->id);
        }

        $categories = 'Tidak berkategori';
        $categoriesAsArray = [];
        $tags = [];

        if(in_array('category', $include)) {
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
        }

        if(in_array('tag', $include)) {
            if (count($post->tags) > 0) {
                $tags = $this->call('tags?post=' . $post->id);
            }
        }

        $postURL = base_url($this->singlePostBaseUrl . '/' . $post->slug);

        $postContent = $post->content->rendered;
        $postContentClean = strip_tags($postContent);
        $excerpt = substr($postContentClean, 0, $this->excerptLength);
        $ellipsis = strlen($postContentClean) > $this->excerptLength ? '...' : '';


        return (object)[
            'id'                => $post->id,
            'title'             => $post->title->rendered,
            'excerpt'           => $excerpt . $ellipsis,
            'content'           => $postContent,
            'media'             => $postImage ?? '',
            'thumbnail'         => $thumbnail ?? '',
            'singlePostImage'   => $singlePostImage ?? '',
            'categories'        => $categories ?? '', // categories rendered to string for post list
            'categoriesArray'   => $categoriesAsArray ?? [], // categories using array of objects for single post
            'tags'              => $tags ?? [], // tags using array of objects
            'url'               => $postURL,
            'author'            => $author->name ?? '',
            'authorBio'         => $author->description ?? '',
            'authorImage'       => $author->avatar_urls->{'96'} ?? '',
            'date'              => $date,
            'comments'          => $comments,
            'commentsCount'     => count($comments),
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
     * @param string $method HTTP method: GET | POST | PUT | PATCH | DELETE
     * @param array $data Data body yang dikirim (untuk POST/PUT/PATCH)
     * 
     * @return array|object
     */
    public function call(string $path, bool $withTotal = false, string $method = 'GET', array $data = [])
    {
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

        // Tentukan metode HTTP
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

        // Jika ada data untuk POST/PUT/PATCH
        if (!empty($data) && in_array(strtoupper($method), ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data))
            ]);
        }

        // Eksekusi
        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);

        curl_close($ch);

        // Ambil total jika diperlukan
        preg_match('/X-WP-Total:\s*(\d+)/i', $header, $matches);
        $totalPosts = isset($matches[1]) ? (int) $matches[1] : null;

        $bodyResult = json_decode($body, $this->responseAsArray);

        $output = $withTotal ? [
            'total' => $totalPosts,
            'data'  => $bodyResult,
        ] : $bodyResult;

        return $output;
    }
}
