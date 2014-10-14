<?php 
get_header();
$title = sprintf(__('Posts tagged &quot;%s&quot;', 'arwyn'), single_tag_title('', false) );
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

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="category-section">
                    <h1><?php echo $title;?></h1>
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