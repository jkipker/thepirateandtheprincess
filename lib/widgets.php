<?php
// TeoAboutAuthor Widget
class TeoAboutAuthor extends WP_Widget
{
    function TeoAboutAuthor(){
    $widget_ops = array('description' => 'Shows info about one author');
    $control_ops = array('width' => 200, 'height' => 300);
    parent::__construct(false,$name='[Arwyn] Author Widget.',$widget_ops,$control_ops);
    }

  /* Displays the Widget in the front-end */
    function widget($args, $instance){
    extract($args);

    $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
    $author = $instance['author'];

    echo $before_widget; 

    if ( $title != '' )
        echo $before_title . $title . $after_title;

    echo '<figure>' . get_avatar($author, 350) . '</figure>';

    echo '<a class="name">' . get_the_author_meta('user_nicename', $author) . '</a>';

    echo '<div class="content">' . get_the_author_meta('description', $author) . '</div>';


    echo $after_widget;
  }

  /*Saves the settings. */
    function update($new_instance, $old_instance){
    $instance =  array();
    $instance['author'] = $new_instance['author'];

    return $instance;
  }

  /*Creates the form for the widget in the back-end. */
    function form($instance){
    //Defaults
    $instance = wp_parse_args( (array) $instance, array('title'=> '', 'author' => 0 ) );

    $title = esc_attr($instance['title']);
    $author = (int) $instance['author'];
    
    echo '<p><label for="' . $this->get_field_id('title') . '">' . 'Title: ' . '</label><br /><input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="'. esc_textarea($title)  . '" /></p>';
    $authors = get_users('hide_empty=0');
    ?>
    <p>
        <label>Author to show: </label> <br />
        <select class="widefat" name="<?php echo $this->get_field_name('author'); ?>">
        <?php foreach( $authors as $auth ) { ?>
                <option value="<?php echo $auth->ID; ?>"  <?php if($auth->ID == $author ) echo 'selected="selected"';?>><?php echo $auth->display_name;?></option>
        <?php } ?>
        </select>
    </p> 
    <?php
  }

}// end TeoAboutAuthor class

// TeoLatestPosts Widget
class TeoLatestPosts extends WP_Widget
{
    function TeoLatestPosts(){
    $widget_ops = array('description' => 'Shows the latest posts with the image attached.');
    $control_ops = array('width' => 200, 'height' => 300);
    parent::__construct(false,$name='[Arwyn] Latest Posts',$widget_ops,$control_ops);
    }

  /* Displays the Widget in the front-end */
    function widget($args, $instance){
    extract($args);

    $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
    $number_posts = $instance['number_posts'];
    $categories = $instance['categories'];

    echo $before_widget; 

    if ( $title != '' )
        echo $before_title . $title . $after_title;

    echo '<ul>';
    $args = array();
    $args['post_type'] = 'post';
    if(!empty($categories) )
        $args['category__in'] = $categories;
    $args['posts_per_page'] = $number_posts != 0 ? $number_posts : 4;
    $query = new WP_Query($args);
    while($query->have_posts() ) : $query->the_post(); global $post; ?>
        <li>
            <?php 
            if(has_post_thumbnail() ) {
                $image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
                $thumb = teo_resize($image, 80, 80, true);
                ?>
                <a href="<?php the_permalink();?>" title="<?php the_title();?>">
                    <figure>
                        <img src="<?php echo $thumb; ?>" alt="<?php the_title();?>" />
                    </figure>
                </a>
            <?php } ?>
            <a class="post-title" href="<?php the_permalink();?>"><?php the_title();?></a>
            <div class="info">
                <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ' . __('ago', 'arwyn'); ?>
            </div>
        </li>
    <?php 
    endwhile; wp_reset_postdata(); 
    echo '</ul>';

    echo $after_widget;
  }

  /*Saves the settings. */
    function update($new_instance, $old_instance){
    $instance =  array();
    $instance['title'] = esc_attr($new_instance['title']);
    $instance['number_posts'] = (int)$new_instance['number_posts'];
    $instance['categories'] = $new_instance['categories'];

    return $instance;
  }

