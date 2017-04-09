
// TOOLS.JS - Useful GUI functions used in admin.php
// Version: 2017/03/22 12:12


// Popup messages

function confirmation(action, user) {
	return confirm('Are you sure that you want to perform operation\n'+action+'\non user\n'+user);
}


// Non-modal popup messages

function showMsg(msg) {
	var div=document.getElementById('msgDisplay');
	div.style.left = (window.innerWidth - div.style.width) / 2 + "px";
	div.style.visibility = "visible";
	div.innerHTML = msg;
}

function hideMsg() {
	var div=document.getElementById('msgDisplay');
	div.style.visibility = "hidden";
}


// Progress bar

function showProgress(msg) {
	var progwin = document.getElementById('progressWindow');
	var doc = document.documentElement;
	var scrollOffset = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);
	var newtop = window.innerHeight/2 - progwin.clientHeight/2 + scrollOffset;
	var newleft = window.innerWidth/2 - progwin.clientWidth/2;
	
	console.log("offset "+doc.scrollTop);
	console.log(newtop);
	console.log(progwin);
	progwin.style.visibility = "visible";
	progwin.style.top = "" + newtop + "px";
	progwin.style.left = "" + newleft + "px";
	progwin.style.zIndex = "100";
	console.log(newtop);
	console.log(progwin);
	console.log(progwin.style.top);
	console.log(progwin.style.left);
	
	var progmsg = document.getElementById('progressBarMsg');
	progmsg.innerHTML = msg;
	
	updateProgress(0);
}
function hideProgress() {
	var progwin = document.getElementById('progressWindow');
	progwin.style.visibility = "hidden";
}
function updateProgress(percent) {
	var progbar = document.getElementById('myBar');
	var proglabel = document.getElementById('progressBarLabel');
	progbar.style.width = "" + percent + "%";
	proglabel.innerHTML = percent * 1  + '%';
}


// Universal showhide function

function showhide(id) {
	var o = document.getElementById(id);
	if (o.style.display=="block"){
		o.style.display="none";
	} else {
		o.style.display="block";
	}
}

