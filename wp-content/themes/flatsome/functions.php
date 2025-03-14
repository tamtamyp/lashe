<?php

/**
 * Flatsome functions and definitions
 *
 * @package flatsome
 */

require get_template_directory() . '/inc/init.php';
require_once( get_theme_root() . '/flatsome/include/custom-content-data.php' );
require_once( get_theme_root() . '/flatsome/include/custom-content-data.php' );
/**
 * Note: It's not recommended to add any custom code here. Please use a child theme so that your customizations aren't lost during updates.
 * Learn more here: http://codex.wordpress.org/Child_Themes
 */
// Ẩn nút cập nhật WordPress core
// add_filter('pre_site_transient_update_core', '__return_null');

// // Ẩn nút cập nhật plugin
// add_filter('pre_site_transient_update_plugins', '__return_null');

// // Ẩn nút cập nhật theme
// add_filter('pre_site_transient_update_themes', '__return_null');
// // Tắt thêm mới giao diện
// add_action('admin_init', function () {
//   // Xóa menu "Giao diện" khỏi khu vực quản trị
//   remove_menu_page('plugins.php');
//   remove_submenu_page('themes.php', 'themes.php');
//   remove_submenu_page('themes.php', 'theme-editor.php');
//   remove_submenu_page('themes.php', 'tgmpa-install-plugins');
//   remove_menu_page( 'edit-comments.php');
//   // remove_menu_page( 'options-general.php');
//   remove_menu_page( 'tools.php');
//   remove_menu_page( 'wps_overview_page');
//   // Thêm một thông báo cho người dùng
//   add_action('admin_notices', function () {
//     echo '<div class="notice notice-warning">
//         <p>Thêm mới giao diện đã bị tắt.</p>
//       </div>';
//   });
// });
// function my_init() {
//     if (!is_admin()) {
//         // comment out the next two lines to load the local copy of jQuery
//         wp_deregister_script('jquery'); 
//         wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js', false, '1.3.2'); 
//         wp_enqueue_script('jquery');
//     }
// }
// add_action('init', 'my_init');
function custom_styles()
{
  /*
  * Hàm get_stylesheet_uri() sẽ trả về giá trị dẫn đến file style.css của theme
  * Nếu sử dụng child theme, thì file custom-style.css này vẫn load ra từ theme chính
  */
  wp_register_style('custom-style', get_template_directory_uri() . '/custom-style.css', 'all');
  wp_enqueue_style('custom-style');
}
add_action('wp_enqueue_scripts', 'custom_styles');
function ti_custom_javascript()
{
  wp_enqueue_script('example-script', get_template_directory_uri() . '/customjs.js');
}
add_action('wp_enqueue_scripts', 'ti_custom_javascript');
// Close comments 

add_action('admin_init', function () {
  // Redirect any user trying to access comments page
  global $pagenow;
  
  if ($pagenow === 'edit-comments.php') {
      wp_redirect(admin_url());
      exit;
  }

  // Remove comments metabox from dashboard
  remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

  // Disable support for comments and trackbacks in post types
  foreach (get_post_types() as $post_type) {
      if (post_type_supports($post_type, 'comments')) {
          remove_post_type_support($post_type, 'comments');
          remove_post_type_support($post_type, 'trackbacks');
      }
  }
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
  remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
  if (is_admin_bar_showing()) {
      remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
  }
});

// Disable comment-reply.min.js
function clean_header(){ wp_deregister_script( 'comment-reply' ); } add_action('init','clean_header');
function the_breadcrumb() {
  echo '<ul id="crumbs">';
if (!is_home()) {
  echo '<a href="';
  echo get_option('home');
  echo '">';
  echo 'Trang chủ';
  echo "</a> ";
  if (is_category() || is_single()) {
          the_category(' » ');
          if (is_single()) {
                  the_title(' » ');
          }
  } elseif (is_page()) {
          echo the_title(' » ');
  }
}
elseif (is_tag()) {single_tag_title();}
elseif (is_day()) {echo"<li>Archive for "; the_time('F jS, Y'); echo'</li>';}
elseif (is_month()) {echo"<li>Archive for "; the_time('F, Y'); echo'</li>';}
elseif (is_year()) {echo"<li>Archive for "; the_time('Y'); echo'</li>';}
elseif (is_author()) {echo"<li>Author Archive"; echo'</li>';}
elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<li>Blog Archives"; echo'</li>';}
elseif (is_search()) {echo"<li>Search Results"; echo'</li>';}
echo '</ul>';
}
add_shortcode('the_breadcrumb', 'the_breadcrumb');
function page_title_sc( ){
  return get_the_title();
}
add_shortcode( 'page_title', 'page_title_sc' );

