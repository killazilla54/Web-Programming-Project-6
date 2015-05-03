// JavaScript Document

function getMeaning(name){
	document.getElementById('resultsarea').style.display = 'block';
	new Ajax.Request('babynames.php', {
		method: 'post',
		parameters: {
			babyname: name,
			type: 'meaning',
  		},
		onSuccess: successFunc
  });
	/*
	$.ajax({
		type:"POST",
		url:"babynames.php",
		data:{babyname:name,type:"meaning"},
		success: function(data){
			$("#meaning").html(data);
		}
	});*/
}

function successFunc(response){
//	$("#meaning").html(response);
	document.getElementById('loadingmeaning').style.display = 'none';
	
	document.getElementById("meaning").innerHTML = response.responseText;
}