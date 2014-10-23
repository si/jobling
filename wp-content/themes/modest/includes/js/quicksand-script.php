<?php
/*
Quicksand
*/
?>
<script type="text/javascript">
/* <![CDATA[  */ 
jQuery(document).ready(function($){
 	// Clone applications to get a second collection
	var $data = $(".filter-posts").clone();
	
	$('.filter-list li').click(function(e) {
		$(".filter li").removeClass("active");	
		// Use the last category class as the category to filter by.
		var filterClass=$(this).attr('class').split(' ').slice(-1)[0];
		
		if (filterClass == 'all-projects') {
			var $filteredData = $data.find('.project');
		} else {
			var $filteredData = $data.find('.project[data-type~=' + filterClass + ']');
		}
		$(".filter-posts").quicksand($filteredData, {
			duration: 400,
			easing: 'jswing',
			adjustHeight: 'auto',
		}, function() {
			// re-establishes lightbox again after the quicksand is done
			$(".lightbox").fancybox({
				'transitionIn'	: 'fade',
				'transitionOut'	: 'fade'
			});
			// re-establishes the vertical alignment again after the quicksand is done
			if(!$.browser.msie){
				$(".hover_info .hover_content").vAlign();
			};
			// re-establishes the hover colors after the quicksand is done
			$(".hover_info a.preview , .hover_info a.view_post").hover(function(){
				$(this).stop().animate({backgroundColor: '<?php echo stripslashes(of_get_option('button_color')); ?>'});
			}, function() {
				$(this).stop().animate({backgroundColor: '<?php echo stripslashes(of_get_option('link_color')); ?>'});
			});
		});		
		$(this).addClass("active"); 			
		return false;
	});
});
/* ]]> */
</script>