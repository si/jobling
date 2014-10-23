<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

			<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'designcrumbs') ?>.</p>

			<?php
			return;
		}
	}
?>

<?php if ( have_comments() ) : //if comments open and there are comments to show, display this ?>

	<?php if ( ! empty($comments_by_type['comment']) ) : ?>

		<h3 id="comments"><?php comments_number();?> on "<?php the_title(); ?>"</h3>
		<div id="comments_wrap"> 
			<ul class="commentlist"><!-- display omments -->
				<?php wp_list_comments('callback=custom_comment&type=comment'); //'custom_comment' are edited in [functions.php] ?>
			</ul>
		</div>

        <div class="navigation comment-nav">
	        <div class="nav-prev"><?php previous_comments_link() ?></div>
    	    <div class="nav-next"><?php next_comments_link() ?></div>
        </div>

	<?php endif; ?>

	<?php if ( ! empty($comments_by_type['pings']) ) : ?>
		<h3 id="pings"><?php _e('Trackbacks', 'designcrumbs') ?></h3>
		<div id="comments_wrap">
			<ul class="pinglist"><!-- display trackbacks -->
				<?php wp_list_comments('callback=custom_pings&type=pings'); ?>
			</ul>
		</div>
		
		<div class="navigation comment-nav"> 
			<div class="nav-prev"><?php previous_comments_link() ?></div>
			<div class="nav-next"><?php next_comments_link() ?></div>
		</div>

	<?php endif; ?>

<?php else : ?>
	
	<?php if ('open' == $post->comment_status) : 
		// Display something if there are no comments yet?
	else : ?>
		<h5 id="comments_closed"><?php _e('Comments are closed.', 'designcrumbs'); ?></h5>
	<?php endif; ?>

<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<div id="respond">

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>

<p><?php _e('You must be', 'designcrumbs'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e('logged in', 'designcrumbs'); ?></a> <?php _e('to post a comment', 'designcrumbs'); ?>.</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<h3><?php _e('Leave Your Comment', 'designcrumbs'); ?></h3>
<?php if ( $user_ID ) : ?>

<p><?php _e('Logged in as', 'designcrumbs'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out', 'designcrumbs'); ?>"><?php _e('Log out', 'designcrumbs'); ?> &raquo;</a></p>

<?php else : ?>
                             
<p>
<label for="author"><?php _e('Name', 'designcrumbs' ) ?> <?php if ($req) echo __('(required)', 'designcrumbs'); ?></label>
<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" class="text<?php if ($req) echo ' required'; ?>" />
</p>

<p>
<label for="email"><?php _e('Email', 'designcrumbs' ) ?> <?php if ($req) echo __('(required - never shared)', 'designcrumbs'); ?></label>
<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" class="text<?php if ($req) echo ' required'; ?>" />
</p>

<p>
<label for="url"><?php _e('Website', 'designcrumbs' ) ?></label>
<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
</p>

<?php endif; ?>

<div>
<?php comment_id_fields(); ?>
<input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" />
</div>

<p>
<label for="url"><?php _e('Comment', 'designcrumbs' ) ?></label>
<textarea name="comment" id="comment" cols="50" rows="10" tabindex="4"></textarea></p>
<div>
<input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Send', 'designcrumbs') ?>" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
<div id="cancel-comment-reply"><small><?php cancel_comment_reply_link( __('Cancel reply', 'designcrumbs')) ?></small></div>
<div class="clear"></div>
</div>
<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>

</div><!-- end #respond -->

<?php endif; // if you delete this the sky will fall on your head ?>
