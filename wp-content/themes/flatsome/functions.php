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
  echo '';
  endif;
  
  return ob_get_clean();
  }
  add_shortcode('list_child_pages', 'list_child_pages_shortcode');
// Hàm lấy mã màu từ taxonomy hoặc từ HTML class
function get_color_code_from_taxonomy($term_slug, $taxonomy) {
  $term = get_term_by('slug', $term_slug, $taxonomy);

  if (!$term) {
      return '';
  }

  // Lấy tất cả metadata của term
  $term_meta = get_term_meta($term->term_id);

  // Kiểm tra các key phổ biến
  $possible_keys = ['product_attribute_color', 'swatch_color', 'pa_color', 'pa_mau-sac'];

  foreach ($possible_keys as $key) {
      if (!empty($term_meta[$key][0])) {
          return $term_meta[$key][0]; // Trả về mã màu nếu tìm thấy
      }
  }

  return ''; // Trả về chuỗi rỗng nếu không tìm thấy
}

// Hàm lấy biến thể của sản phẩm
function get_variations_from_current_product($product_id) {
  $variations_list = [];
  $unique_colors = []; // Mảng để kiểm tra trùng lặp mã màu
  $unique_sizes = []; // Mảng để kiểm tra trùng lặp mã màu
  $product = wc_get_product($product_id);

  if ($product && $product->is_type('variable')) {
      $variations = $product->get_children();

      foreach ($variations as $variation_id) {
          $variation_obj = wc_get_product($variation_id);

          if ($variation_obj && $variation_obj->exists()) {
              $attributes = $variation_obj->get_attributes();
              $color_code = '';
              $kich_thuoc = '';

              // Lấy mã màu từ thuộc tính pa_mau-sac
              if (isset($attributes['pa_mau-sac'])) {
                  $color_slug = $attributes['pa_mau-sac'];
                  $color_code = get_color_code_from_taxonomy($color_slug, 'pa_mau-sac');
                  
                  // Chỉ thêm mã màu nếu chưa tồn tại trong mảng $unique_colors
                  if ($color_code && !in_array($color_code, $unique_colors)) {
                      $unique_colors[] = $color_code;
                  }
              }

              // Lấy kích thước từ thuộc tính pa_kich-thuoc
              if (isset($attributes['kich-thuoc'])) {
                $kich_thuoc = $attributes['kich-thuoc'];
                
                if ($kich_thuoc && !in_array($kich_thuoc, $unique_sizes)) {
                    $unique_sizes[] = $kich_thuoc;  // Thêm vào danh sách kích thước duy nhất
                }
            }
            else if(isset($attributes['pa_kich-thuoc'])){
              $kich_thuoc = $attributes['pa_kich-thuoc'];
            
              if ($kich_thuoc && !in_array($kich_thuoc, $unique_sizes)) {
                  $unique_sizes[] = $kich_thuoc;  // Thêm vào danh sách kích thước duy nhất
              }
            }

              $variations_list[] = [
                  'variation_id' => $variation_obj->get_id(),
                  'price'        => $variation_obj->get_price(),
                  'sale_price'   => $variation_obj->get_sale_price(),
                  'image'        => wp_get_attachment_image_url($variation_obj->get_image_id(), 'thumbnail'),
                  'attributes'   => $attributes,
                  'color_code'   => $color_code,
                  'kich_thuoc'   => $kich_thuoc,
                  'stock'        => $variation_obj->get_stock_quantity(),
              ];
          }
      }
  }

  return [
      'variations_list' => $variations_list,
      'unique_colors'   => $unique_colors,
      'unique_sizes'   => $unique_sizes
  ];
}

// Shortcode lấy biến thể của sản phẩm hiện tại
add_shortcode('get_current_product_variations', function() {
  global $product;

  $product_id = ($product && is_a($product, 'WC_Product')) ? $product->get_id() : get_the_ID();
  
  if (!$product_id) {
      return 'Không tìm thấy sản phẩm hiện tại.';
  }

  $result = get_variations_from_current_product($product_id);
  $variations = $result['variations_list'];
  $unique_colors = $result['unique_colors'];

  if (empty($variations)) {
      return '';
  }

  ob_start();
  ?>
  <div class="variations-list">
      <div class="unique-colors">
          <?php if (!empty($unique_colors)) : ?>
            <br>
                  <?php foreach ($unique_colors as $color) : ?>
                          <span style="display: inline-block; width: 30px; height: 30px; background-color: 
                              <?php echo esc_attr($color); ?>; border-radius: 50%;"> </span>
                  <?php endforeach; ?>
          <?php else : ?>
          <?php endif; ?>
      </div>
  </div>
  <?php
  return ob_get_clean();
});

