<?php
/*
Sterile JS
Code and stuff you need for the Sterile theme
*/

	//These are also in header.php
	//Sets variables for queries
	$projects_per = (stripslashes(of_get_option('projects_per')));
	$projects_total = (stripslashes(of_get_option('projects_total')));
	$project_offset = ($projects_total - $projects_per);
			
	//sets the name for classes depending on projects per line
	if ($projects_per == '2') {$projects_count_class = "half";};
	if ($projects_per == '3') {$projects_count_class = "third";};
	if ($projects_per == '4') {$projects_count_class = "fourth";};
	if ($projects_per == '5') {$projects_count_class = "fifth";};
?>
<script type="text/javascript">
			/* <![CDATA[  */ 
			jQuery(document).ready(function($){
				
				$('a.slicktoggle-featured').click(function() {
					if ($('#toggle-featured_extend').is(":hidden"))
						$('.featured_toggle').html('<?php _e('View Less', 'designcrumbs'); ?> &ndash;');
					else $('.featured_toggle').html('<?php _e('View More', 'designcrumbs'); ?> +');
					$('#toggle-featured_extend').slideToggle(250);
					return false;
				});
				
				// centers hover content vertically
				if(!$.browser.msie){
					$.fn.vAlign = function() {
						return this.each(function(i){
						var ah = $(this).height();
						var ph = $(this).parent().height();
						var mh = Math.ceil((ph-ah) / 2);
						$(this).css('margin-top', mh);
						});
					};
					$('.hover_content').vAlign();
				};
				
				// Children Flyout on Menu
				function mainmenu(){
				$("#main_menu ul li ul").css({display: "none"}); // Opera Fix
					$("#main_menu ul li").hover(function(){
						$(this).find('ul:first').css({visibility: "visible",display: "none"}).show(300);
						},function(){
						$(this).find('ul:first').css({visibility: "hidden"});
					});
				}
								
				mainmenu();
				
				<?php if (is_front_page()) { ?>
				// The Slider
				$(function(){
					$('#slides').slides({
						preload: true,
						preloadImage: '<?php echo get_template_directory_uri(); ?>/images/loading.gif',
						play: <?php echo stripslashes(of_get_option('slider_time')); ?>000,
						pause: 10000,
						hoverPause: true,
						effect: '<?php echo stripslashes(of_get_option('slider_fx')); ?>',
						generateNextPrev: true
					});
				});
				
				// Change class on hover
				$(".cta").hover(function(){
        			$(this).switchClass('cta', 'cta_hover', 200);
    			}, function(){
        			$(this).switchClass('cta_hover', 'cta', 400);
				});
				<?php } ?>
			
				// Adds class to commenters
				$("ul.commentlist li:not(.bypostauthor)").children(".the_comment").addClass("not_author");
				
				<?php if ((is_front_page()) || (is_page_template())) { ?>
				// Index project hovers
				$('.info_wrap').live({mouseenter:function(){
					$(this).children('.hover_info').stop().fadeTo(200, .95);
				},mouseleave:function(){
					$(this).children('.hover_info').stop().fadeTo(200, 0);
				}});
				<?php } if (is_front_page()) { ?>
				// Recent Blog Post hovers
				$('.single_latest .attachment-alt_blog_image').hover(function(){
					$(this).stop().fadeTo(200, .8);
				},function(){
					$(this).stop().fadeTo(200, 1);
				});
				<?php } if (is_home()) { ?>
				// Blog Post hovers
				$('.attachment-blog_image, .attachment-alt_blog_image').hover(function(){
					$(this).stop().fadeTo(200, .8);
				},function(){
					$(this).stop().fadeTo(200, 1);
				});
				<?php } ?>
				// portfolio gallery hovers
				$('#port_thumbs img, .attachment-port_image, .post-archive .archive_image_link, .lightbox img').hover(function(){
					$(this).stop().fadeTo(200, .7);
				},function(){
					$(this).stop().fadeTo(200, 1);
				});
				
				// Button hovers
				$('a.more-link , input[type="submit"] , .button').hover(function(){
					$(this).stop().fadeTo(50, 1);
				},function(){
					$(this).stop().fadeTo(50, .95);
				});
				
				// Animates the soc nets on hover if not IE (and if you are in IE, you're doing it wrong)
				if(!$.browser.msie){
				$("#footer_socnets").delegate("img", "mouseover mouseout", function(e) {
					if (e.type == 'mouseover') {
						$("#footer_socnets a img").not(this).dequeue().animate({opacity: "0.3"}, 300);
    				} else {
						$("#footer_socnets a img").not(this).dequeue().animate({opacity: "1"}, 300);
   					}
				});
				};
				
				//Animates preview links on hover
				$(".hover_info a.preview , .hover_info a.view_post").hover(function(){
					$(this).stop().animate({backgroundColor: '<?php echo stripslashes(of_get_option('button_color')); ?>'});
				}, function() {
					$(this).stop().animate({backgroundColor: '<?php echo stripslashes(of_get_option('link_color')); ?>'});
				});
				
				//Animates comment links, search, the logo and toggles on hover, no IE
				if(!$.browser.msie){
					$(".additional-meta_comments .comments-link").fadeTo("fast", 0.5);
					$(".additional-meta_comments .comments-link").hover(function(){
						$(this).fadeTo("fast", 1.0); 
					},function(){
						$(this).fadeTo("fast", 0.5);
					});
					
					$("#searchform").fadeTo("fast", 0.7);
					$("#searchform").hover(function(){
						$(this).fadeTo("fast", 1.0); 
					},function(){
						$(this).fadeTo("fast", 0.7);
					});
					
					$(".featured_toggle").hover(function(){
						$(this).fadeTo(100, 0.8); 
					},function(){
						$(this).fadeTo(100, 1);
					});
					
					$(".the_logo").hover(function(){
						$(this).fadeTo(100, 0.8); 
					},function(){
						$(this).fadeTo(100, 1);
					});
				};

				// Fancybox in galleries
				$("#port_thumbs a, .lightbox").fancybox({
					'showNavArrows'		: 'true',
					'transitionIn'		: 'fade',
					'transitionOut'		: 'fade'
				});
				
				// Add classes and divs
				$('#home_latest_posts .single_latest:nth-child(4)').addClass('no_margin');
				$('.blog_magazine .mag_alt_post:nth-child(2n-1)').addClass('no_margin_right');
				$('<div class="clear"></div>').insertAfter('.footer_widget_overflow .footer_widget:nth-child(3n), .blog_magazine .mag_alt_post:nth-child(2n-1)');

			});
			
			/* ]]> */
		</script>