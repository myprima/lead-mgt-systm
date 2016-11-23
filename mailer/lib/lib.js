function openWindow(url) {
    popupWin = window.open(url, '', 'toolbar,scrollbars,resizable,width=400,height=300')
    popupWin.focus()
}

function openWindow2(url,w,h) {
    popupWin = window.open(url, '', 'scrollbars,resizable,width='+w+',height='+h)
    popupWin.focus()
}

/* select all checkboxes and dropdowns within the form */
var state=false;
function select_all(f) {
    var i;

    for(i=0;i<f.elements.length;i++) {
	// alert(f[i].type);
	if(f[i].type=='checkbox') {
	    if(state) {
		f[i].checked=false;
	    }
	    else {
		f[i].checked=true;
	    }
	}
	else if(f[i].type=='select-multiple') {
	    for(j=0;j<f[i].length;j++) {
		if(state) {
		    f[i][j].selected=false;
		}
		else {
		    f[i][j].selected=true;
		}	
	    }
	}
    }
    state=!state;
}

/* select all checkboxes within all forms on the page */
function select_really_all() {
    var i;

    for(a=0;a<document.forms.length;a++) {
	for(i=0;i<document.forms[a].elements.length;i++) {
	    // alert(document.forms[a][i].type);
	    if(document.forms[a][i].type=='checkbox') {
		if(state) {
		    document.forms[a][i].checked=false;
	        }
		else {
		    document.forms[a][i].checked=true;
	        }
	    }
	}
    }
    state=!state;
}

function gamma() {
    gammawindow=window.open('gamma.php','Gamma','WIDTH=300,height=350,scrollbars=1,resizable=no');
    gammawindow.window.focus();
}

function verify_url(url) {
    var w;
    w=window.open(url,'','scrollbars=1,resizable=yes,toolbar,location');
}

/* check browser */
var ns=(document.layers);
var ie=(document.all);
var w3=(document.getElementById && !ie);

function get_object(id) {
    var o=0;    
    if (id != 'form_')
    {
	    if(ie)
	        o = eval('document.all.'+id+'.style');
	    else if(w3)
	        o=eval('document.getElementById("'+id+'")');
	    else if(ns)
	        o = eval('document.layers["'+id+'"]');
    }
    return o;
}

function show_div(id) {
    var div;
    div=get_object(id);
    if(ie)
        div.visibility = "visible";
    else if(w3)
        div.style.visibility = "visible";
    else if(ns)
        div.visibility = "show";
}

function hide_div(id) {
    var div;
    div=get_object(id);
    if(ie)
        div.visibility = "hidden";
    else if(w3)
        div.style.visibility = "hidden";
    else if(ns)
        div.visibility = "hide";
}

function show_div2(id) {
    var div;
    div=get_object(id);
    if(ie)
        div.display = "";
    else if(w3)
        div.style.display = "";
    else if(ns)
        div.display = "";
}

function hide_div2(id) {
    var div;
    div=get_object(id);
    if(ie)
        div.display = "none";
    else if(w3)
        div.style.display = "none";
    else if(ns)
        div.display = "none";
}


