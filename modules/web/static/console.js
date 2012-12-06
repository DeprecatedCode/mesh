var main = {
	history: [],
	success: function(data) {
		alert(data);
	},
	error: function(data) {
		alert('Err: ' + data);
	},
	send: function() {
		$.ajax({
			url: '',
			type: 'POST',
			dataType: 'json',
			data: JSON.stringify({
				key: main.key,
				line: main.cursor.val()
			}),
			success: main.success,
			error: main.error
		});
		main.cursor.val('');
	}
}

$(function() {
	var cursor = $('#x-cursor');
	main.cursor = cursor;
	$('html').click(function() {cursor.focus();});
	cursor.keypress(function(e) {
		switch(e.keyCode) {
			case 13:
				return main.send();
		}
	});
	$('#x-loading').hide();
});
