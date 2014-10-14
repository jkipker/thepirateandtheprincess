<form action="<?php echo home_url( '/' ); ?>" id="searchform" method="get">
    <label for="s" class="screen-reader-text"><?php _e('Search for:', 'arwyn');?></label>
    <input class="form-control" type="search" id="s" name="s" required />
    <input type="submit" id="searchsubmit" id="contact_submit" class="btn btn-default" value="<?php _e('Search', 'arwyn');?>" />
</form>