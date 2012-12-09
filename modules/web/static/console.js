var main = {
	history: [],
	print: function(str) {
		main.loading.remove();
		var line = main.newline();
		main.line.text(str);
		main.line = line;
	},
	success: function(data) {
		if(data.key){
			main.key = data.key;
		}
		if(data.prompt){
			main.prompt = data.prompt + ': ';
		}
		if(data.options) {
			if(data.options == 'secure') {
				main.secure = true;
				main.cursor[0].type = 'password';
			} else if(main.secure) {
				main.cursor[0].type = 'text';
				main.secure = false;
			}
		}
		if(data.data !== null) {
			main.print(JSON.stringify(data.data));
		} else {
			var line = main.newline();
			main.line.remove();
			main.line = line;
		}
	},
	error: function(xhr, status) {
		main.print("Error: " + status);
	},
	newline: function(loading) {
		var line = $('<div class="line">');
		main.body.append(line);
		if(loading) {
			line.append(main.loading);
		}
		line.text(main.prompt);
		line.append(main.cursor);
		main.cursor.focus();
		return line;
	},
	clear: function() {
		var sel = $('.line');
		var line = main.newline();
		main.line = line;
		sel.remove();
	},
	send: function() {
		var inp = main.cursor.val();
		main.cursor.val('');
		$.ajax({
			url: '',
			type: 'POST',
			dataType: 'json',
			data: JSON.stringify({
				key: main.key,
				line: inp
			}),
			success: main.success,
			error: main.error
		});
		var line = main.newline(true);
		if(main.secure) {
			inp = (new Array(inp.length + 1)).join("&bull;");
			main.line.html(main.prompt + inp);
		} else {
			main.line.text(main.prompt + inp);
		}
		if(main.line.text() == '') {
			main.line.remove();
		}
		main.line = line;
	}
}

$(function() {
	main.prompt = '';
	main.cursor = $('#x-cursor');
	main.loading = $('#x-loading');
	main.body = $('body');
	main.line = $(".line");
	main.secure = false;
	$('html').click(function() {main.cursor.focus();});
	main.cursor.keypress(function(e) {
		switch(e.keyCode) {
			case 13:
				return main.send();
		}
	});
	main.cursor.keydown(function(e) {
		if(e.ctrlKey) {
			switch(e.keyCode) {
				case 76:
					e.preventDefault();
					return main.clear();
				default:
					//console.log(e);
			}
		}
	});
	main.send(true);
});
