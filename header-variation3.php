<?php
global $teo_data;
if(isset($teo_data['logo']) && $teo_data['logo']['url'] != '') {
    $logo = $teo_data['logo']['url'];
}
else {
    $logo = get_template_directory_uri() . '/img/logo-white-small.png';
}

if(isset($teo_data['header-bg-image']) && $teo_data['header-bg-image'] != '') {
    $bg_image = esc_url($teo_data['header-bg-image']['url']);
}
else {
    $bg_image = get_template_directory_uri() . '/content/header-small.jpg';
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
<header style="background-image: url('<?php echo $bg_image;?>')" class="image type-1">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="content">
                    <!-- <button type="button" class="menu-icon open-side-menu">
                        <div class="pp-menu-title">MENU</div>
                        <span class="icon-bar"></span>
                        <span class="icon-bar middle"></span>
                        <span class="icon-bar"></span>
                    </button> -->
                    <figure>
                        <a href="<?php echo home_url();?>">
                            <img src="<?php echo esc_url($logo);?>" alt="<?php bloginfo('name');?>" />
                        </a>
                    </figure>
                    <?php wp_nav_menu(array(
                        'theme_location' => 'top-menu',
                        'container' => '',
                        'echo' => true,
                        'depth' => 1 ) 
                    );
                    ?>
                    
                    <a href="<?php echo home_url();?>" class="logo-text"><?php bloginfo('name');?></a>
                    <div class="logo-extra"><?php bloginfo('description');?></div>
                    <?php get_template_part('header', 'social');?>
                </div>
            </div>
        </div>
    </div>
</header>