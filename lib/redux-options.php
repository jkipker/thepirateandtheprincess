<?php

/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */

if (!class_exists('Redux_Framework_sample_config')) {

    class Redux_Framework_sample_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if (  true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 3);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css, $changed_values) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r($changed_values); // Values that have changed since the last save
            echo "</pre>";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'redux-framework-demo'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns        = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'redux-framework-demo'), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                <?php endif; ?>

                <h4><?php echo $this->theme->display('Name'); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'redux-framework-demo'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'redux-framework-demo'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'redux-framework-demo') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'redux-framework-demo'), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }

            // ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'title'     => __('General Settings', 'redux-framework-demo'),
                'desc'      => __('Hello there! If you like the theme, please support us by rating the theme 5 stars on the Downloads section from <a href="http://themeforest.net/downloads">ThemeForest(click)</a>', 'redux-framework-demo'),
                'icon'      => 'el-icon-cogs',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'        => 'logo',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Dark Logo(default - used on header 1 and 2)', 'redux-framework-demo'),
                        //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc'      => __('The logo that shows up on the headers 1-2(a dark logo is highly recommended).', 'redux-framework-demo'),
                        'subtitle'  => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
                        'default'   => array('url' => get_template_directory_uri() . '/img/logo-black-small.png'),
                    ),
                    array(
                        'id'        => 'logo2',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('White Logo(used on header 3-6)', 'redux-framework-demo'),
                        //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc'      => __('The logo that shows up on the headers 3-6(if you don\'t set it, it will use the dark one.', 'redux-framework-demo'),
                        'subtitle'  => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
                        'default'   => array('url' => get_template_directory_uri() . '/img/logo-white-small.png'),
                    ),
                    array(
                        'id'        => 'favicon',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Favicon', 'redux-framework-demo'),
                        //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc'      => __('This is the little icon that shows up attached to your title in the browser.', 'redux-framework-demo'),
                        'subtitle'  => __('Upload any media using the WordPress native uploader(recommended .ico file format)', 'redux-framework-demo'),
                        'default'   => array(),
                    ),
                    array(
                        'id'        => 'header-type',
                        'type'      => 'radio',
                        'title'     => __('Header variation on category/author/tag and other archive pages.', 'redux-framework-demo'),
                        'desc'      => __('Headers can be seen at this url <a href="http://teothemes.com/html/Arwyn/headers.html">Click here</a>. This header variation will be used on all the category/author/tag/archive/etc pages, excepting the single post pages which will be managed through the metaboxes from the single posts individually and also except the homepage with the full screen slider.', 'redux-framework-demo'),
                        
                        //Must provide key => value pairs for select options
                        'options'   => array(
                            '1' => 'Variation 1(<a target="_blank" href="http://teothemes.com/wp/arwyn/wp-content/themes/Arwyn/img/variation1.png">click to see</a>) ', 
                            '2' => 'Variation 2(<a target="_blank" href="http://teothemes.com/wp/arwyn/wp-content/themes/Arwyn/img/variation2.png">click to see</a>) ', 
                            '3' => 'Variation 3(<a target="_blank" href="http://teothemes.com/wp/arwyn/wp-content/themes/Arwyn/img/variation3.png">click to see</a>) ', 
                            '4' => 'Variation 4(<a target="_blank" href="http://teothemes.com/wp/arwyn/wp-content/themes/Arwyn/img/variation4.png">click to see</a>) ', 
                            '5' => 'Variation 5(<a target="_blank" href="http://teothemes.com/wp/arwyn/wp-content/themes/Arwyn/img/variation5.png">click to see</a>) ', 
                            '6' => 'Variation 6(<a target="_blank" href="http://teothemes.com/wp/arwyn/wp-content/themes/Arwyn/img/variation6.png">click to see</a>) ', 
                        ),
                        'default'   => '1'
                    ),
                    array(
                        'id'        => 'header-bg-color',
                        'type'      => 'color',
                        'title'     => __('Background color used on some header variations', 'redux-framework-demo'),
                        'subtitle'  => __('Pick a background color for header variations 1 or 2(if used).', 'redux-framework-demo'),
                        'default'   => '#E3E3E3',
                        'validate'  => 'color',
                    ),
                    array(
                        'id'        => 'header-bg-image',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Background image used on some header variations', 'redux-framework-demo'),
                        //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc'      => __('Used on the header variations 3, 4, 5 and 6.', 'redux-framework-demo'),
                        'default'   => array('url' => get_template_directory_uri() . '/content/header-small.jpg'),
                    ),
                ),
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-cog',
                'title'     => __('Single post functionality', 'redux-framework-demo'),
                'desc'      => 'Functionality for single posts',
                'fields'    => array(
                    array(
                        'id'        => 'excerpt_type',
                        'type'      => 'select',
                        'title'     => __('Excerpt style, on archive/category pages', 'redux-framework-demo'),
                        'subtitle'  => __('The type of excerpt shown on blog / archive / category / index / tag pages for single posts.', 'redux-framework-demo'),
                        'options'   => array('1' => 'First 70 words from the post content', '2' => 'Post excerpt'),
                        'default'   => '1',
                    ),
                    array(
                        'id' => 'show_postauthor',
                        'type' => 'switch',
                        'title' => __('Show post author in the header?', 'redux-framework-demo'),
                        'desc' => __('If you don\'t want to show the post author in the header, below the title, disable it here!', 'redux-framework-demo'),
                        "default" => 1,
                        'on' => 'Enabled',
                        'off' => 'Disabled',
                    ),
                    array(
                        'id' => 'show_publishdate',
                        'type' => 'switch',
                        'title' => __('Show the publish date in the header?', 'redux-framework-demo'),
                        'desc' => __('If you don\'t want to show the publish date in the header, below the title, disable it here!', 'redux-framework-demo'),
                        "default" => 1,
                        'on' => 'Enabled',
                        'off' => 'Disabled',
                    ),
                    array(
                        'id' => 'show_categories',
                        'type' => 'switch',
                        'title' => __('Show categories in the header?', 'redux-framework-demo'),
                        'desc' => __('If you don\'t want to show the categories in the header, below the title, disable it here!', 'redux-framework-demo'),
                        "default" => 1,
                        'on' => 'Enabled',
                        'off' => 'Disabled',
                    ),
                    array(
                        'id' => 'show_share',
                        'type' => 'switch',
                        'title' => __('Show share options?', 'redux-framework-demo'),
                        'desc' => __('If you don\'t want to show the share options, disable it here!', 'redux-framework-demo'),
                        "default" => 1,
                        'on' => 'Enabled',
                        'off' => 'Disabled',
                    ),
                    array(
                        'id' => 'show_related',
                        'type' => 'switch',
                        'title' => __('Show related posts?', 'redux-framework-demo'),
                        'desc' => __('If you don\'t want to show related posts, disable it here!', 'redux-framework-demo'),
                        "default" => 1,
                        'on' => 'Enabled',
                        'off' => 'Disabled',
                    ),
                    array(
                        'id' => 'show_authorbox',
                        'type' => 'switch',
                        'title' => __('Show about author box?', 'redux-framework-demo'),
                        'desc' => __('If you don\'t want to show the box with the author info, disable it here!', 'redux-framework-demo'),
                        "default" => 1,
                        'on' => 'Enabled',
                        'off' => 'Disabled',
                    ),
                    array(
                        'id' => 'show_navigationposts',
                        'type' => 'switch',
                        'title' => __('Show next / previous links?', 'redux-framework-demo'),
                        'desc' => __('If you don\'t want to show the next / previous navigation links, disable them here!', 'redux-framework-demo'),
                        "default" => 1,
                        'on' => 'Enabled',
                        'off' => 'Disabled',
                    ),
                )
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-group',
                'title'     => __('Social Icons', 'redux-framework-demo'),
                'desc'      => 'The social icons show up on the header',
                'fields'    => array(
                    array(
                        'id'        => 'twitter_url',
                        'type'      => 'text',
                        'title'     => __('Twitter social URL', 'redux-framework-demo'),
                        'subtitle'  => __('The link to your twitter profile. Make sure it starts with http://', 'redux-framework-demo'),
                        'default'   => ''
                    ),
                    array(
                        'id'        => 'facebook_url',
                        'type'      => 'text',
                        'title'     => __('Facebook social URL', 'redux-framework-demo'),
                        'subtitle'  => __('The link to your facebook profile. Make sure it starts with http://', 'redux-framework-demo'),
                        'default'   => ''
                    ),
                    array(
                        'id'        => 'instagram_url',
                        'type'      => 'text',
                        'title'     => __('Instagram social URL', 'redux-framework-demo'),
                        'subtitle'  => __('The link to your instagram profile. Make sure it starts with http://', 'redux-framework-demo'),
                        'default'   => ''
                    ),
                    array(
                        'id'        => 'flickr_url',
                        'type'      => 'text',
                        'title'     => __('Flickr social URL', 'redux-framework-demo'),
                        'subtitle'  => __('The link to your flickr profile. Make sure it starts with http://', 'redux-framework-demo'),
                        'default'   => ''
                    ),
                    array(
                        'id'        => 'dribbble_url',
                        'type'      => 'text',
                        'title'     => __('Dribbble social URL', 'redux-framework-demo'),
                        'subtitle'  => __('The link to your dribbble profile. Make sure it starts with http://', 'redux-framework-demo'),
                        'default'   => ''
                    ),
                    array(
                        'id'        => 'googleplus_url',
                        'type'      => 'text',
                        'title'     => __('GooglePlus social URL', 'redux-framework-demo'),
                        'subtitle'  => __('The link to your google-plus profile. Make sure it starts with http://', 'redux-framework-demo'),
                        'default'   => ''
                    ),

                    array(
                        'id'        => 'pinterest_url',
                        'type'      => 'text',
                        'title'     => __('Pinterest social URL', 'redux-framework-demo'),
                        'subtitle'  => __('The link to your pinterest profile. Make sure it starts with http://', 'redux-framework-demo'),
                        'default'   => ''
                    ),
                    array(
                        'id'        => 'linkedin_url',
                        'type'      => 'text',
                        'title'     => __('LinkedIn social URL', 'redux-framework-demo'),
                        'subtitle'  => __('The link to your LinkedIn profile. Make sure it starts with http://', 'redux-framework-demo'),
                        'default'   => ''
                    ),

                    array(
                        'id'        => 'youtube_url',
                        'type'      => 'text',
                        'title'     => __('Youtube social URL', 'redux-framework-demo'),
                        'subtitle'  => __('The link to your Youtube profile. Make sure it starts with http://', 'redux-framework-demo'),
                        'default'   => ''
                    ),

                    array(
                        'id'        => 'skype_url',
                        'type'      => 'text',
                        'title'     => __('Skype username', 'redux-framework-demo'),
                        'subtitle'  => __('The skype username, in case you want to let your visitors contact you', 'redux-framework-demo'),
                        'default'   => ''
                    ),
                )
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-scissors',
                'title'     => __('Customization', 'redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'overlay-color',
                        'type'      => 'color',
                        'title'     => __('Overlay color(darkens the images)', 'redux-framework-demo'),
                        'subtitle'  => __('Pick a background color for the overlay that shows up over all the images.', 'redux-framework-demo'),
                        'default'   => '#000',
                        'validate'  => 'color',
                    ),
                    array(
                        'id'        => 'overlay-percentage',
                        'type'      => 'text',
                        'title'     => __('Overlay percentage(how much to darken the images? default 25%)', 'redux-framework-demo'),
                        'subtitle'  => __('Use just 0-100 values, in percentages. 0% means no overlay, the image will stay as the original, while 100 means the image will be fully hidden by the overlay.', 'redux-framework-demo'),
                        'default' => '25'
                    ),
                    array(
                        'id'        => 'tracking-code',
                        'type'      => 'textarea',
                        'title'     => __('Tracking Code', 'redux-framework-demo'),
                        'subtitle'  => __('Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'custom-css',
                        'type'      => 'ace_editor',
                        'title'     => __('CSS Code', 'redux-framework-demo'),
                        'subtitle'  => __('Paste your customization CSS code here.', 'redux-framework-demo'),
                        'mode'      => 'css',
                        'theme'     => 'monokai',
                        'default'   => ""
                    ),
                    array(
                        'id'            => 'menu-font',
                        'type'          => 'typography',
                        'title'         => __('Typography for menu items', 'redux-framework-demo'),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => true,    // Select a backup non-google font in addition to a google font
                        'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'       => true, // Only appears if google is true and subsets not set to false
                        //'font-size'     => false,
                        'line-height'   => false,
                        'color'         => false,
                        'all_styles'    => false,    // Enable all Google Font style/weight variations to be added to the page
                        'output'        => array('.st-menu ul, .st-menu ul a, header .menu a'), // An array of CSS selectors to apply this font style to dynamically
                        'units'         => 'px', // Defaults to px
                        'subtitle'      => __('Typography module for the left menu.', 'redux-framework-demo'),
                        'default'       => array(
                            'font-family'   => 'Oswald',
                            'google'        => true,
                            'font-size'     => '12px'),
                    ),
                    array(
                        'id'            => 'titles-font',
                        'type'          => 'typography',
                        'title'         => __('Typography for titles', 'redux-framework-demo'),
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => true,    // Select a backup non-google font in addition to a google font
                        'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'       => true, // Only appears if google is true and subsets not set to false
                        'font-size'     => false,
                        'line-height'   => false,
                        'color'         => false,
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        'output'        => array('.homepage-articles article h2, .normal-slider article h2, .full-homepage .main-slider article h2, .full-homepage .sidebar-slider article h2, .homepage-post figure h3, .homepage-post figure h5, .related-posts .post-title, .post-section, .post-section h3, .category-section h1, header.image-header .content h1, header.image-header .content h4, .widget .title, .widget li .post-title, .widget li, .widget li a, .widget li p'), // An array of CSS selectors to apply this font style to dynamically
                        //'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'         => 'px', // Defaults to px
                        'subtitle'      => __('Typography module for the post / widget / page titles.', 'redux-framework-demo'),
                        'default'       => array(
                            'font-family'   => 'Oswald',
                            'google'        => true),
                    ),
                    array(
                        'id'            => 'content-font',
                        'type'          => 'typography',
                        'title'         => __('Typography for post content / normal text', 'redux-framework-demo'),
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => true,    // Select a backup non-google font in addition to a google font
                        'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'       => true, // Only appears if google is true and subsets not set to false
                        'font-size'     => false,
                        'line-height'   => false,
                        'color'         => false,
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        'output'        => array('.text-editor p, .author-single .contact, .author-single .content, .commentslist .contact, .commentslist .content, form input.form-control, form textarea, header .logo-extra, .author-section .contact, .author-section .contact a, .author-section .content, .widget.widget_teoaboutauthor .content, .textwidget, .text-editor'), // An array of CSS selectors to apply this font style to dynamically
                        //'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'         => 'px', // Defaults to px
                        'subtitle'      => __('Typography module for the post content / normal text.', 'redux-framework-demo'),
                        'default'       => array(
                            'font-family'   => 'Vollkorn',
                            'google'        => true),
                    ),
                    array(
                        'id'            => 'slider-content-font',
                        'type'          => 'typography',
                        'title'         => __('Typography for the content in the sliders', 'redux-framework-demo'),
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => true,    // Select a backup non-google font in addition to a google font
                        'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'       => true, // Only appears if google is true and subsets not set to false
                        'font-size'     => false,
                        'line-height'   => false,
                        'color'         => false,
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        'output'        => array('.normal-slider-wrap .slider-controls .counter, .normal-slider article p, .normal-slider article .article-link, .full-homepage .right-section .slider-controls .counter, .full-homepage .main-slider article p, .full-homepage .main-slider article .article-link, .full-homepage .sidebar-slider article .article-link'), // An array of CSS selectors to apply this font style to dynamically
                        //'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'         => 'px', // Defaults to px
                        'subtitle'      => __('Typography module for the content in the sliders.', 'redux-framework-demo'),
                        'default'       => array(
                            'font-family'   => 'Lora',
                            'google'        => true),
                    ),
                    array(
                        'id'        => 'opt-hover-color',
                        'type'      => 'color',
                        'output'    => array('.st-menu ul li.active > a, .st-menu ul li.selected > a, .st-menu ul a:hover, .normal-slider-wrap .slider-controls button:hover, .full-homepage .right-section .slider-controls button:hover, .text-editor a, .textwidget a, .author-single .content a, .post-header .navigation a:hover, .post-header .navigation a:hover .text, header .menu a:hover, .widget li a:hover, .widget_calendar a, .author-single .name:hover, .post-share a:hover, .post-inside .tags a:hover, .related-posts .post-title:hover'),
                        'title'     => __('Secondary color(used on hovers, links, buttons)', 'redux-framework-demo'),
                        'subtitle'  => __('Pick a background color for the secondary color (default: #D7A25B).', 'redux-framework-demo'),
                        'default'   => '#D7A25B',
                        'validate'  => 'color',
                    ),
                )
            );

            $theme_info  = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __('<strong>Theme URL:</strong> ', 'redux-framework-demo') . '<a href="' . $this->theme->get('ThemeURI') . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __('<strong>Author:</strong> ', 'redux-framework-demo') . $this->theme->get('Author') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __('<strong>Version:</strong> ', 'redux-framework-demo') . $this->theme->get('Version') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __('<strong>Tags:</strong> ', 'redux-framework-demo') . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';

            if (file_exists(dirname(__FILE__) . '/../README.md')) {
                $this->sections['theme_docs'] = array(
                    'icon'      => 'el-icon-list-alt',
                    'title'     => __('Documentation', 'redux-framework-demo'),
                    'fields'    => array(
                        array(
                            'id'        => '17',
                            'type'      => 'raw',
                            'markdown'  => true,
                            'content'   => file_get_contents(dirname(__FILE__) . '/../README.md')
                        ),
                    ),
                );
            }
            
            $this->sections[] = array(
                'title'     => __('Import / Export', 'redux-framework-demo'),
                'desc'      => __('Import and Export your Redux Framework settings from file, text or URL.', 'redux-framework-demo'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your Redux options',
                        'full_width'    => false,
                    ),
                ),
            );                     
                    
            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-info-sign',
                'title'     => __('Theme Information', 'redux-framework-demo'),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-raw-info',
                        'type'      => 'raw',
                        'content'   => $item_info,
                    )
                ),
            );

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon'      => 'el-icon-book',
                    'title'     => __('Documentation', 'redux-framework-demo'),
                    'content'   => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'redux-framework-demo'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'redux-framework-demo'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'teo_data',            // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => $theme->get('Name'),     // Name that appears at the top of your panel
                'display_version'   => $theme->get('Version'),  // Version that appears at the top of your panel
                'menu_type'         => 'menu',                  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => true,                    // Show the sections below the admin menu item or not
                'menu_title'        => __('Arwyn Options', 'redux-framework-demo'),
                'page_title'        => __('Arwyn Options', 'redux-framework-demo'),
                
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => 'AIzaSyDr8mDDTUxXh6ub07jMGKYpPmR-xXLAbkY', // Must be defined to add google fonts to the typography module
                
                'async_typography'  => false,                    // Use a asynchronous font on the front end or font string
                'admin_bar'         => true,                    // Show the panel pages on the admin bar
                'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => true,                    // Show the time the page took to load, etc
                'customizer'        => true,                    // Enable basic customizer support
                //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.


                // OPTIONAL -> Give you extra features
                'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'themes.php',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => '',                      // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'icon-themes',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => '_options',              // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true,                   // Shows the Import/Export panel when not used as a field.
                
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
                
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE

                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );

            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace('-', '_', $this->args['opt_name']);
                }
                $this->args['intro_text'] = sprintf(__('<p>Please rate the theme 5* on ThemeForest <a href="http://themeforest.net/downloads">Click here</a></p>', 'redux-framework-demo'), $v);
            } else {
                $this->args['intro_text'] = __('<p>Please rate the theme 5* on ThemeForest <a href="http://themeforest.net/downloads">Click here</a></p>', 'redux-framework-demo');
            }

            // Add content after the form.
            $this->args['footer_text'] = '';
        }

    }
    
    global $reduxConfig;
    $reduxConfig = new Redux_Framework_sample_config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('redux_my_custom_field')):
    function redux_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('redux_validate_callback_function')):
    function redux_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;
