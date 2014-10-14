<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (esc_attr_e('Please do not load this page directly. Thanks!','arwyn'));

	if ( post_password_required() ) { ?>

<p class="nocomments"><?php esc_attr_e('This post is password protected. Enter the password to view comments.','arwyn') ?></p>
<?php
		return;
	}
	global $redux_demo;
?>
<!-- You can start editing here. -->

<div id="comments" class="comments">

	<?php if ( have_comments() ) : ?>

		<div class="post-sidebar-padding">
			<div class="post-section">
				<i class="icon-comment"></i>
				<?php printf( _n( '1 comment', '%1$s comments', get_comments_number(), 'arwyn' ), number_format_i18n(get_comments_number() ), get_the_title() ); ?>
			</div>
		</div>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="paginate comment-paginate clearfix">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'arwyn' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'arwyn' ) ); ?></div>
			</div> <!-- .navigation -->
		<?php endif; // check for comment navigation ?>
			
		<?php if ( ! empty($comments_by_type['comment']) ) : ?>
			<ul class="commentslist">
				<?php global $comment_count; $comment_count = 1; wp_list_comments( array('type'=>'comment','callback'=>'teo_comment') ); ?>
			</ul>
		<?php endif; ?>
			
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="paginate comment-paginate clearfix">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'arwyn' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'arwyn' ) ); ?></div>
			</div> <!-- .navigation -->
		<?php endif; // check for comment navigation ?>
				
		<?php if ( ! empty($comments_by_type['pings']) ) : ?>
			<div id="trackbacks">
				<ul class="pinglist">
					<?php //wp_list_comments('type=pings&callback=tilability_pings'); ?>
				</ul>
			</div>
		<?php endif; ?>	

	<?php else : // this is displayed if there are no comments so far ?>
	    <div class="post-sidebar-padding no-comments">
			<div class="post-section">
				<i class="icon-comment"></i>0 <?php _e('comments', 'arwyn');?>
			</div>
		</div>
	<?php endif; ?>


	<?php if ( ! comments_open() ) : ?>
		<div class="post-inside">
			<p class="no-comments"><?php _e( 'Comments are closed.', 'arwyn' ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ('open' == $post->comment_status) : ?>
		<div class="post-sidebar-padding">
			<div class="post-section">
	            <?php _e('Leave a reply', 'arwyn');?>
			</div>
		</div>
			
		<?php 
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		if($commenter['comment_author'] != '') 
			$name = esc_attr($commenter['comment_author']);
		else 
			$name = '';
		if($commenter['comment_author_email'] != '') 
			$email = esc_attr($commenter['comment_author_email']);
		else
			$email = '';
		if($commenter['comment_author_url'] != '') 
			$url = esc_attr($commenter['comment_author_url']);
		else 
			$url = '';
		$fields =  array(
		'author' => '<div class="form-group">
	            <label for="name">' . __('Name', 'arwyn') . ':</label>
	            <input class="form-control" id="author" placeholder="John Doe(*)" name="author" type="text" value="' . $name . '" ' . $aria_req . ' />
	        </div>',
		'email'  => '<div class="form-group">
	            <label for="name">' . __('E-mail', 'arwyn') . ':</label>
				<input class="form-control" id="email" placeholder="john@doe.com(*)" name="email" type="text" value="' . $email . '" ' . $aria_req . ' />
			</div>',
		'url'    => '<div class="form-group">
	            <label for="name">' . __('Name', 'arwyn') . ':</label>
				<input class="form-control" id="url" placeholder="http://example.com" name="url" type="text" value="' . $url . '" />
			</div>'
		); 

		$comment_textarea = '<div class="form-group">
	            <label for="message">Message:</label>
	            <textarea placeholder="Your Message...(*)" id="comment" name="comment" aria-required="true"></textarea>
	        </div>';
		comment_form( array( 'fields' => $fields, 'comment_field' => $comment_textarea, 'id_submit' => 'contact_submit', 'label_submit' => esc_attr__( 'Submit Comment', 'arwyn' ), 'title_reply' => '<div class="post-section">' . esc_attr__( 'Leave a Reply', 'arwyn' ) . '</div>', 'title_reply_to' => '<div class="post-section">' . esc_attr__( 'Leave a Reply to %s', 'arwyn' ) . '</div>') ); ?>		
	<?php endif; //comment status if ?>

</div>