add_shortcode('get_current_product_size_variations', function() {
  global $product;

  $product_id = ($product && is_a($product, 'WC_Product')) ? $product->get_id() : get_the_ID();
  
  if (!$product_id) {
      return 'Không tìm thấy sản phẩm hiện tại.';
  }

  $result = get_variations_from_current_product($product_id);
  $variations = $result['variations_list'];
  $unique_sizes = $result['unique_sizes'];

  if (empty($variations)) {
      return '';
  }

  ob_start();
  ?>
  <div class="variations-list">
      <div class="unique-colors">
          <?php if (!empty($unique_sizes)) : ?>
            <br>
            <div class="size-container-custom">
                  <?php foreach ($unique_sizes as $size) : ?>
                        <div class="size-item-custom">
                            <div class="size-box-custom"><?php echo esc_html(strtoupper($size)); ?> </div>
                        </div>
                  <?php endforeach; ?>
            </div>
          <?php else : ?>
          <?php endif; ?>
      </div>
  </div>
  <?php
  return ob_get_clean();
});
add_shortcode('get_current_product_price_variations', function() {
  global $product;

  $product_id = ($product && is_a($product, 'WC_Product')) ? $product->get_id() : get_the_ID();
  
  if (!$product_id) {
      return 'Không tìm thấy sản phẩm hiện tại.';
  }

  $result = get_product_price($product_id);

  $price = $result['price'];
  $sale_price = $result['sale_price'];
  ob_start();
  if($sale_price >0){
    
  ?>
  <div class="price-container">
        <span class="current-price"><?php echo esc_html(number_format($sale_price, 0, ',', '.')); ?>đ</span>
        <span class="original-price"><?php echo esc_html(number_format($price, 0, ',', '.')); ?>đ</span>
        <span class="discount-percent">-<?php echo esc_html(calculate_discount_percentage($price, $sale_price)); ?>%</span>
    </div>
  <?php
  }else{
    ?>
      <div class="price-container">
              <span class="current-price"><?php echo esc_html(number_format($price, 0, ',', '.')); ?>đ</span>
          </div>
    <?php
  }
  return ob_get_clean();
});
function get_product_price($product_id) {
  $product = wc_get_product($product_id);
  $price = 0;
  $sale_price = 0;

  if ($product) {
    if ($product->is_type('variable')) { 
        $variations = $product->get_children();

        if (!empty($variations)) {
            $first_variation = wc_get_product($variations[0]);
            
            if ($first_variation) {
                $price = $first_variation->get_regular_price();
                $sale_price = $first_variation->get_sale_price();
            }
        }
    } else { 
        $price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
    }
}

  return [
      'price' => $price,
      'sale_price' => $sale_price
  ];
}

function calculate_discount_percentage($original_price, $sale_price) {
  if ($original_price <= 0) return 0; // Tránh chia cho 0
  $discount = (($original_price - $sale_price) / $original_price) * 100;
  return round($discount); // Làm tròn tới số nguyên gần nhất
}
add_shortcode('get_current_product_sale_variations', function() {
  global $product;

  $product_id = ($product && is_a($product, 'WC_Product')) ? $product->get_id() : get_the_ID();
  
  if (!$product_id) {
      return 'Không tìm thấy sản phẩm hiện tại.';
  }

  $result = get_product_price($product_id);
  $sale_price = $result['sale_price'];
  ob_start();
  if($sale_price >0){
    
  ?>
  <span class="p-icon-sale">Sale</span>
  <?php
  }
  return ob_get_clean();
});
// Tạo shortcode lấy description của sản phẩm
function get_product_description_shortcode($atts) {
  global $product;

  if (!is_product() || !$product) {
      return 'Không tìm thấy sản phẩm.';
  }

  // Lấy phần mô tả của sản phẩm
  $description = $product->get_description();

  if (empty($description)) {
      return '';
  }

  return wpautop($description); // Giữ lại định dạng HTML của mô tả
}
add_shortcode('product_description', 'get_product_description_shortcode');

function custom_category_menu_shortcode() {
  $categories = get_terms(array(
      'taxonomy'   => 'category', // Lấy danh mục bài viết
      'hide_empty' => true, // Chỉ lấy danh mục có bài viết
  ));

  if (empty($categories) || is_wp_error($categories)) {
      return '<p>Không có danh mục nào.</p>';
  }

  $current_category_id = get_queried_object_id();

  ob_start();
  ?>
  <div class="menu-container-custome">
      <div class="menu-header-custome">Danh mục</div>
      <ul class="menu-list-custome">
          <?php foreach ($categories as $category) : ?>
              <?php $active_class = ($category->term_id == $current_category_id) ? ' active-custome' : ''; ?>
              <li class="menu-item-custome<?php echo $active_class; ?>">
                  <a href="<?php echo esc_url(get_term_link($category)); ?>">
                      <?php echo esc_html($category->name); ?>
                  </a>
              </li>
          <?php endforeach; ?>
      </ul>
  </div>
  <style>
      .menu-container-custome {
          width: 100%; /* Chiếm 1/3 chiều rộng trang */
          max-width: 400px; /* Giới hạn độ rộng tối đa */
          border-radius: 30px; /* Bo viền 30px */
          overflow: hidden;
          box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      }
      .menu-header-custome {
          background-color: #A70D38;
          color: white;
          padding: 10px 15px;
          font-weight: bold;
          border-radius: 30px 30px 0 0;
      }
      .menu-list-custome {
          list-style: none;
          padding: 0;
          margin: 0;
      }
      .menu-item-custome {
          padding: 12px 15px;
          border-bottom: 1px solid #eee;
          cursor: pointer;
          transition: background 0.3s, color 0.3s;
      }
      .menu-item-custome a {
          text-decoration: none;
          color: black;
          display: block;
      }
      .menu-item-custome:hover {
          background-color: #f8f8f8;
      }
      .menu-item-custome:hover a {
          color: #A70D38;
      }
      .menu-item-custome.active-custome a {
          color: #A70D38; /* Màu chữ giống hover */
      }
  </style>
  <?php
  return ob_get_clean();
}
