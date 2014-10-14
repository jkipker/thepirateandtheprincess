<?php 
/* 
Template name: Homepage version 3
*/
the_post();
$nrposts = get_post_meta($post->ID, '_home3_nrposts', true);
if($nrposts == 0) {
    $nrposts = 6;
}
$categories = get_post_meta($post->ID, '_home3_categories', true);
$categories_normal = get_post_meta($post->ID, '_home3_categories_normal', true);
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
                                            <a href="#"><i class="icon-heart"></i> <span>652</span></a>
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

    <section class="homepage-articles">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <?php 
                    echo '<span class="post_id" data-id="' . $post->ID . '" style="display: none"></span>';
                    $args = array();
                    $args['post_type'] = 'post';
                    if(is_array($categories_normal) ) {
                        $args['category__in'] = $categories_normal;
                    }
                    query_posts($args);
                    while(have_posts() ) : the_post(); global $post;
                        $thumb = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
                        $resized = teo_resize($thumb, 650, 310);
                        $bginline = '';
                        if($resized != '') {
                            $bginline = ' style="background-image: url(\'' . $resized . '\')"';
                        }
                        ?>
                        <article class="slide" <?php echo $bginline;?>>
                            <div class="overlay"></div>
                            <div class="inner">
                                <div class="inner-overlay">
                                    <h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
                                    <div class="separator"></div>
                                    <div class="details">
                                        <?php echo __('by', 'arwyn') . ' '; the_author_posts_link();?> <span>/</span>
                                        <a href="<?php the_permalink();?>"><?php the_time( get_option( 'date_format' ) ); ?></a> <span>/</span>
                                        <?php the_category(', '); ?>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_query(); ?>
                </div>
            </div>
        </div>
    </section>

</main>

<script id="article-template" type="text/x-handlebars-template">
    <article class="slide" style="background-image: url({{image}})">
        <div class="overlay"></div>
        <div class="inner">
            <div class="inner-overlay">
                <h2><a href="{{link}}">{{title}}</a></h2>
                <div class="separator"></div>
                <div class="details">
                    <a href="{{authorLink}}">by {{author}}</a> <span>/</span>
                    <a href="{{dateLink}}">{{date}}</a> <span>/</span>
                    <a href="{{categoryLink}}">{{{category}}}</a>
                </div>
            </div>
        </div>
    </article>
</script>

<?php get_footer();?>