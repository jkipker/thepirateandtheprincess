<?php
global $teo_data;
if(isset($teo_data['logo2']) && $teo_data['logo2']['url'] != '') {
    $logo = $teo_data['logo2']['url'];
}
else {
    $logo = get_template_directory_uri() . '/img/logo-white-small.png';
}

if(isset($teo_data['header-bg-image']) && $teo_data['header-bg-image'] != '') {
    $bg_image = $teo_data['header-bg-image']['url'];
}
else {
    $bg_image = get_template_directory_uri() . '/content/header-small.jpg';
}
?>
<header style="background-image: url('<?php echo $bg_image;?>')" class="image type-3">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="content">
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
                </div>
            </div>
        </div>
    </div>
</header>