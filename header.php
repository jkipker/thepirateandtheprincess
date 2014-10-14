<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <meta http-equiv="<?php echo get_template_directory_uri();?>/content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <title><?php wp_title('-');?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <?php
    global $teo_data;
    if(isset($teo_data['tracking-code']) && $teo_data['tracking-code'] != '') {
        echo $teo_data['tracking-code'];
    }
    wp_head(); 
    ?>
</head>
<?php 
$class = '';
if(is_page_template('page-template-home2.php') ||
    ( is_home() && !is_page() ) ||
    is_category() || is_archive() || is_tag() || is_search() ) {
    $class = 'has-infinite-scroll homepage2';
}
elseif(is_page_template('page-template-home3.php') ) {
    $class = 'has-infinite-scroll homepage3';
}
?>
<body <?php body_class($class);?>>