<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(function($) {
	$(".primary-menu>li").on('mouseenter mouseleave', function (e) {
		if ($('ul', this).length) {
			var elm = $('ul:first', this);
			var off = elm.offset();
			var l = off.left;
			var w = elm.width();
			var docH = $(".primary-menu-wrapper").height();
			var docW = $('.primary-menu-wrapper').width();

			var isEntirelyVisible = (l + w <= docW);

			if (!isEntirelyVisible) {
				$(this).addClass('edge');
			} else {
				$(this).removeClass('edge');
			}
		}
	});
	
	
});

</script>
<!-- end Simple Custom CSS and JS -->
