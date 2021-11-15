<?php
?>
</div>
<div class="back-to-top-holder">
    <div class='back-to-top-button icon'><i class="fas fa-chevron-up"></i></div>
</div>
<script>
    $(function() {
        $("a").click(function(e) {
			var href = $(this).blur().attr('href');
			if( ! /^#.*/.test( href ) )
				return ;
			
			
            if ('parentIFrame' in window) {
                var href = $(this).blur().attr('href');
                var anchor = $('#' + href.substring(1) + '');
				console.log( href, anchor );
				
				if( ! anchor )
					return ;
				
                var offset = Math.round(anchor.offset().top);
                e.preventDefault();
                window.parentIFrame.sendMessage(offset);
            }
        });
		window.onscroll = function() {stickMe()};
		function stickMe() {
			  if (window.pageYOffset >= 0) {
				navbar.classList.add("sticky")
			  } else {
				navbar.classList.remove("sticky");
			  }
		}
    });
</script>
</body>
</html>