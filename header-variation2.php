<?php
global $teo_data;
if(isset($teo_data['logo']) && $teo_data['logo']['url'] != '') {
    $logo = $teo_data['logo']['url'];
}
else {
    $logo = get_template_directory_uri() . '/img/logo-black-small.png';
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
<header class="grey type-1 <?php if(isset($teo_data['header-bg-color']) && $teo_data['header-bg-color'] != '') echo ' style="background-color: ' . $teo_data['header-bg-color'] . '"';?>">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="content">
                    <button type="button" class="menu-icon open-side-menu">
                        <span class="icon-bar"></span>
                        <span class="icon-bar middle"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <figure>
                        <a href="<?php echo home_url();?>">
                            <img src="<?php echo esc_url($logo);?>" alt="<?php bloginfo('name');?>" />
                        </a>
                    </figure>
                    <a href="<?php echo home_url();?>" class="logo-text"><?php bloginfo('name');?></a>
                    <div class="logo-extra"><?php bloginfo('description');?></div>
                    <?php get_template_part('header', 'social');?>
                </div>
            </div>
        </div>
    </div>
</header>