<?php
global $wp_query;

query_posts($wp_query->query);


function master_feed_add_footer($content) {
    return $content . "<hr/>Follow <a href=\"http://twitter.com/Si\">@Si</a> for more Web-related content";
}
add_filter('the_content_feed', 'master_feed_add_footer');

include('wp-includes/feed-rss2.php');
?>