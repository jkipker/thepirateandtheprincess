<?php 
the_post();
get_header();
$thumb = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
$has_sidebar = 0;
$likes = (int)get_post_meta($post->ID, '_teo_nr_likes', true);
$ip = $_SERVER['REMOTE_ADDR'];
$postIPs = get_post_meta($post->ID, '_teo_post_likes', true);
$liked = 0;
if($postIPs) {
    $postIPs = unserialize($postIPs);

    if(is_array($postIPs) && in_array($ip, $postIPs) ) {
        $liked = 1;
    }
}
global $teo_data;

$variation = 0;
if(isset($teo_data['header-type']) && $teo_data['header-type'] != '') {
    $variation = (int)$teo_data['header-type'];
}
if($variation == 0 || $variation == '') {
    $variation = 1;
}

if(isset($teo_data['logo2']) && $teo_data['logo2']['url'] != '') {
    $logo = $teo_data['logo2']['url'];
}
else {
    $logo = get_template_directory_uri() . '/img/logo-white-small.png';
}

if($variation == 1 || $variation == 2 || $variation == 3) { ?>
    <nav class="st-menu st-effect-1" id="menu-1">
        <button class="close-side-menu"><i class="icon-close"></i></button>
        <?php wp_nav_menu(array(
            'theme_location' => 'top-menu',
            'container' => '',
            'echo' => true,
            'depth' => 0 ) 
        );
        ?>
    </nav>
<?php } 
$class = '';
if($variation == 1 || $variation == 2 || $variation == 3) {
    $class = 'type-1';
}
elseif($variation == 4) {
    $class = 'type-2';
}
elseif($variation == 5 || $variation == 6) {
    $class = 'type-3';
}

