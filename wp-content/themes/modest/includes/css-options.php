<style type="text/css">
a {
	color:<?php echo stripslashes(of_get_option('link_color')); ?>;
}
ul.commentlist .bypostauthor img.avatar {
	border:1px solid <?php echo stripslashes(of_get_option('link_color')); ?>;
}
input[type="submit"], .button {
	background-color:<?php echo stripslashes(of_get_option('button_color')); ?>;
}
.button_secondary {
	background-color:<?php echo stripslashes(of_get_option('button_color_secondary')); ?>;
}
.hover_info a.preview, .hover_info a.view_post {
	background-color:<?php echo stripslashes(of_get_option('link_color')); ?>;
}
</style>
<!--[if IE]>
<style type="text/css">
.hover_content {
	padding-top:20px;
}
</style>
<![endif]-->