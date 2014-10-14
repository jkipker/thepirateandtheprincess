<?php 
get_header();
global $teo_data;

$auth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));

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

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="author-section">
                    <figure>
                        <?php echo get_avatar($auth->ID, 128, 128);?>
                    </figure>
                    <a class="name"><?php echo $auth->display_name;?></a>
                    <div class="contact">
                        <?php if($auth->user_url != '') { ?>
                            <a href="<?php echo $auth->user_url;?>"><?php echo $auth->user_url;?></a>
                        <?php } ?>
                    </div>
                    <div class="content">
                        <?php echo $auth->description;?>
                    </div>
                    <span class="posts">
                        <?php echo sprintf(__('%d posts', 'trendy'), get_the_author_posts() );?>
                        <br/>
                        <i class="icon-angle-down"></i>
                    </span>
                </div>
            </div>

            <div class="col-sm-8">
                <div class="left-container text-editor">
                    <?php 
                    global $query_string;
                    echo '<span class="query_parameters" data-parameters="' . $query_string . '" style="display: none"></span>';
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