$fullwidth = 1;
if(get_post_meta($post->ID, '_single_fullwidth', true) == 2) {
    $fullwidth = 0;
}
?>
<header class="image-header <?php echo $class;?>" style="background-image: url('<?php echo $thumb;?>')">
    <div class="single-overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <?php if($variation == 1 || $variation == 2 || $variation == 3) { ?>
                    <button type="button" class="menu-icon open-side-menu">
                        <span class="icon-bar"></span>
                        <span class="icon-bar middle"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="<?php echo home_url();?>">
                        <figure>
                            <img src="<?php echo esc_url($logo);?>" alt="<?php bloginfo('name');?>" />
                        </figure>
                    </a>
                    <a href="<?php echo home_url();?>" class="logo-text"><?php bloginfo('name');?></a>
                    <?php if($variation == 2 || $variation == 3) { //extra tagline for variations 2 and 3 ?>
                        <div class="logo-extra"><?php bloginfo('description');?></div>
                    <?php } ?>

                    <?php get_template_part('header', 'social');?>

                <?php } 
                else if($variation == 4 || $variation == 5 || $variation == 6) { ?>
                    <div class="single-menu-content">
                        <a href="<?php echo home_url();?>">
                            <figure>
                                <img src="<?php echo esc_url($logo);?>" alt="<?php bloginfo('name');?>" />
                            </figure>
                        </a>
                        <?php 
                        wp_nav_menu(array(
                            'theme_location' => 'top-menu',
                            'container' => '',
                            'echo' => true,
                            'depth' => 1 ) 
                        );
                        if($variation == 4) { 
                            get_template_part('header', 'social');
                        }
                        elseif($variation == 6) { ?>
                            <div class="logo-extra"><?php bloginfo('description');?></div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="content">
                    <h1><?php the_title();?></h1>
                    <h4>
	                    <?php if(isset($teo_data['show_postauthor']) && $teo_data['show_postauthor'] == 1) { 
                            echo __('by', 'arwyn') . ' '; the_author_posts_link();?> /
                        <?php } ?> 
	                    
                        <?php if(isset($teo_data['show_publishdate']) && $teo_data['show_publishdate'] == 1) { ?>
                            <a href="<?php the_permalink();?>"><?php the_time( get_option( 'date_format' ) ); ?></a> / 
                        <?php } ?>

	                    <?php if(isset($teo_data['show_categories']) && $teo_data['show_categories'] == 1) { 
                            the_category(', '); 
                        } ?>
                    </h4>
                </div>
            </div>
        </div>
    </div>
</header>

<main role="main" id="post-container" class="text-editor white-bg">
    <div class="container">
        <div class="row">
        	<?php
        	if($fullwidth == 0) {
        		$class = 'col-sm-8';
        	}
        	else {
        		$class = 'col-sm-12 col-md-8 col-md-offset-2';
        	}
        	?>
        	<div <?php post_class($class);?>>
				<div class="left-container">
			        <div class="post-header">
				        <div class="post-header-border">
				            <?php if(isset($teo_data['show_navigationposts']) && $teo_data['show_navigationposts'] == 1) { ?>
                                <div class="navigation">
    				            	<?php 
    				            	previous_post_link('%link', '<i class="icon-angle-left"></i>
    				                    <span class="text">' . __('Previous', 'arwyn') . '</span>');
    				            	next_post_link('%link', '<span class="text">' . __('Next', 'arwyn') . '</span>
    				                    <i class="icon-angle-right"></i>');
    				                ?>
    			                </div>
                            <?php } ?>
                            
				            <div class="info">
				                <div class="text">
				                    <a href="<?php echo comments_link();?>"><i class="icon-comment"></i> <span><?php comments_number('0', '1', '%'); ?></span></a>
				                </div>
			                    <div class="text">
			                        <a href="#" data-id="<?php echo $post->ID;?>" class="text icn-like <?php if($liked == 1) echo 'liked';?>">
                                        <i class="icon-heart"></i>
                                        <span><?php echo $likes;?></span>
                                    </a>
			                    </div>
                                <?php if(isset($teo_data['show_share']) && $teo_data['show_share'] == 1) { ?>
    			                    <div class="text sticky-share">
    				                    <div class="dropdown">
    				                        <i class="icon-share"></i>
    				                        <a data-toggle="dropdown" href="#"><?php _e('Share', 'arwyn');?></a>
    				                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
    				                            <li><a href="javascript:void(0)" data-href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink();?>"><i class="icon-facebook"></i></a></li>
                                                <li><a href="javascript:void(0)" data-href="http://reddit.com/submit?url=<?php the_permalink();?>&amp;title=<?php the_title();?>"><i class="icon-reddit"></i></a></li>
                                                <li><a href="javascript:void(0)" data-href="https://twitter.com/intent/tweet?source=tweetbutton&amp;text=<?php the_title();?>&url=<?php the_permalink();?>"><i class="icon-twitter"></i></a></li>
                                                <li><a class="dohref" href="javascript:void((function()%7Bvar%20e=document.createElement(&apos;script&apos;);e.setAttribute(&apos;type&apos;,&apos;text/javascript&apos;);e.setAttribute(&apos;charset&apos;,&apos;UTF-8&apos;);e.setAttribute(&apos;src&apos;,&apos;http://assets.pinterest.com/js/pinmarklet.js?r=&apos;+Math.random()*99999999);document.body.appendChild(e)%7D)());"><i class="icon-pinterest"></i></a></li>
                                                <li><a href="javascript:void(0)" data-href="https://plus.google.com/share?url=<?php the_permalink();?>"><i class="icon-googleplus icon-smaller"></i></a></li>
                                                <li><a href="javascript:void(0)" data-href="http://www.stumbleupon.com/submit?url=<?php the_permalink();?>&amp;title=<?php the_title();?>"><i class="icon-stumbleupon icon-smaller"></i></a></li>
                                                <li><a href="javascript:void(0)" data-href="http://www.tumblr.com/share/link?url=<?php echo preg_replace('#^https?://#', '', rtrim(get_permalink(),'/'));?>&amp;name=<?php the_title();?>"><i class="icon-tumblr icon-smaller"></i></a></li>
                                                <li><a class="dohref" href="mailto:?Subject=<?php the_title();?>&amp;Body=<?php _e('Check this great post', 'arwyn'); echo ' '; the_permalink();?>"><i class="icon-mail icon-smaller"></i></a></li>
    				                        </ul>
    				                    </div>
    				                </div>
                                <?php } ?>
				            </div>
				        </div>
				    </div>
				    <div class="post-inside top-padded">
				        <?php the_content();?>
                        <div style="clear: both"></div>
                        <?php wp_link_pages(array('before' => '<p class="paginate_single"><strong>'.esc_html__('Pages','arwyn').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
                        <div style="clear: both"></div>
                        <div class="tags">
                            <strong><?php _e('Tags:', 'arwyn');?></strong>
                            <?php the_tags('', ', ');?>
                        </div>
				    </div>

				    <?php if(isset($teo_data['show_share']) && $teo_data['show_share'] == 1) { ?>
                        <div class="post-sidebar-padding">
    				        <div class="post-section post-share post-share-sidebar">
    				            <div class="dropdown">
    				                <i class="icon-share"></i>
    				                <a data-toggle="dropdown" href="#"><?php _e('Share', 'arwyn');?></a>
    				                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
    				                    <li><a href="javascript:void(0)" data-href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink();?>"><i class="icon-facebook"></i></a></li>
                                        <li><a href="javascript:void(0)" data-href="http://reddit.com/submit?url=<?php the_permalink();?>&amp;title=<?php the_title();?>"><i class="icon-reddit"></i></a></li>
    				                    <li><a href="javascript:void(0)" data-href="https://twitter.com/intent/tweet?source=tweetbutton&amp;text=<?php the_title();?>&url=<?php the_permalink();?>"><i class="icon-twitter"></i></a></li>
    				                    <li><a class="dohref" href="javascript:void((function()%7Bvar%20e=document.createElement(&apos;script&apos;);e.setAttribute(&apos;type&apos;,&apos;text/javascript&apos;);e.setAttribute(&apos;charset&apos;,&apos;UTF-8&apos;);e.setAttribute(&apos;src&apos;,&apos;http://assets.pinterest.com/js/pinmarklet.js?r=&apos;+Math.random()*99999999);document.body.appendChild(e)%7D)());"><i class="icon-pinterest"></i></a></li>
    				                    <li><a href="javascript:void(0)" data-href="https://plus.google.com/share?url=<?php the_permalink();?>"><i class="icon-googleplus icon-smaller"></i></a></li>
    				                    <li><a href="javascript:void(0)" data-href="http://www.stumbleupon.com/submit?url=<?php the_permalink();?>&amp;title=<?php the_title();?>"><i class="icon-stumbleupon icon-smaller"></i></a></li>
    				                    <li><a href="javascript:void(0)" data-href="http://www.tumblr.com/share/link?url=<?php echo preg_replace('#^https?://#', '', rtrim(get_permalink(),'/'));?>&amp;name=<?php the_title();?>"><i class="icon-tumblr icon-smaller"></i></a></li>
    				                    <li><a class="dohref" href="mailto:?Subject=<?php the_title();?>&amp;Body=<?php _e('Check this great post', 'arwyn'); echo ' '; the_permalink();?>"><i class="icon-mail icon-smaller"></i></a></li>
    				                </ul>
    				            </div>
    				        </div>
    				    </div>
                    <?php } ?>

				    <?php if(isset($teo_data['show_authorbox']) && $teo_data['show_authorbox'] == 1) { ?>
                        <div class="author-single">
    				    	<?php
    				    	$description = get_the_author_meta('description');
    				    	$url = get_the_author_meta('user_url');
    				    	?>
    				        <figure>
    				            <?php echo get_avatar(get_the_author_meta('user_email'), 128, 128);?>
    				        </figure>
    				        <a href="#" class="name"><?php echo get_the_author_meta('display_name');?></a>
    				        <div class="contact"><?php echo $url;?></div>
    					    <div class="content"><?php echo $description;?></div>
    				    </div>
                    <?php } ?>

                    <?php if(isset($teo_data['show_related']) && $teo_data['show_related'] == 1) { ?>
                        <div class="post-sidebar-padding">
                            <div class="post-section">
                                <?php _e('Related news', 'arwyn');?>
                            </div>
                        </div>

                        <div class="related-posts">
                            <?php 
                            $tags = get_the_tags();
                            $args = array();
                            $include = array(); //ids to include in related news
                            if(is_array($tags) && count($tags) > 0) { 
                                foreach($tags as $tag) {
                                    $include[] = $tag->term_id;
                                }
                                $args['tag__in'] = $include;
                            }
                            else {
                                //no tags, we get categories
                                $categories = get_the_terms($post->ID, 'category');
                                if($categories && count($categories) > 0) {
                                    foreach($categories as $category) {
                                        $include[] = $category->term_id;
                                    }
                                    $args['category__in'] = $include;
                                }
                            }
                            $args['posts_per_page'] = 3;
                            $args['post_type'] = 'post';
                            //performance improvements
                            $args['no_found_rows'] = true;
                            $args['update_post_term_cache'] = true;
                            $args['update_post_meta_cache'] = true;
                            $query = new WP_Query($args);
                            while($query->have_posts() ) : $query->the_post(); ?>
                                <div class="col-xs-4">
                                    <div class="post">
                                        <?php if(has_post_thumbnail() ) { 
                                            $thumb = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
                                            ?>
                                            <figure>
                                                <img alt="<?php the_title();?>" src="<?php echo teo_resize($thumb, 220, 220);?>">
                                                <a class="overlay" href="<?php the_permalink();?>">
                                                    <div class="inner">
                                                        <i class="icon-plus"></i>
                                                    </div>
                                                    <div class="background"></div>
                                                </a>
                                            </figure>
                                        <?php } ?>
                                        <a class="post-title" href="<?php the_permalink();?>"><?php the_title();?></a>
                                        <span class="info"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ' . __('ago', 'arwyn'); ?></span>
                                    </div>
                                </div>
                            <?php endwhile; wp_reset_postdata();
                            ?>
                        </div>
                    <?php } ?>

                    <div style="clear: both"></div>

				    <?php comments_template('', true);?>

				</div>
			</div>
			<?php if($fullwidth == 0) { ?>
                <div class="col-sm-4">
                    <?php get_sidebar();?>
                </div>
            <?php } ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer();?>