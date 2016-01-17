function readTooltips(tooltipArray) {
	console.log('readTooltips');
	for(var i=0; i<tooltipArray.length; i++) {
		var label = "#" + tooltipArray[i].id;
		var text = tooltipArray[i].tooltipText;
		var optionsObj = tooltipArray[i].options || {};
		
		optionsObj.title = text;
		
		//$(label).tooltip(optionsObj);
		$('[id^=' + tooltipArray[i].id + ']').tooltip(optionsObj);
		
		console.log($('[id^=' + tooltipArray[i].id + ']'));
	}
}

function setupAutoComplete() {
	$('select[name^="presentationType"]').change(function() {
		autoComplete(this);
	});

	$('input[name^="name"]').focusout(function() {
		autoComplete(this);
		camelCaseDescriptionFromName(this);
	});
}

function autoComplete(that) {
	$that = $(that);
	var nameOfField = $that.attr("name");
	
	var pointer = nameOfField.slice(-1);
	if(/^[0-9]*$/.test(pointer) == false) {
		pointer = "";
	}
	else {
		// remove last char (pointer)
		nameOfField = nameOfField.slice(0, - 1);
	}

	//console.log(nameOfField); console.log(pointer);

	var type = $("select[name='type" + pointer + "']");
	var length = $("input[name='length" + pointer + "']");
	var collation = $("input[name='colCollation" + pointer + "']");
	
	if ( nameOfField === "name" ) {
		// aNiceId -> char 10 ascii...
		if($that.val().slice(-2) === "Id") {
			type.val('3');
			length.attr("value", "10");
			collation.attr("value", "ascii_bin");
			return;
		}
		else {
			collation.attr("value", "");
		}
	}
		
	if ( nameOfField === "presentationType" ) {
		var presentationType = $("option:selected", that).text();
		
		if(presentationType == "checkBox") {
			type.val('3');
			length.attr('value', '1');
			collation.attr("value", "ascii_bin");
		}
		else if(presentationType == "date" || presentationType == "time" || presentationType == "dateAndTime") {
			type.val('3');
			length.attr("value", "14");
			collation.attr("value", "ascii_bin");
		}
		else if(presentationType == "file") {
			type.val('3');
			length.attr("value", "10");	
			collation.attr("value", "ascii_bin");
		}
		else if(presentationType == "picture") {
			type.val('4');
			length.attr("value", "255");
		}
		else if(presentationType == "radioHoriz" || presentationType == "radioVert") {
			type.val('3');
			length.attr("value", "10");
			collation.attr("value", "ascii_bin");
		}
		else {
			//type.val('0');
			//length.attr("value", "");
		}
	}
}

function camelCaseString(str) {
	return str
			// insert a space between lower & upper
		    .replace(/([a-z])([A-Z])/g, '$1 $2')
		    // space before last upper in a sequence followed by lower
		    .replace(/\b([A-Z]+)([A-Z])([a-z])/, '$1 $2$3')
		    // uppercase the first character
		    .replace(/^./, function(str){ return str.toUpperCase(); });
	
}

function camelCaseDescriptionFromName(that) {
	$that = $(that);
	var pointer = $that.attr("name").slice(-1);
	
	if(/^[0-9]*$/.test(pointer) == false) {
		pointer = "";
	}
			
	$("input[name='description" + pointer + "']").val(camelCaseString($("input[name='name" + pointer + "']").val()));
}






