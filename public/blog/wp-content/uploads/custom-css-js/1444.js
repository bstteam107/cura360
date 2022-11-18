<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
/* Default comment here */ 
jQuery(function($) {
	$('.mobile_menu').click(function() {
		$('.primary-menu-wrapper').slideToggle('slow');
	});
	$('.menu-item-has-children').click(function(e) {
		e.stopPropagation();
		//$(this).children('ul').toggleClass('d_active');
		$(this).children('ul').slideToggle('slow');
	});
});
</script>
<!-- end Simple Custom CSS and JS -->
