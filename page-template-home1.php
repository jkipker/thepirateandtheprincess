<?php 
/* 
Template name: Homepage version 1
*/
the_post();
$nrposts = get_post_meta($post->ID, '_home1_nrposts', true);
if($nrposts == 0) {
    $nrposts = 6;
}
$categories = get_post_meta($post->ID, '_home1_categories', true);
get_header();
$ip = $_SERVER['REMOTE_ADDR'];
global $teo_data;
if(isset($teo_data['logo2']) && $teo_data['logo2']['url'] != '') {
    $logo = $teo_data['logo2']['url'];
}
elseif(isset($teo_data['logo']) && $teo_data['logo']['url'] != '') {
    $logo = $teo_data['logo']['url'];
}
else {
    $logo = get_template_directory_uri() . '/img/logo-white-small.png';
}
if($teo_data['excerpt_type'] == 1) {
    $excerpt = wp_trim_words(get_the_content(), 70, '...');
}
else {
    $excerpt = get_the_excerpt();
}
?>
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
<main role="main" id="main-container">
    <section class="full-homepage">
        <button type="button" class="menu-icon open-side-menu">
            <span class="icon-bar"></span>
            <span class="icon-bar middle"></span>
            <span class="icon-bar"></span>
        </button>
        <section class="left-section">
            <a href="<?php echo home_url();?>" class="big-logo"><img src="<?php echo $logo;?>" alt="<?php bloginfo('description');?>"/></a>
            <section class="main-slider">
            	<?php
            	$i = 1;
                $args = array();
                $args['post_type'] = 'post';
                $args['posts_per_page'] = $nrposts;
                if(is_array($categories) ) {
                    $args['category__in'] = $categories;
                }
                query_posts($args);
            	while(have_posts() ) : the_post(); 
            		$thumb = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
            		$bginline = '';
            		if($thumb != '') {
            			$bginline = ' style="background-image: url(\'' . $thumb . '\')"';
            		}
                    $likes = (int)get_post_meta($post->ID, '_teo_nr_likes', true);
                    $postIPs = get_post_meta($post->ID, '_teo_post_likes', true);
                    $liked = 0;
                    if($postIPs) {
                        $postIPs = unserialize($postIPs);

                        if(is_array($postIPs) && in_array($ip, $postIPs) ) {
                            $liked = 1;
                        }
                    }
            		?>
            		<article class="slide <?php if($i==1) echo 'visible';?>" <?php echo $bginline;?>>
	                    <div class="overlay"></div>
	                    <div class="inner">
	                        <div class="inner-overlay">
	                            <h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
	                            <div class="separator"></div>
	                            <p><?php echo $excerpt;?></p>
	                            <div class="separator"></div>
	                            <div class="icons">
	                                <a href="<?php echo comments_link();?>">
                                        <i class="icon-comment"></i> 
                                        <span><?php comments_number('0', '1', '%'); ?></span>
                                    </a>
	                                <a href="#" data-id="<?php echo $post->ID;?>" class="text icn-like <?php if($liked == 1) echo 'liked';?>">
                                        <i class="icon-heart"></i>
                                        <span><?php echo $likes;?></span>
                                    </a>
	                            </div>
	                        </div>
	                    </div>
	                </article>
            	<?php $i++; endwhile; wp_reset_query(); ?>
            </section>
        </section>
        <section class="right-section">
            <section class="sidebar-slider">
                <?php 
                $i=1;
                $args = array();
                $args['post_type'] = 'post';
                $args['posts_per_page'] = $nrposts;
                if(is_array($categories) ) {
                    $args['category__in'] = $categories;
                }
                query_posts($args);
                while(have_posts() ) : the_post();
                	$thumb = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
                	$resized = teo_resize($thumb, 600, 400);
            		$bginline = '';
            		if($resized != '') {
            			$bginline = ' style="background-image: url(\'' . $resized . '\')"';
            		}
                    $likes = (int)get_post_meta($post->ID, '_teo_nr_likes', true);
            		?>
	                <article class="slide <?php if($i==1) echo 'active';?>" <?php echo $bginline;?> data-slide-index="<?php echo $i-1;?>">
	                    <div class="overlay"></div>
	                    <div class="inner">
	                        <div class="inner-overlay">
	                            <div class="hidden-top">
	                                <div class="date">
	                                    <i class="icon-comment"></i> <span><?php comments_number('0', '1', '%'); ?></span>
	                                    <i class="icon-heart"></i> <span><?php echo $likes;?></span>
	                                </div>
	                                <div class="separator"></div>
	                            </div>
	                            <h2><?php the_title();?></h2>
	                            <div class="separator"></div>
	                            <div class="icons">
	                                <i class="icon-comment"></i> <span><?php comments_number('0', '1', '%'); ?></span>
	                                <i class="icon-heart"></i> <span><?php echo $likes;?></span>
	                            </div>
	                            <div class="hidden-bottom">
	                                <a href="<?php the_permalink();?>" class="article-link"><?php _e('View', 'arwyn');?></a>
	                            </div>
	                        </div>
	                    </div>
	                </article>
	            <?php $i++; endwhile; wp_reset_query(); ?>
            </section>
            <section class="slider-controls">
                <button type="button" class="prev"><i class="icon-angle-up"></i></button>
                <div class="counter">
                    <span class="current-slide">1</span>
                    <span class="separator">/</span>
                    <span class="count-slides">3</span>
                </div>
                <button type="button" class="next"><i class="icon-angle-down"></i></button>
            </section>
        </section>
    </section>
</main>

<?php get_footer();?>