$(function() {
	$('#hljs-cancel').click(function() {
		window.close();
		return false;
	});

	$('#hljs-ok').click(function() {
		sendClose();
		window.close();
		return false;
	});

	function sendClose() {
		var insert_form = $('#hljs-form').get(0);
		if (insert_form == undefined) { return; }
		var tb = window.opener.the_toolbar;
		var data = tb.elements.hljs.data;
		data.syntax = insert_form.syntax.value;
		tb.elements.hljs.fncall[tb.mode].call(tb);
	};
});
