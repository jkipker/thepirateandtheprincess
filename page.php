<?php 
get_header();
the_post();
global $teo_data;
$category = get_the_category(); 
$variation = 0;
if(isset($teo_data['header-type']) && $teo_data['header-type'] != '') {
    $variation = (int)$teo_data['header-type'];
}
if($variation == 0 || $variation == '') {
    $variation = 1;
}
$fullwidth = 1;
if(get_post_meta($post->ID, '_single_fullwidth', true) == 2) {
    $fullwidth = 0;
}
?>

<main role="main" id="post-container" class="text-editor">
    <?php 
    get_header('variation' . $variation);     
    if($fullwidth == 0) {
        $class = 'col-sm-8';
    }
    else {
        $class = 'col-sm-12 col-md-8 col-md-offset-2';
    }
    ?>

    <div <?php post_class($class);?>>
        <div class="left-container">
            <div class="post-inside">
                <h1 class="page_title"><?php the_title();?></h1>
                <?php the_content();?>
                <div style="clear: both"></div>
                <?php wp_link_pages(array('before' => '<p class="paginate_single"><strong>'.esc_html__('Pages','arwyn').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
                <div style="clear: both"></div>
            </div>


        </div>
    </div>
    
    <?php if($fullwidth == 0) { ?>
        <div class="col-sm-4">
            <?php get_sidebar();?>
        </div>
    <?php } ?>

</main>

<?php get_footer();?>