$(window).scroll(function() {
	if ($(this).scrollTop() > 500 )
		$('.back-to-top-holder:hidden').stop(true, true).fadeIn();
	else
		$('.back-to-top-holder').stop(true, true).fadeOut();
});
$(function(){
	$(".back-to-top-button").click(function(){
		$("html,body").animate({
			scrollTop:$("#ucfhb").offset().top
		},"1000");
		return false
	});

	function showHideKeywords( e ) {
		let btn = e.currentTarget;
		
		var column = ourDataTable.column('keywords:name');

		if( column.visible() ) {
			$( btn ).html("Show Keywords");
			$('#datatable-1').parent().addClass('container');
		} else {
			$( btn ).html("Hide Keywords");
			$('#datatable-1').parent().removeClass('container');
		}
		column.visible( ! column.visible() );
		$('#datatable-1').width('100%');
	}

	$(".btn-hide-keywords").click( showHideKeywords );

	var isHidingNonSpecifiedAmounts = false;
	function showHideNonSpecifiedAmounts( e ) {
		let btn = e.currentTarget;

		var column = ourDataTable.column('filteramount:name');

		isHidingNonSpecifiedAmounts =! isHidingNonSpecifiedAmounts;

		if( isHidingNonSpecifiedAmounts ) {
			$( btn ).html("Show Unspecified Amounts");
			column.search('^(?!Not Specified).*',true, false,true).draw();
		} else {
			$( btn ).html("Hide Unspecified Amounts");
			column.search('').draw();
		}
	}

	$(".btn-hide-unspecified-amounts").click( showHideNonSpecifiedAmounts );
});
