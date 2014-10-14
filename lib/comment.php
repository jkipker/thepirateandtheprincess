<?php if ( ! function_exists( 'teo_comment' ) ) :
function teo_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    global $comment_count;
   ?>

   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
        <div id="comment-<?php comment_ID(); ?>">
            <figure>
                <?php echo get_avatar($comment, 80); ?>
            </figure>
            <a <?php if($comment->comment_author_url != '') echo 'href="' . esc_url($comment->comment_author_url) . '"';?> class="name"><?php comment_author();?></a>
            <div class="contact"><?php echo esc_url($comment->comment_author_url); ?></div>
            <div class="content">
                <?php comment_text(); ?>
                <?php if ($comment->comment_approved == '0') : ?>
                    <p>
                        <em class="moderation"><?php esc_html_e('Your comment is awaiting moderation.','Cleanse') ?></em>
                    </p>
                    <br />
                <?php endif; ?>
            </div>
            <div class="reply">
                <div class="time-ago"><?php echo (get_comment_date('F jS, Y G:i'));?></div>
                <?php
                $reply_link = get_comment_reply_link( array_merge( $args, array('reply_text' => __('Reply', 'arwyn') . '<span class="no-font">&ndash;</span>','depth' => $depth, 'max_depth' => $args['max_depth'])) );
                if($reply_link) { 
                    echo $reply_link;
                }
                edit_comment_link( __('Edit', 'arwyn', ' ' ) . '<span class="no-font">&ndash;</span>' ); ?>
            </div>
            <div class="comment-no"><?php if($comment_count < 10) echo '0'; echo $comment_count; $comment_count++; ?></div>
        </div>
<?php }
endif; ?>