<?php 
add_action( 'after_setup_theme', 'teo_setup' );
if ( ! function_exists( 'teo_setup' ) ){
	function teo_setup(){
		load_theme_textdomain('arwyn', get_template_directory() .'/languages');
		require get_template_directory() . '/lib/comment.php';
		require get_template_directory() . '/lib/custom-functions.php';
		require get_template_directory() . '/lib/meta_boxes.php';
		require get_template_directory() . '/lib/widgets.php';

		require get_template_directory() . '/lib/redux/redux-framework.php';
		require get_template_directory() . '/lib/redux-options.php';

		//Post thumbnails support
		add_theme_support( 'post-thumbnails');

		add_theme_support( 'automatic-feed-links' );

		//Adding custom sidebars
		$args = array(
				'name'          => 'Right sidebar',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="title">',
				'after_title'   => '</div>' );
		register_sidebar($args);
		
		//Adding a custom menu location
		register_nav_menus( array( 'top-menu' => 'Top primary menu') );
	}
}

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once get_template_directory() . '/lib/meta_boxes/init.php';

}

// Loading css/js files into the theme
add_action('wp_enqueue_scripts', 'teo_scripts');
if ( !function_exists('teo_scripts') ) {
	function teo_scripts() {
		wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '1.0');
		wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/style.css', array(), '1.0');

		wp_enqueue_script( 'jquery');
		wp_enqueue_script( 'modernizr-respond.min', get_template_directory_uri() . '/js/modernizr-2.6.2-respond-1.1.0.min.js', '1.0');
		wp_enqueue_script( 'libraries', get_template_directory_uri() . '/js/libraries.js', array('jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-draggable'), '1.0', true);
		wp_enqueue_script( 'main', get_template_directory_uri() . '/js/main.js', array('jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-draggable'), '1.0', true);
		wp_localize_script( 'main', 'MyAjax', array( 'ajaxurl' => admin_url('admin-ajax.php') ) );

		if ( is_singular() && get_option( 'thread_comments' ) )
    		wp_enqueue_script( 'comment-reply' );
	}

}

//Loading the custom CSS from the theme options panel with a priority of 11, so it loads after the other css files

add_action('wp_head', 'teo_custom_css', 11);
function teo_custom_css() {
	global $teo_data;
	if(isset($teo_data['css-code']) && $teo_data['css-code'] != '')
		echo '<style type="text/css">' . $teo_data['css-code'] . '</style>';

	if(isset($teo_data['favicon']['url']) && $teo_data['favicon']['url'] != '') {
		echo '<link rel="shortcut icon" href="' . $teo_data['favicon']['url'] . '" />';
		echo '<link rel="apple-touch-icon" href="' . $teo_data['favicon']['url'] . '" />';
	}

	//custom CSS
	echo '<style type="text/css">';

	if(isset($teo_data['overlay-color']) && $teo_data['overlay-color'] != '') {
		echo '.homepage-articles article .overlay, .normal-slider article .overlay, .full-homepage .main-slider article .overlay, .full-homepage .sidebar-slider article .overlay, .home2-overlay, .image-overlay, .single-overlay { background-color: ' . $teo_data['overlay-color'] . ' !important; }';
	}

	if(isset($teo_data['overlay-percentage']) && $teo_data['overlay-percentage'] != '') {
		$percentage = $teo_data['overlay-percentage'];
		if($percentage < 0 || $percentage > 100) {
			$percentage = 0.25;
		}
		else {
			$percentage = (float)$percentage/100;
		}
		$percentage2 = (float)1-$percentage;
		echo '.homepage-articles article .overlay, .normal-slider article .overlay, .full-homepage .main-slider article .overlay, .full-homepage .sidebar-slider article .overlay, .home2-overlay, .single-overlay { -webkit-opacity: ' . $percentage . ' !important; -moz-opacity: ' . $percentage . ' !important; opacity: ' . $percentage . ' !important; }';
		echo '.image-overlay img { -webkit-opacity: ' . $percentage2 . ' !important; -moz-opacity: ' . $percentage2 . ' !important; opacity: ' . $percentage2 . ' !important; }';
	}

	if(isset($teo_data['opt-hover-color']) && $teo_data['opt-hover-color'] != '') {
		echo '.btn.btn-default { background: ' . $teo_data['opt-hover-color'] . '; }';
		echo '.st-menu ul li.selected ul:before, .st-menu ul li:hover > ul:before { border-top: 4px solid ' . $teo_data['opt-hover-color'] . '; }';
		echo '.text-editor .underline.orange { border-bottom: 1px solid ' . $teo_data['opt-hover-color'] . ';}';
	}

	echo '</style>';

}

