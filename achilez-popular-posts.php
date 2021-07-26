<?php

/*
Plugin Name: Achilez Popular Posts
Plugin URI: https://wordpress.org/plugins/achilez-popular-posts/
Description: A customizable widget that displays the most popular posts on your blog.
Version: 1.1
Author: Archie Mercader
Author URI: http://www.archiemercader.com
License: GPL2
*/

/**
 * Adds a view to the post being viewed
 *
 * Finds the current views of a post and adds one to it by updating
 * the postmeta. The meta key used is "achilez_pop_views".
 *
 * @global object $post The post object
 * @return integer $new_views The number of views the post has
 *
 */
function achilez_pop_add_view() {
   if(is_single()) {
      global $post;
      $current_views = get_post_meta($post->ID, "achilez_pop_views", true);
      if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views) ) {
         $current_views = 0;
      }
      $new_views = $current_views + 1;
      update_post_meta($post->ID, "achilez_pop_views", $new_views);
      return $new_views;
   }
}

add_action("wp_head", "achilez_pop_add_view");

/**
 * Retrieve the number of views for a post
 *
 * Finds the current views for a post, returning 0 if there are none
 *
 * @global object $post The post object
 * @return integer $current_views The number of views the post has
 *
 */
function achilez_pop_get_view_count() {
   global $post;
   $current_views = get_post_meta($post->ID, "achilez_pop_views", true);
   if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views) ) {
      $current_views = 0;
   }

   return $current_views;
}

/**
 * Shows the number of views for a post
 *
 * Finds the current views of a post and displays it together with some optional text
 *
 * @global object $post The post object
 * @uses achilez_pop_get_view_count()
 *
 * @param string $singular The singular term for the text
 * @param string $plural The plural term for the text
 * @param string $before Text to place before the counter
 *
 * @return string $views_text The views display
 *
 */
function achilez_pop_show_views($singular = "view", $plural = "views", $before = "This post has: ") {
   global $post;
   $current_views = achilez_pop_get_view_count();

   $views_text = $before . $current_views . " ";

   if ($current_views == 1) {
      $views_text .= $singular;
   }
   else {
      $views_text .= $plural;
   }

   return $views_text;

}

/**
 * Displays a list of posts ordered by popularity
 *
 * Shows a simple list of post titles ordered by their view count
 *
 * @param integer $post_count The number of posts to show
 *
 */
 function achilez_pop_popularity_list($post_count = 10) {
  $args = array(
    "posts_per_page" => 10,
    "post_type" => "post",
    "post_status" => "publish",
    "meta_key" => "achilez_pop_views",
    "orderby" => "meta_value_num",
    "order" => "DESC"
  );

  $awepop_list = new WP_Query($args);

  if($awepop_list->have_posts()) { echo "<ul>"; }

  while ( $awepop_list->have_posts() ) : $awepop_list->the_post();
    echo '<li><a href="'.get_permalink($post->ID).'">'.the_title(’, ’, false).' count: '.achilez_pop_show_views().'</a></li>';
  endwhile;

  if($awepop_list->have_posts()) { echo "</ul>";}
 }


//Use the code below to call this function in your theme and generate a simple list of posts ordered by popularity:
// if (function_exists("achilez_pop_popularity_list")) {
//    achilez_pop_popularity_list();
// }

?>

