<?php
if (!function_exists("custom_content_data")) {
    function custom_content_data()
    {
        // Define query arguments to retrieve 'projects' post type
        $query_args = array(
            'posts_per_page' => -1,
            // Retrieve all posts
            'post_type' => 'projects',
        );

        // Execute the query
        $project_data = new WP_Query($query_args);

        // Get the total number of posts found
        $total_posts = $project_data->found_posts;
        if ($project_data->have_posts()) {
            while ($project_data->have_posts()) {
                $project_data->the_post();
                $post_id = get_the_ID();

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
                            <img src="<?php echo $image_url ?>" alt="<?php echo get_the_title() ?>">
                        </div>
                        <div class="info-container-custom">
                            <div class="header-custom">
                                <div class="title-custom">
                                    <?php echo get_the_title() ?>
                                </div>
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
                        </div>
                    </div>
                </div>
                <?php
            }

        }
    }
    add_shortcode('custom_content_data', 'custom_content_data');
}

function workreap_prepare_pagination($pages = '', $range = 4) {
    $max_num_pages	= !empty($pages) && !empty($range) ? ceil($pages/$range) : 1;
    
    $big            = 999999999; 
    $pagination = paginate_links( array(
        'base'       => str_replace( $big, '%#%', get_pagenum_link( $big,false ) ),
        'format'     => '?paged=%#%',
        'type'       => 'array',
        'current'    => max( 1, get_query_var('paged') ),
        'total'      => $max_num_pages,
        'prev_text'  => '<i class="lnr lnr-chevron-left"></i>',
        'next_text'  => '<i class="lnr lnr-chevron-right"></i>',
    ) );
    
    ob_start();
    if ( ! empty( $pagination ) ) { ?>
        <div class='wt-paginationvtwo'>
            <nav class="wt-pagination">					
                <ul>
                    <?php
                        foreach ( $pagination as $key => $page_link ) {
                            $link           = htmlspecialchars($page_link);
                            $link           = str_replace( ' current', '', $link);
                            $activ_class    = '';
                            
                            if ( strpos( $page_link, 'current' ) !== false ) { 
                                $activ_class    = 'class="wt-active"'; 
                            } else if ( strpos( $page_link, 'next' ) !== false ) { 
                                $activ_class    = 'class="wt-nextpage"'; 
                            } else if ( strpos( $page_link, 'prev' ) !== false ) { 
                                $activ_class    = 'class="wt-prevpage"'; 
                            }
                        ?>
                            <li <?php echo do_shortcode($activ_class);?> > <?php echo wp_specialchars_decode($link,ENT_QUOTES); ?> </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    <?php
    }
    echo ob_get_clean();
}
?>