  /*Creates the form for the widget in the back-end. */
    function form($instance){
    //Defaults
    $instance = wp_parse_args( (array) $instance, array('number_posts'=> 4, 'title' => '', 'categories' => array() ) );

    $title = esc_attr($instance['title']);
    $number_posts = (int) $instance['number_posts'];
    $categories = (array) $instance['categories'];
    
    echo '<p><label for="' . $this->get_field_id('title') . '">' . 'Title: ' . '</label><input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="'. esc_textarea($title)  . '" /></p>';
    echo '<p><label for="' . $this->get_field_id('number_posts') . '">' . 'Number of posts: ' . '</label><input id="' . $this->get_field_id('number_posts') . '" name="' . $this->get_field_name('number_posts') . '" value="'. esc_textarea($number_posts)  . '" /></p>';
    $cats = get_categories('hide_empty=0');
    ?>
    <p>
        <label>Categories to include: </label> <br />
        <?php foreach( $cats as $category ) { ?>
            <label>
                <input type="checkbox" name="<?php echo $this->get_field_name('categories'); ?>[]" value="<?php echo $category->cat_ID; ?>"  <?php if(in_array( $category->cat_ID, $categories ) ) echo 'checked="checked" '; ?> /> 
                <?php echo $category->cat_name; ?>
            </label> <br />
        <?php } ?>
    </p> 
    <?php
  }

}// end TeoLatestPosts class

// TeoInstagram Widget
class TeoInstagram extends WP_Widget
{
    function TeoInstagram(){
    $widget_ops = array('description' => 'Shows photos from instagram.');
    $control_ops = array('width' => 200, 'height' => 300);
    parent::__construct(false,$name='[Arwyn] Instagram Widget',$widget_ops,$control_ops);
    }

  /* Displays the Widget in the front-end */
    function widget($args, $instance){
    extract($args);

    $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
    $userID = $instance['userID'];
    $accessToken = $instance['accessToken'];
    $number = (int)$instance['number'];

    if($number == 0) {
        $number = 6;
    }

    echo $before_widget; 

    if ( $title != '' )
        echo $before_title . $title . $after_title;

    echo '<div class="content">';

    $data = get_transient('ig_data' . $userID);
    if(!$data) {

        $url = 'https://api.instagram.com/v1/users/' . $userID . '/media/recent/?access_token=' . $accessToken . '&count=' . $number;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = curl_exec($ch);
        curl_close($ch); 

        set_transient( 'ig_data' . $userID , $result , 2);

        $data = $result;

    }

    $data = json_decode($data)->data;

    if(!empty($data)) {
        foreach ($data as $post){
            echo '<a href="' . $post->images->standard_resolution->url . '"><img src="' . $post->images->thumbnail->url . '" alt="' . $post->caption->text . '" />';
        }
    }

    echo '</div>';

     if(isset($data[0]) && $data[0]->user->username) {
         echo '<a class="btn btn-default btn-instagram" href="//instagram.com/' . $data[0]->user->username . '">' . sprintf(__('Follow %s', 'Arwyn'), $data[0]->user->username) . '</a>';
     }

    echo $after_widget;
  }

  /*Saves the settings. */
    function update($new_instance, $old_instance){
    $instance =  array();
    $instance['title'] = esc_attr($new_instance['title']);
    $instance['userID'] = $new_instance['userID'];
    $instance['accessToken'] = $new_instance['accessToken'];
    $instance['number'] = $new_instance['number'];

    return $instance;
  }

  /*Creates the form for the widget in the back-end. */
    function form($instance){
    //Defaults
    $instance = wp_parse_args( (array) $instance, array('userID'=> '', 'title' => '', 'accessToken' => '', 'number' => '') );

    $title = esc_attr($instance['title']);
    $userID = $instance['userID'];
    $accessToken = $instance['accessToken'];
    $number = $instance['number'];
    
    echo '<p><label for="' . $this->get_field_id('title') . '">' . 'Title: ' . '</label><input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="'. esc_textarea($title)  . '" /></p>';
    echo '<p>Generate your Instagram userID and access token on: <a target="_blank" href="http://www.pinceladasdaweb.com.br/instagram/access-token/">Instagram access token generator</a> website</p>';

    echo '<p><label for="' . $this->get_field_id('userID') . '">' . 'User ID: ' . '</label><input id="' . $this->get_field_id('userID') . '" name="' . $this->get_field_name('userID') . '" value="'. esc_textarea($userID)  . '" /></p>';
    echo '<p><label for="' . $this->get_field_id('accessToken') . '">' . 'Access Token: ' . '</label><input id="' . $this->get_field_id('accessToken') . '" name="' . $this->get_field_name('accessToken') . '" value="'. esc_textarea($accessToken)  . '" /></p>';
    echo '<p><label for="' . $this->get_field_id('number') . '">' . 'Number of images: ' . '</label><input id="' . $this->get_field_id('number') . '" name="' . $this->get_field_name('number') . '" value="'. esc_textarea($number)  . '" /></p>';
  }

}// end TeoInstagram class

function TeoWidgets() {
    register_widget('TeoAboutAuthor');
    register_widget('TeoLatestPosts');
    register_widget('TeoInstagram');
  
}

add_action('widgets_init', 'TeoWidgets');