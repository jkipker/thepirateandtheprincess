<?php
global $teo_data;
?>
<ul class="socials">
    <?php if(isset($teo_data['facebook_url']) && $teo_data['facebook_url'] != '') { ?>
    	<li><a target="_blank" rel="nofollow" href="<?php echo esc_url($teo_data['facebook_url']);?>" class="symbol facebook">facebook</a></li>
    <?php } ?>

    <?php if(isset($teo_data['youtube_url']) && $teo_data['youtube_url'] != '') { ?>
    	<li><a target="_blank" rel="nofollow" href="<?php echo esc_url($teo_data['youtube_url']);?>" class="symbol youtube">youtube</a></li>
    <?php } ?>

    <?php if(isset($teo_data['twitter_url']) && $teo_data['twitter_url'] != '') { ?>
    	<li><a target="_blank" rel="nofollow" href="<?php echo esc_url($teo_data['twitter_url']);?>" class="symbol twitter">twitterbird</a></li>
    <?php } ?>

    <?php if(isset($teo_data['googleplus_url']) && $teo_data['googleplus_url'] != '') { ?>
    	<li><a target="_blank" rel="nofollow" href="<?php echo esc_url($teo_data['googleplus_url']);?>" class="symbol googleplus">googleplus</a></li>
    <?php } ?>

    <?php if(isset($teo_data['dribbble_url']) && $teo_data['dribbble_url'] != '') { ?>
    	<li><a target="_blank" rel="nofollow" href="<?php echo esc_url($teo_data['dribbble_url']);?>" class="symbol dribbble">dribble</a></li>
    <?php } ?>

    <?php if(isset($teo_data['instagram_url']) && $teo_data['instagram_url'] != '') { ?>
    	<li><a target="_blank" rel="nofollow" href="<?php echo esc_url($teo_data['instagram_url']);?>" class="symbol instagram">instagram</a></li>
    <?php } ?>

    <?php if(isset($teo_data['flickr_url']) && $teo_data['flickr_url'] != '') { ?>
    	<li><a target="_blank" rel="nofollow" href="<?php echo esc_url($teo_data['flickr_url']);?>" class="symbol flickr">flickr</a></li>
    <?php } ?>

    <?php if(isset($teo_data['pinterest_url']) && $teo_data['pinterest_url'] != '') { ?>
    	<li><a target="_blank" rel="nofollow" href="<?php echo esc_url($teo_data['pinterest_url']);?>" class="symbol pinterest">pinterest</a></li>
    <?php } ?>

    <?php if(isset($teo_data['linkedin_url']) && $teo_data['linkedin_url'] != '') { ?>
    	<li><a target="_blank" rel="nofollow" href="<?php echo esc_url($teo_data['linkedin_url']);?>" class="symbol linkedin">linkedin</a></li>
    <?php } ?>

    <?php if(isset($teo_data['skype_url']) && $teo_data['skype_url'] != '') { ?>
    	<li><a target="_blank" rel="nofollow" href="skype:<?php echo $teo_data['skype_url'];?>" class="symbol skype">skype</a></li>
    <?php } ?>
</ul>