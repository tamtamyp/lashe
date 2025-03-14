<?php
/**
 * The template used for displaying projects post style
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */
 get_header(); ?>
 <div class="parallax-title mb">
     <?php 
     while (have_posts()) : the_post(); ?>
         <?php ob_start(); ?>
         <?php
     // $bg = '#333';
  
     if (has_post_thumbnail()) $bg = get_post_thumbnail_id();
     $header_html = ob_get_contents();
     // $header_html = '[ux_banner animate="fadeInUp" bg_overlay="#000" parallax="2" parallax_text="-1" height="300px" bg="'.$bg.'"]'.$header_html.'[/ux_banner]';
     ob_end_clean();
     echo do_shortcode($header_html); ?>
     <?php endwhile; // end of the loop. 
     ?>
 </div>
 
 <div id="content" class="custom-show-category" role="main">
 
     <?php while (have_posts()) : the_post(); 

        //thông tin có thể sẽ cần
        
        $post_id = get_the_ID();
        global $post;
        // Retrieve custom field values
        $custom_field_value = get_post_meta($post_id, 'image', true);
        $address = get_post_meta($post_id, 'address', true);
        $date_of_delivery = get_post_meta($post_id, 'date_of_delivery', true);
        $investor = get_post_meta($post_id, 'investor', true);
        $typology = get_post_meta($post_id, 'typology', true);
        $sizing = get_post_meta($post_id, 'sizing', true);
        $acreage = get_post_meta($post_id, 'Acreage', true);
        $floor = get_post_meta($post_id, 'floor', true);
        $phone_number = get_post_meta($post_id, 'phone_number', true);
        $price_per_square_meter = get_post_meta($post_id, 'price_per_square_meter', true);
        $image_data = wp_get_attachment_image_src($custom_field_value, 'full');
        $image_url = ($image_data) ? $image_data[0] : '';
         the_content($image_url );
 
     endwhile;
 ?>

 </div>
 
 <?php get_footer(); ?>