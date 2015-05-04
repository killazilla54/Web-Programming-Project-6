Event.observe(window, "load", function(){
	getList();
   $("search").observe("click", searchAll);
});

function searchAll(){
	var name = document.getElementById("allnames").value;
	getMeaning(name);
	getRank(name);
	findCelebs(name);	
	
}

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
}

function successFunc(response){
//	$("#meaning").html(response);
	document.getElementById('loadingmeaning').style.display = 'none';
	
	document.getElementById("meaning").innerHTML = response.responseText;
}

//JOSEPH
function findCelebs(name){
	document.getElementById('resultsarea').style.display = 'block';
    var url = "babynames.php?type=celebs&name=";
    //url += $("allnames").value;
    url += name;
    url += "&gender=";
    var gender = "m";
    if(!$("genderm").checked){
        gender = "f";
    }
    url += gender;
    new Ajax.Request(url, {
        method: 'get',
        onSuccess: showCelebs
    });
}

function showCelebs(ajax) {
	document.getElementById('loadingcelebs').style.display = 'none';


    var data = JSON.parse(ajax.responseText);
	console.log("PARSED!");
    var celebList = document.getElementById("celebs");
    while (celebList.firstChild) {
        celebList.removeChild(celebList.firstChild);
    }
    for (var i = 0; i < data.actors.length; i++) {
        var li = document.createElement("li");
        li.innerHTML = data.actors[i].firstName + " " + data.actors[i].lastName + " (" +
        data.actors[i].filmCount + ")";
        celebList.appendChild(li);
    }
    
}

function getList(){
    new Ajax.Request('babynames.php', {
        method: 'post',
        parameters: {type: 'list'},
        onSuccess: successList
    });
}

function successList(response){
    document.getElementById("allnames").disabled = false;
    document.getElementById("allnames").innerHTML=response.responseText;
}

function rankSuccess(response) {
    document.getElementById('loadinggraph').style.display = 'none';
   
    //console.log(response.responseText);
    var xmlDoc = response.responseXML;
    console.log(xmlDoc);

    var ranks = xmlDoc.getElementsByTagName("rank");
    var txt = "";

    txt += "<tr>";
    for (i=0; i < ranks.length;i++) {
        txt += "<th>" + ranks[i].getAttribute('year') + "</th>";
    }//end for loop

    txt += "</tr><tr>";

    for (i=0; i < ranks.length;i++) {
        txt += "<td><div>" + ranks[i].childNodes[0].nodeValue + "</div></td>";
    }//end for loop
    txt += "</tr>";

    document.getElementById('graph').innerHTML = txt;
}//end rankSuccess

function getRank(name) {
//    var dropdown = document.getElementById('allnames');
 //   var name = dropdown.options[dropdown.selectedIndex].text;
    var gender = "";
    if (document.getElementById('genderm').checked)
        gender = "m";
    else
        gender = "f";
    document.getElementById('resultsarea').style.display = 'block';
    new Ajax.Request('babynames.php', {
            method: 'post',
            parameters: {
                babyname: name,
                gender: gender,
                type: 'rank',
            },
            onSuccess: rankSuccess
    });
}//end getRank