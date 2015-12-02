$(document).ready(function(){

	$('th').click(function(){
		var table = $(this).parents('table').eq(0)
		var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
		this.asc = !this.asc
		if (!this.asc){rows = rows.reverse()}
		for (var i = 0; i < rows.length; i++){table.append(rows[i])}
	})
	function comparer(index) {
		return function(a, b) {
			var valA = getCellValue(a, index), valB = getCellValue(b, index)
			return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB)
		}
	}
	function getCellValue(row, index){ return $(row).children('td').eq(index).html() }

	var keyword = $("#keyword").val();

	$.post("twitsuggestions.php")
	.done(function( data ) {
		$('#results').html('');
		var results = jQuery.parseJSON(data);
		$(results).each(function(key, value) {
			$('#results').append('<div class="item">' + value + '</div>');
		})

	    $('.item').click(function() {
	    	var text = $(this).html();
	    	$('#keyword').val(text);
	    })

	});

});
