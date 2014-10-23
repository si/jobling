<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (__('Please do not load this page directly. Thanks!'));

        if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
				?>

				<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.'); ?><p>

				<?php
				return;
            }
        }

if (!function_exists('wpmobile_comment')) {
	function wpmobile_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
	?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
			<?php if ( $comment->comment_approved == '0' ) : ?>
			<p><em><?php _e('Your comment is awaiting moderation.'); ?></em></p>
			<?php endif; ?>
	
			<?php comment_text() ?>
	
			<p><?php _e('By'); ?> <cite><?php comment_author_link() ?></cite> on <a href="#comment-<?php comment_ID() ?>"><?php comment_date(get_option('date_format')) ?> <?php _e('at'); ?> <?php comment_time() ?></a> <?php edit_comment_link('e','',''); ?></p>
	<?php
	}
}

if ( have_comments() ) : ?>
	<h3 id="comments"><?php comments_number(__('No Responses'), __('One Response'), __('% Responses') );?> to &#8220;<?php the_title(); ?>&#8221;</h3>

	<ol class="commentlist">
		<?php wp_list_comments(array('callback' => 'wpmobile_comment', 'style' => 'ol')); ?>
	</ol>
	
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<div class="navigation">
			<span class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'mobile_theme_dumb' ) ); ?></span>
			<span class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'mobile_theme_dumb' ) ); ?></span>
		</div> <!-- .navigation -->
	<?php endif; // check for comment navigation ?>
		
<?php endif; ?>


<?php if ( comments_open() ) : ?>
<h3 id="respond"><?php _e('Leave a Reply'); ?></h3>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>You must be <a href="<?php echo site_url(); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p>
<?php else : ?>

<form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p><?php _e('Logged in as'); ?> <a href="<?php echo site_url(); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo site_url(); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account'); ?>"><?php _e('Logout'); ?> &raquo;</a></p>

<?php else : ?>

<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
<label for="author"><small><?php _e('Name'); ?> <?php if ($req) echo "(required)"; ?></small></label></p>
<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
<label for="email"><small><?php _e('E-Mail'); ?> <?php if ($req) echo "(required)"; ?></small></label></p>
<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
<label for="url"><small><?php _e('Website'); ?></small></label></p>

<?php endif; ?>

<p><textarea name="comment" id="comment" cols="30" rows="10" tabindex="4"></textarea></p>
<p><input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment'); ?>" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>
<?php do_action('comment_form', $post->ID); ?>
</form>

<?php endif; // If registration required and not logged in ?>
<?php endif; // if you delete this the sky will fall on your head ?>
