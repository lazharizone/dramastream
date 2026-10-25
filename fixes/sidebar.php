<?php if (get_option('tsthemelayout') !== 'tsnosidebar') { ?>
<div id="sidebar">
<?php
remove_action('pre_get_posts', 'reorder_tax');
remove_action('pre_get_posts', 'reorder_blog');

$episode_id = get_queried_object_id();
$series_id = is_singular('post') ? absint(get_post_meta($episode_id, 'ero_seri', true)) : 0;

if ($series_id) {
    // Show only episodes linked to the current series.
    $series_episodes = new WP_Query(array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 30,
        'ignore_sticky_posts' => true,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array(
            'relation' => 'OR',
            array('key' => 'ero_seri', 'value' => (string) $series_id, 'compare' => '='),
            array('key' => '_anikoto_series_id', 'value' => (string) $series_id, 'compare' => '=')
        )
    ));
    if ($series_episodes->have_posts()) {
        echo '<div class="bixbox"><div class="releases"><h3>Episodes</h3></div><ul class="series-only-episodes" style="list-style:none;margin:0;padding:10px 15px;">';
        while ($series_episodes->have_posts()) {
            $series_episodes->the_post();
            echo '<li style="padding:8px 0;border-bottom:1px solid rgba(128,128,128,.2)"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
        }
        echo '</ul></div>';
    }
    wp_reset_postdata();
} elseif (function_exists('ts_playlist')) {
    ts_playlist('DESC', 'F j, Y');
}

if (function_exists('dynamic_sidebar')) {
    dynamic_sidebar();
}
?>
</div>
<?php } ?>
