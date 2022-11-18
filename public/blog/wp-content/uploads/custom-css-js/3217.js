<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(function($) {$
	var hh = $('#site-header').outerHeight();
	$('#site-content').css("margin-top", hh);
	$(window).scroll(function() {    
		var scroll = $(window).scrollTop();
		if (scroll >= 200) {
			$("#site-header").addClass("fixHeader");
		} else {
			$("#site-header").removeClass("fixHeader");
		}
	});					
});</script>
<!-- end Simple Custom CSS and JS -->
