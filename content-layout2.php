<?php
global $teo_data;
$thumb = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
$resized = teo_resize($thumb, 710, 336);
$likes = (int)get_post_meta($post->ID, '_teo_nr_likes', true);
$postIPs = get_post_meta($post->ID, '_teo_post_likes', true);
$liked = 0;
$ip = $_SERVER['REMOTE_ADDR'];
if($postIPs) {
    $postIPs = unserialize($postIPs);

    if(is_array($postIPs) && in_array($ip, $postIPs) ) {
        $liked = 1;
    }
}

if($teo_data['excerpt_type'] == 1) {
    $excerpt = wp_trim_words(get_the_content(), 70, '...');
}
else {
    $excerpt = get_the_excerpt();
}
?>
<div class="homepage-post">
    <figure>
        <div class="image-overlay">
            <?php if(has_post_thumbnail($post->ID) && $resized != '' ) { ?>
                <img src="<?php echo $resized;?>" alt="<?php the_title();?>" />
            <?php } else { ?>
                <img src="<?php echo get_template_directory_uri() . '/content/placeholder.png';?>" alt="<?php the_title();?>" />
            <?php } ?>
        </div>
        <div class="overlay">
            <div class="inner">
                <div class="figure-text">
                    <h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                    <hr class="hidden-xs"/>
                    <h5 class="hidden-xs">
                        <?php if(isset($teo_data['show_postauthor']) && $teo_data['show_postauthor'] == 1) { 
                                echo __('by', 'arwyn') . ' '; the_author_posts_link();?> / 
                        <?php } ?>
                        
                        <?php if(isset($teo_data['show_publishdate']) && $teo_data['show_publishdate'] == 1) { ?>
                            <a href="<?php the_permalink();?>"><?php the_time( get_option( 'date_format' ) ); ?></a>  / 
                        <?php } ?>

                        <?php if(isset($teo_data['show_categories']) && $teo_data['show_categories'] == 1) { 
                            the_category(', '); 
                        } ?>
                    </h5>
                </div>
            </div>
        </div>
    </figure>
    <p><?php echo $excerpt;?></p>
    <div class="info post-share">
        <a href="<?php echo comments_link();?>" class="text">
            <i class="icon-comment"></i>
            <span><?php comments_number('0', '1', '%'); ?></span>
        </a>
        <a href="#" data-id="<?php echo $post->ID;?>" class="text icn-like <?php if($liked == 1) echo 'liked';?>">
            <i class="icon-heart"></i>
            <span><?php echo $likes;?></span>
        </a>
        <?php if(isset($teo_data['show_share']) && $teo_data['show_share'] == 1) { ?>
            <div class="dropdown">
                <a class="text" data-toggle="dropdown" href="#">
                    <i class="icon-share"></i>
                    <?php _e('Share', 'arwyn');?>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                    <li><a href="javascript:void(0)" data-href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink();?>"><i class="icon-facebook"></i></a></li>
                    <li><a href="javascript:void(0)" data-href="http://reddit.com/submit?url=<?php the_permalink();?>&amp;title=<?php the_title();?>"><i class="icon-reddit"></i></a></li>
                    <li><a href="javascript:void(0)" data-href="https://twitter.com/intent/tweet?source=tweetbutton&amp;text=<?php the_title();?>&url=<?php the_permalink();?>"><i class="icon-twitter"></i></a></li>
                    <li><a href="javascript:void(0)" data-href="https://plus.google.com/share?url=<?php the_permalink();?>"><i class="icon-googleplus icon-smaller"></i></a></li>
                    <li><a href="javascript:void(0)" data-href="http://www.stumbleupon.com/submit?url=<?php the_permalink();?>&amp;title=<?php the_title();?>"><i class="icon-stumbleupon icon-smaller"></i></a></li>
                    <li><a class="dohref" href="mailto:?Subject=<?php the_title();?>&amp;Body=<?php _e('Check this great post', 'arwyn'); echo ' '; the_permalink();?>"><i class="icon-mail icon-smaller"></i></a></li>
                </ul>
            </div>
        <?php } ?>
    </div>
</div>