function fix_pagination()
{ 
  $the_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
  $slug = $the_page->post_name;
  ?> <script>
    jQuery('.elementor-pagination').find('a').each(function() {
      var url = jQuery(this).attr('href');
      var pieces = url.split("/");
      if(pieces[4].length == 0){
        pieces[4]=1;
      }
      var newhrf = window.location.origin +'/<?php echo $slug; ?>/page/' + pieces[4];
      jQuery(this).attr('href', newhrf);
    });
  </script> <?php }
add_action('wp_footer', 'fix_pagination');
          // Tạo shortcode để hiển thị danh sách child pages của một trang cha
function list_child_pages_shortcode($atts)
{
  ob_start();
  
  // Thiết lập các tham số mặc định cho shortcode
  $atts = shortcode_atts(array(
  'parent_id' => get_the_ID(), // Lấy trang cha của trang hiện tại
  'orderby' => 'menu_order',
  'order' => 'ASC',
  ), $atts);
  
  // Truy vấn các trang con của trang cha
  $child_pages = new WP_Query(array(
  'post_type' => 'page',
  'post_parent' => $atts['parent_id'],
  'posts_per_page' => -1,
  'orderby' => $atts['orderby'],
  'order' => $atts['order'],
  ));
  
  if ($child_pages->have_posts()) :
  ?>
  <div class="child-pages-list row" id="row-2072933724">
    <?php while ($child_pages->have_posts()) : $child_pages->the_post();
      $thumbnail_id = get_post_thumbnail_id(get_the_ID());
      $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, 'full'); // Chuyển 'full' thành kích thước mong muốn
      $imageChild = empty($thumbnail_url) ? "/wp-content/themes/flatsome/image-not-found.png" : $thumbnail_url;
    ?>
      <div class="col medium-3 small-6 large-3">
        <div class="col-inner">
          <a href="<?php the_permalink(); ?>">
            <div class="box has-hover image-box-custom has-hover box-default box-text-bottom">
              <div class="box-image">
                <div class="image-zoom">
                  <img width="300" height="188" src="<?php echo $imageChild; ?>" class="attachment- size- ls-is-cached lazyloaded" alt="" decoding="async" fetchpriority="high" data-src="<?php echo $imageChild; ?>" data-eio-rwidth="300" data-eio-rheight="188" /><noscript><img width="300" height="188" src="<?php echo $imageChild; ?>" class="attachment- size-" alt="" decoding="async" fetchpriority="high" data-eio="l" /></noscript>
                </div>
              </div>
              <div class="box-text text-center" style="background-color: rgb(245, 245, 245)">
                <div class="box-text-inner">
                  <div id="text-1093628204" class="text">
                    <h3>
                      <span style="color: #01304c"><strong style="text-transform: uppercase;"><?php the_title(); ?></strong></span>
                    </h3>
  
                    <style>
                      #text-1093628204 {
                        color: rgb(1, 48, 76);
                      }
  
                      #text-1093628204>* {
                        color: rgb(1, 48, 76);
                      }
                    </style>
                  </div>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
  
    <?php endwhile; ?>
  </div>
  <?php
  wp_reset_postdata(); // Đặt lại trạng thái sau khi hoàn thành vòng lặp
  else :
  echo 'Không có trang con.';
  endif;
  
  return ob_get_clean();
  }
  add_shortcode('list_child_pages', 'list_child_pages_shortcode');