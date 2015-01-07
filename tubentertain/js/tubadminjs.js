//:::::::::Iscrolls
var channelScroll;
function channelLoaded () {
	channelScroll = new IScroll('#forIntro', 
	{
	scrollbars: true,
	scrollX: false,
	scrollY: true,
	momentum: false,
	snap: true,
	snapSpeed: 400,
	keyBindings: true,
	interactiveScrollbars:true,
	mouseWheel: true, click: true
	});
}
jQuery(document).ready(function($) {
//alert('is there');
channelLoaded ();
});