function teo_list_pings($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; ?>
		<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?> - <?php comment_excerpt(); ?>
	<?php 
}
if ( ! isset( $content_width ) ) $content_width = 990;

add_action( 'wp_ajax_teo-infinite', 'teo_infinite_scroll' );
add_action( 'wp_ajax_nopriv_teo-infinite', 'teo_infinite_scroll' );

function teo_infinite_scroll() {
	$page = (int)$_GET['page'];
	$template = $_GET['template'];
	$postID = isset($_GET['post_id']) ? (int)$_GET['post_id'] : '';
	$posts_per_page = get_option('posts_per_page');
	$query_string = isset($_GET['query_string']) ? $_GET['query_string'] : '';
	if($template == 'home2' || $template == 'home3') {
		$args = '';
		if($query_string != '') {
			$args = $query_string . '&';
		}
		$extra = ''; //used on homepage 2 and 3 page templates
		if($template == 'home2' && isset($postID) && $postID != '' ) {
			$categories_normal = get_post_meta($postID, '_home2_categories_normal', true);
			if(is_array($categories_normal) && count($categories_normal) > 0) {
				$extra = '&cat=' . implode(',', $categories_normal);
			}
		}
		if($template == 'home3' && isset($postID) && $postID != '' ) {
			$categories_normal = get_post_meta($postID, '_home3_categories_normal', true);
			if(is_array($categories_normal) && count($categories_normal) > 0) {
				$extra = '&cat=' . implode(',', $categories_normal);
			}
		}
		$args .= 'post_type=post&posts_per_page=' . $posts_per_page . '&offset=' . $page * $posts_per_page . $extra;
		$query = new WP_Query($args);
		if($query->have_posts() ) :
			$json = array();
			$json['page'] = $page;
			$json['items'] = array();
			$count = 0;
			while($query->have_posts() ) : $query->the_post(); global $post;
				$thumb = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
				$json['items'][$count]['image'] = teo_resize($thumb, 710, 336);
				$json['items'][$count]['title'] = get_the_title();
				$json['items'][$count]['link'] = get_permalink();
				$json['items'][$count]['author'] = get_the_author();
				$json['items'][$count]['authorLink'] = get_author_posts_url(get_the_author_meta( 'ID' ) );
				$json['items'][$count]['date'] = get_the_time( get_option( 'date_format' ) );
				$json['items'][$count]['dateLink'] = get_permalink();
				//categories code
				$categories = get_the_category();
				$separator = ', ';
				$output = '';
				if($categories){
					foreach($categories as $category) {
						$output .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'arwyn' ), $category->name ) ) . '">' . $category->cat_name . '</a>' . $separator;
					}
					$output = trim($output, $separator);
				}
				//categories code end
				$json['items'][$count]['category'] = $output;
				if($template == 'home2') {
					$json['items'][$count]['excerpt'] = get_the_excerpt();
					$json['items'][$count]['commentCount'] = get_comments_number();
					$json['items'][$count]['heartCount'] = (int)get_post_meta($post->ID, '_teo_nr_likes', true);
				}
				$count++;
			endwhile;
			$json['totalPages'] = $query->max_num_pages;
			echo json_encode($json);
		endif; wp_reset_postdata();
	}
	die();
}

add_action( 'wp_ajax_like-submit', 'teo_like_submit' );
add_action( 'wp_ajax_nopriv_like-submit', 'teo_like_submit' );
 
function teo_like_submit() {
	// get the submitted parameters
	$postID = $_POST['post_id'];
 	$ip = $_SERVER['REMOTE_ADDR'];
 	$postIPs = get_post_meta($postID, '_teo_post_likes', true);
 	$likes = (int)get_post_meta($postID, '_teo_nr_likes', true);
 	$postIPs = unserialize($postIPs);

    if(!is_array($postIPs) || !in_array($ip, $postIPs) ) {
    	$postIPs[] = $ip;
    	update_post_meta($postID, '_teo_nr_likes', $likes + 1);
    }
    else {
    	$index = array_search($ip, $postIPs);
    	unset($postIPs[$index]);
    	update_post_meta($postID, '_teo_nr_likes', $likes - 1);
    }

    $postIPs = serialize($postIPs);
    update_post_meta($postID, '_teo_post_likes', $postIPs);
	die();
}

?>