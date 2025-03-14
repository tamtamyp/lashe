<?php

/**
 * Template name: Page - Apartment
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

get_header(); ?>
<div class="parallax-title mb">
    <?php 
       $current_page = get_queried_object();   
       $taxonomy_name = 'categorie';
       $args = array(
           'post_type' => 'projects', 
           'tax_query' => array(
               array(
                   'taxonomy' => $taxonomy_name,
                   'field'    => 'slug', 
                   'terms'    =>  $current_page->post_name,
               ),
           ),
       );
       $projects_query = new WP_Query($args);
       $projects = get_posts($args);
    while (have_posts()) : the_post(); ?>
        <?php ob_start(); ?>
        <div class="elementor-container elementor-container-36585b45" style="min-height:225px; display: flex; flex-direction: column-reverse; overflow: hidden; background-image: url(/wp-content/themes/flatsome/bg-sunshine-diamond-river.jpg); background-position: center center; background-repeat: no-repeat; background-size: cover;">
            <div class="elementor-container elementor-container-6694438d elementor-column elementor-col-100" style="min-height: 40px; flex-align-items: flex-start; background-color: #1947869E;max-height: 40px; display: flex; align-items: center;">
                <div class="elementor-widget elementor-widget-text-editor elementor-widget-692c80a8 elementor-element-populated" style="width: 100%; padding: 0 10px;">
                    <ul style="margin: 0; padding-left: 0; font-size: 16px; color: white; margin: 0 auto; width: 1140px;">
                        <?php
                        if (!is_home()){
                            echo '<a href="'.get_option('home').'" style="text-decoration: none; color: white;">Trang chủ</a>
                            » '.get_the_title();
                        }
                        ?>
                        
                    </ul>
                </div>
            </div>
        </div>
                        <br>
        <div class="elementor-container elementor-container-1cb614ab" style="padding: 0 10px;">
            <div class="elementor-widget elementor-widget-text-editor elementor-widget-7201ed41 elementor-element-populated">
                <h1 style="text-align: center;"><span style="color: #0c336c;">
                        <i class="fa fa fa-book" style="font-size:15px; font-style: italic;"></i>&nbsp;
                        <strong>[page_title]</strong></span>
                </h1>
            </div>
        </div>
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

    <?php while (have_posts()) : the_post(); ?>

        <?php the_content(); ?>

    <?php endwhile; // end of the loop. 
    
echo do_shortcode('[block id="dang-ky-nhan-bang-gia-uu-dai-tu-chu-dau-tu"]'); 
?>
<br>
<?php
    if ($projects_query->have_posts()) {
        while ($projects_query->have_posts()) {
            $projects_query->the_post();
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

            // Get the URL of the custom field image
            $image_data = wp_get_attachment_image_src($custom_field_value, 'full');
            $image_url = ($image_data) ? $image_data[0] : '';

            // Display the project data
            ?>
            <div class="grid-container">
                <div class="dialog-container-custom">
                    <div class="image-container-custom">
                        <a href="<?php echo $post->guid ?>">
                            <img src="<?php echo $image_url ?>" alt="<?php echo get_the_title() ?>">
                        </a>
                    </div>
                    <div class="info-container-custom">
                        <div class="header-custom">
                        <a href="<?php echo $post->guid ?>">
                            <div class="title-custom" style="color:#cba442;">
                               <?php echo esc_html(get_the_title()); ?>    
                            </div>
                            </a>
                            <div class="price-custom">
                                <?php echo $price_per_square_meter; ?>
                            </div>
                        </div>

                        <div class="additional-info">
                            <p><i class="icon fas fa-map-marker"></i><strong>Địa chỉ:</strong>
                                <?php echo $address; ?>
                            </p>
                            <p><i class="icon fas fa-calendar-alt"></i><strong>Ngày Bàn Giao:</strong>
                                <?php echo $date_of_delivery; ?>
                            </p>
                            <p><i class="icon fas fa-building"></i><strong>Chủ Đầu Tư:</strong>
                                <?php echo $investor; ?>
                            </p>
                            <p><i class="icon fas fa-home"></i><strong>Loại Hình :</strong>
                                <?php echo $typology; ?>
                            </p>
                            <p><i class="icon fas fa-ruler-combined"></i><strong>Quy Mô:</strong>
                                <?php echo $sizing; ?>
                            </p>
                            <p><i class="icon fas fa-building"></i><strong>Số Tầng:</strong>
                                <?php echo $floor; ?>
                            </p>
                            <div class="phone-and-area">
                                <p><i class="icon fas fa-chart-area"></i><strong>Diện tích:</strong>
                                    <?php echo $acreage; ?>
                                </p>
                                <p class="custom-phone-number"><i class="icon fas fa-phone"></i>
                                    <?php echo $phone_number; ?>
                                </p>
                            </div>
                        </div>

                        <a href="<?php echo $post->guid ?>">
                            <button class="btn-custom">Xem tất cả</button>
                        </a>

                    </div>
                </div>
            </div>
            <br>
            <?php
            
        }
        wp_reset_postdata(); // Đặt lại dữ liệu bài viết
    }
    ?>

</div>

<?php get_footer(); ?>