<?php 
/* 
Template name: Homepage version 2
*/
the_post();
$nrposts = get_post_meta($post->ID, '_home2_nrposts', true);
if($nrposts == 0) {
    $nrposts = 8;
}
$categories = get_post_meta($post->ID, '_home2_categories', true);
$categories_normal = get_post_meta($post->ID, '_home2_categories_normal', true);
get_header();
global $teo_data;

$variation = 0;
if(isset($teo_data['header-type']) && $teo_data['header-type'] != '') {
    $variation = (int)$teo_data['header-type'];
}
if($variation == 0 || $variation == '') {
    $variation = 1;
}
?>

<main role="main" id="post-container">
    <?php get_header('variation' . $variation);?>
    <section class="normal-slider-wrap">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <section class="slider-controls">
                        <button type="button" class="next"><i class="icon-angle-right"></i></button>
                        <div class="counter">
                            <span class="current-slide">1</span>
                            <span class="separator">/</span>
                            <span class="count-slides">3</span>
                        </div>
                        <button type="button" class="prev"><i class="icon-angle-left"></i></button>
                    </section>
                    <div class="normal-slider">
                        <?php
                        $args = array();
                        $args['post_type'] = 'post';
                        $args['posts_per_page'] = $nrposts;
                        if(is_array($categories) ) {
                            $args['category__in'] = $categories;
                        }
                        $query = new WP_Query($args);
                        $i = 1;
                        while($query->have_posts() ) : $query->the_post(); global $post;
                            $thumb = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
                            $resized = teo_resize($thumb, 1140, 700);
                            $bginline = '';
                            if($resized != '') {
                                $bginline = ' style="background-image: url(\'' . $resized . '\')"';
                            }
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
                            ?>
                            <article class="slide <?php if($i==1) echo 'visible';?>" <?php echo $bginline;?>>
                                <div class="overlay"></div>
                                <div class="inner">
                                    <div class="inner-overlay">
                                        <h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
                                        <div class="details">
                                            <?php echo __('by', 'arwyn') . ' '; the_author_posts_link();?> <span>/</span>
                                            <a href="<?php the_permalink();?>"><?php the_time( get_option( 'date_format' ) ); ?></a> <span>/</span>
                                            <?php the_category(', '); ?>
                                        </div>
                                        <div class="separator"></div>
                                        <p><?php echo wp_trim_words(get_the_content(), 55, '...');?></p>
                                        <div class="separator"></div>
                                        <div class="icons">
                                            <a href="<?php echo comments_link();?>"><i class="icon-comment"></i> <span><?php comments_number('0', '1', '%'); ?></span></a>

                                            <a href="#" data-id="<?php echo $post->ID;?>" class="text icn-like <?php if($liked == 1) echo 'liked';?>">
                                                <i class="icon-heart"></i>
                                                <span><?php echo $likes;?></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php $i++; endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <div class="left-container text-editor">
                    <?php 
                    echo '<span class="post_id" data-id="' . $post->ID . '" style="display: none"></span>';
                    $args = array();
                    $args['post_type'] = 'post';
                    if(is_array($categories_normal) ) {
                        $args['category__in'] = $categories_normal;
                    }
                    query_posts($args);
                    while(have_posts() ) : the_post(); global $post;
                        get_template_part('content', 'layout2');
                    endwhile; wp_reset_query(); ?>
                </div>
            </div>
            <div class="col-sm-4">
                <?php get_sidebar();?>
            </div>
        </div>
    </div>

</main>

<?php get_template_part('infinitescroll', 'template-2');?>

<?php get_footer();?>