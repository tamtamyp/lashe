<?php

/**
 * Template name: Page - My Template
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

get_header(); ?>
<div class="parallax-title mb">
    <?php while (have_posts()) : the_post(); ?>
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

<div id="content" role="main">

    <?php while (have_posts()) : the_post(); ?>

        <?php the_content(); ?>

    <?php endwhile; // end of the loop. 
    ?>

</div>
<br>
<?php echo do_shortcode('[block id="dang-ky-nhan-bang-gia-uu-dai-tu-chu-dau-tu"]'); 
    $the_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
    $slug = $the_page->post_name;
    if($slug=="tin-tuc"){
        echo do_shortcode('[elementor-template id="640"]');
    }else if($slug=="tien-do"){
        echo do_shortcode('[elementor-template id="734"]');
    }
?>
<?php get_footer(); ?>