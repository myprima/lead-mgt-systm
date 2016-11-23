<?php
/* NS and others version */
if(!ereg('MSIE',$HTTP_USER_AGENT)) {
?>

function gamma() {
    gammawindow=window.open('gamma.php','Gamma','WIDTH=300,height=350,scrollbars=1,resizable=no');
    gammawindow.window.focus();
}

<!--hide this script from old browsers

//document.write('<B>Prompt mode is now working.</B><BR>')
//document.write('<PRE><B>Help status:  </B>')
helpon = true  // ...true or false
//document.write('<input type="radio" name="radiohelp"  value="true" onClick = " helpon = true"> ON ')
//document.write('<input type="radio" name="radiohelp"  value="false" checked onClick = " helpon = false"> OFF <BR>')

//document.write('<B>HTML mode:    </B>')
modeindex = 2	// ...0=simple, 1=sample, 2=prompt
//document.write('<input type="radio" name="radiomode"  value="Copyright 1996  Ray Daly" onClick = " modeindex = 0 "> Simple ')
//document.write('<input type="radio" name="radiomode"  value="Copyright 1996  Ray Daly" onClick = " modeindex = 1"> Sample ')
//document.write('<input type="radio" name="radiomode"  value="Copyright 1996  Ray Daly" onClick = " modeindex = 2" checked> Prompt <BR></PRE>')

document.write('<TABLE CELLSPACING="0" BORDER="0">')
//document.write('<TR><TH><input type="button" value=" Start " onClick="HjButton<?php echo $f; ?>(this.form,jStart)">')
//document.write('<input type="button" value=" End " onClick="HjButton<?php echo $f; ?>(this.form,jEnd)">')
//document.write('</TH><TH><input type="button" value="    Preview    " onClick="preview(this.form)">')
//document.write('</TH><TH><input type="button" value="     Save    " onClick="Save(this.form)">')
//document.write('</TH><TH><input type="button" value="     About      " onClick="About(this.form)">')

document.write('</TH></TR><TR><TH><input type="button" value=" Bold " onClick="HjButton<?php echo $f; ?>(this.form,jBold)">')
document.write('<input type="button" value="  Italize  " onClick="HjButton<?php echo $f; ?>(this.form,jItalics)">')
//document.write('<input type="button" value=" PRE " onClick="HjButton<?php echo $f; ?>(this.form,jPre)">  ')

document.write('</TH><TH><input type="button" value=" H1 " onClick="HjButton<?php echo $f; ?>(this.form,jH1)">')
document.write('<input type="button" value=" H2 " onClick="HjButton<?php echo $f; ?>(this.form,jH2)">')
document.write('<input type="button" value=" H3 " onClick="HjButton<?php echo $f; ?>(this.form,jH3)">  ')

document.write('</TH><TH><input type="button" value=" Font " onClick="HjButton<?php echo $f; ?>(this.form,jFont)">')
document.write('&nbsp;<input type=button value ="Color Box" onclick="gamma()"')
//document.write('</TH><TH><input type="button" value=" OL " onClick="HjButton<?php echo $f; ?>(this.form,jOL)">')
//document.write('<input type="button" value=" UL " onClick="HjButton<?php echo $f; ?>(this.form,jUL)">')
//document.write('<input type="button" value=" LI " onClick="HjButton<?php echo $f; ?>(this.form,jLI)">  ')

//document.write('</TH><TH><input type="button" value=" DL " onClick="HjButton<?php echo $f; ?>(this.form,jDL)">')
//document.write('<input type="button" value=" DT " onClick="HjButton<?php echo $f; ?>(this.form,jDT)">')
//document.write('<input type="button" value=" DD " onClick="HjButton<?php echo $f; ?>(this.form,jDD)"><BR>')

document.write('</TH></TR><TR><TH><input type="button" value=" Paragraph " onClick="HjButton<?php echo $f; ?>(this.form,jPara)">')
document.write('<input type="button" value=" New Line " onClick="HjButton<?php echo $f; ?>(this.form,jBreak)">')
document.write('<input type="button" value=" Horizontal Line " onClick="HjButton<?php echo $f; ?>(this.form,jRule)">  ')

document.write('</TH><TH><input type="button" value="    Web Link     " onClick="HjButton<?php echo $f; ?>(this.form,jAnchor)">  ')

//document.write('</TH><TH><input type="button" value=" L" onClick="HjButton<?php echo $f; ?>(this.form,jImageL)">')
//document.write('<input type="button" value=" Image " onClick="HjButton<?php echo $f; ?>(this.form,jImage)">')
//document.write('<input type="button" value="R" onClick="HjButton<?php echo $f; ?>(this.form,jImageR)">  ')
document.write('</TH><TH><input type="button" value="     Center     " onClick="HjButton<?php echo $f; ?>(this.form,jCenter)">  ')
document.write('</TH></TR></TABLE>')

function HjReset (form) {                // ...required because RESET does not reset values, just GUI
               helpon = false
               modeindex = 0
               form.<?php echo $f; ?>.value = ""
}

function HTMLtag (buttonname, insertmode, inserttext, tagstart, tagmiddle, tagend, sampletext, sampletext2, helptext) {
	// ...this fuction defines the object HTMLtag
	this.buttonname = buttonname
	this.insertmode = insertmode	
		// ...1=none 2=standard input 3=lists (UL and OL) 4=DL list 5=anchor
	this.inserttext = inserttext	// ...prompt when asking for insert text
	this.tagstart   = tagstart
	this.tagmiddle  = tagmiddle
	this.tagend     = tagend
	this.sampletext = sampletext	// ...sample text placed between tags in TextArea 
	this.sampletext2= sampletext2
	this.helptext   = helptext
}

jStart = new HTMLtag ( "Start", "2", "Enter the document TITLE", " <HTML><HEAD><TITLE>", "",               "</TITLE></HEAD><BODY>",
	"Title of the document (eg. HTMLjive Page)", "" ,
	"Tags for start of document and the TITLE go here.  Use the END button when your document is complete." )

jEnd = new HTMLtag ( "End", "1", "", " </BODY></HTML>", "", "",
	"", "" ,
	"This puts in the closing tags when your document is complete." )

jBold = new HTMLtag ( "B", "2", "Enter the text to be BOLD", " <B>", "", "</B>",
	"This will be bold", "" ,
	"The text placed between the <B> and the </B> will be BOLD" )

jItalics = new HTMLtag ( "I", "2", "Enter the text to be ITALICIZED", " <I>", "", "</I>",
	"This will be in italics", "" ,
	"The text placed between the <I> and the </I> will be ITALICIZED" )

jPre = new HTMLtag ( "PRE", "2", "Enter the text to be PREformatted", " <PRE>", "", "</PRE>",
	"This will be PREformatted", "" ,
	"The text placed between the <PRE> and the </PRE> will be PREformatted" )

jPara = new HTMLtag ( "P", "2", "Enter the text for a PARAGRAPH", " <P>", "", "</P>",
	"Start of a PARAGRAPH that continues until the start of next PARAGRAPH.", "" ,
	"The text placed after the <P> will be a separate PARAGRAPH until the next <P>" )

jFont = new HTMLtag ( "FONT", "6","Enter color. Use color box to choose the color, cut and paste here." , " <FONT color=", ">", "</FONT>",
	"Formatted text using FONT tag.", "#fffff (black text)" ,
	"You can customize text size, color and font using the <FONT> tag." )
jFont.inserttext2 = "Enter the text to be formatted"

jBreak = new HTMLtag ( "BR", "1", "", " <BR>", "", "",
	"", "" ,
	"This tag forces a line break, start of the next line." )

jRule = new HTMLtag ( "HR", "1", "", " <HR>", "", "",
	"", "" ,
	"This tag puts a (horizontal rule) line on the page." )

jH1 = new HTMLtag ( "H1", "2", "Enter the text for the HEADLINE", " <H1>", "", "</H1>",
	"This will be a top level HEADLINE", "" ,
	"The text placed between the <H1> and the </H1> will be the HEADLINE." )

jH2 = new HTMLtag ( "H2", "2", "Enter the text for the HEADLINE", " <H2>", "", "</H2>",
	"This will be a next to top level HEADLINE", "" ,
	"The text placed between the <H2> and the </H2> will be the HEADLINE." )

jH3 = new HTMLtag ( "H3", "2", "Enter the text for the HEADLINE", " <H3>", "", "</H3>",
	"This will be a third from top level HEADLINE", "" ,
	"The text placed between the <H3> and the </H3> will be the HEADLINE." )

jCenter = new HTMLtag ( "Center", "2", "Enter the text to be CENTERED", " <CENTER>", "", "</CENTER>",
	"This will be a CENTERED", "" ,
	"The text placed between the <CENTER> and the </CENTER> will be the CENTERED." )

jOL = new HTMLtag ( "OL", "3", "Enter FIRST item for (Numbered) ORDERED LIST", " <OL><LI> ", "</LI><LI> ", "</LI></OL>",
	"This is one item in the numbered list", "Next item in the numbered list" ,
	"Create an ORDERED LIST by placing multiple items between <LI> and </LI>" )

jOL.inserttext2 = "Enter NEXT item for ORDERED LIST"

jUL = new HTMLtag ( "UL", "3", "Enter FIRST item for (Plain) UNORDERED LIST", " <UL><LI> ", "</LI><LI> ", "</LI></UL>",
	"This is one item in the plain list", "Next item in the plain list" ,
	"Create an ORDERED LIST by placing multiple items between <LI> and </LI>" )
jUL.inserttext2 = "Enter NEXT item for UNORDERED LIST"

jLI = new HTMLtag ( "LI", "2", "Enter the text for an item in a LIST", " <LI>", "", "</LI>",
	"This is an item in a LIST", "" ,
	"The text placed between the <L1> and the </L1> will one item in a LIST.  Requires OL or UL." )

jDL = new HTMLtag ( "UL", "4", "Enter item for DEFINITION LIST", " <DL><DT>", "</DT> <DD>", " </DD></DL>",
	"Item to be defined", "Definition of the item" ,
	"DEFINITION LISTS have two elements: item and definition.  Enter the item." )
jDL.inserttext2 = "Enter the definition "
jDL.tagmiddle2='</DD> <DT>'

jDT = new HTMLtag ( "DT", "2", "Enter item for a DEFINITION LIST", " <DT>", "", "</DT>",
	"This is item for a DEFINITION LIST", "" ,
	"The text placed between the <DT> and the </DT> will one item in a LIST.  Requires DL." )

jDD = new HTMLtag ( "DD", "2", "Enter definition for a DEFINITION LIST", " <DD>", "", "</DD>",
	"This is definition in a DEFINITION LIST", "" ,
	"The text placed between the <DD> and the </DD> will one item in a LIST.  Requires DL." )

jImageL = new HTMLtag ("Image", 2, "Enter the URL (e.g., sample.gif)", ' <IMG SRC="', "", '" ALIGN=LEFT>',
	"URL to graphic (e.g. sample.gif)" , "",
	"Enter the URL for the graphic (e.g., sample.gif) for LEFT alignment" )

jImage = new HTMLtag ("Image", 2, "Enter the URL (e.g., sample.gif)", ' <IMG SRC="', "", '">',
	"URL to graphic (e.g. sample.gif)" , "",
	"Enter the URL for the graphic (e.g., sample.gif)" )

jImageR = new HTMLtag ("Image", 2, "Enter the URL (e.g., sample.gif)", ' <IMG SRC="', "", '" ALIGN=RIGHT>',
	"URL to graphic (e.g. sample.gif)" , "",
	"Enter the URL for the graphic (e.g., sample.gif) for RIGHT alignment" )

jAnchor = new HTMLtag ("Anchor", 5, "Enter the URL (e.g., http://www.yahoo.com)", ' <A HREF="', '"> ', '</A>',
	"http://www.yahoo.com", "Yahoo (description here)", 
	"Enter the URL (e.g., http://www.yahoo.com/index.html) followed by the description." )

jAnchor.inserttext2 = "Enter description (e.g., Yahoo)"

function About (form) {
	alert('HTMLjive 1.2 (C) 1996 Ray Daly www.cris.com/~raydaly/htmljive.html')
}

function Save (form) {
	alert('No SAVE function is available.  You must "cut & paste" your document into another application.')
}

function HjButton<?php echo $f; ?> (form,selection) {	// ...all HTML button call this routine
	if (helpon) {
		if (confirm(selection.helptext)){
			addHTML<?php echo $f; ?> (form,selection)
		}
	}else{
		addHTML<?php echo $f; ?> (form,selection)
	}
}

function addHTML<?php echo $f; ?> (form,selection) {
	// ...add text to value ot TextArea
	cancel = false
	addText=selection.tagstart
	if (modeindex==2) {	// ...insert mode
		if (selection.insertmode != 1 ) {	// ...if none, skip it all
			addText += addHTMLinsert (selection, addText, form)
			if (addText == selection.tagstart) {
				cancel = true
			}
		}
	}

	if (modeindex==1) {	// ...sample mode
		addText = addText + selection.sampletext + selection.tagmiddle + selection.sampletext2
	}

	if (modeindex==0) {	// ...simple mode
		addText += selection.tagmiddle
	}

	addText += selection.tagend
	if (cancel == false) {	// ...put text into TextArea unless canceled
		form.<?php echo $f; ?>.value += addText
	}
}

function addHTMLinsert (selection, addText, form) {
	// ...insert mode
	// ...addText alread as .tagstart, cancel=false
	// ...insertmodes 1=none, 2=insert, 3=lists (UL and OL), 4=DL list, 5=anchor
	if (selection.insertmode ==2) {		// ...simple insert (eg.<B>...</B>
		i = ""
		i = prompt (selection.inserttext, "")
		if ((i != null) && (i != "")) {			// ...if input add
			addText = i 
		}else{
			addText = ""
		}
	}

	if (selection.insertmode == 3) {	// ...UL and OL lists
		addText = ""
		i = ""
		i = prompt (selection.inserttext, "")
		if ((i != null) && (i != "")) {
			addText = i
			while ((i != null) && (i != "")) {	// ...get next until null
				i=prompt (selection.inserttext2, "")
				if ((i != null) && (i != "")) {
					addText=addText + selection.tagmiddle + i
				}
			}
		}
	}

	if (selection.insertmode == 4) {	// ...DL list
		i= "dummy"
		j = i
		addText = ""
		count = 0
		while ((i != null) && (i != "") && (j != null) && (j !="")) {	// ...get next until null
			++count 
			i = ""
			i = prompt (selection.inserttext, "")
			// ... used for debugging form.<?php echo $f; ?>.value += "-->" + i + "<--"
			if ((i != null) && (i != "")) {
				j = ""
				j=prompt (selection.inserttext2, "")
				if ((j != null) && (j != "")) {
					if (count > 1){
						addText += selection.tagmiddle2
					}
					addText=addText +i + selection.tagmiddle + j
				}
			}
		}
	}

	if (selection.insertmode == 5) {	// ...Anchor
		addText = ""
		i = ""
		i = prompt (selection.inserttext, "")
		// ... used for debugging form.<?php echo $f; ?>.value += "-->" + i + "<--"
		if ((i != null) && (i != "")) {
			j = ""
			j=prompt (selection.inserttext2, "")
			if ((j != null) && (j != "")) {
				addText=i + selection.tagmiddle + j
			}
		}
	}

	if (selection.insertmode == 6) {	// ...Font
		addText = ""
		i = ""
		i = prompt (selection.inserttext2, "")
		// ... used for debugging form.<?php echo $f; ?>.value += "-->" + i + "<--"
		if ((i != null) && (i != "")) {
			j = ""
			j=prompt (selection.inserttext, "")
			textsize=prompt("Enter text size. -1 and less is a small size, 1 is a normal size,\n 2 and more is a big size.\n Leave empty to skip","")
			face=prompt("Enter text face (font name). Common values are 'Arial, Helvetica, sans-serif',\n 'Verdana', 'Times New Roman'.\n Leave empty to skip","")
			if ((j != null) && (j != "")) {
			    addText=j
			}
			if((textsize!=null) && (textsize!='')) {
			    addText+=" size='"+textsize+"'"
			}
			if((face!=null) && (face!='')) {
			    addText+=" face='"+face+"'"
			}
			addText+=" "+selection.tagmiddle+i
		}
	}
	return addText

}

function preview(form) {
             msg=open("","DisplayWindow","toolbar=no,directories=no,menubar=yes");
             msg.document.write(form.<?php echo $f; ?>.value);
}

<?php
}
/*
 * MSIE editor
 */
else {
?>

document.write("<select size=\"1\" id=\"FontName\" class=\"stTBGeneric\" title=\"Font Name\" language=\"javascript\" onchange=\"<?php echo $f; ?>format('fontname',this[this.selectedIndex].value);this.selectedIndex=0\"> <option selected> --Choose a font--</option> <option value=\"Arial\">Arial</option> <option value=\"Arial Black\">Arial Black</option> <option value=\"Arial Narrow\">Arial Narrow</option> <option value=\"Comic Sans MS\">Comic MS</option> <option value=\"Courier New\">Courier New</option> <option value=\"System\">System</option> <option value=\"Tahoma\">Tahoma</option> <option value=\"Times New Roman\">Times New Roman</option> <option value=\"Verdana\">Verdana</option> <option value=\"Wingdings\">Wingdings</option> </select>");
document.write("<img src=\"html_editor/itogglemode.gif\" width=22 height=22 TITLE=\"View Source\"  align=absbottom language=javascript onclick=<?php echo $f; ?>toggleMode() name=<?php echo $f; ?>togglemd onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>togglemd','<?php echo $f; ?>togglemd2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>togglemd','<?php echo $f; ?>togglemd1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>togglemd','<?php echo $f; ?>togglemd3')\"   onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>togglemd','<?php echo $f; ?>togglemd2')\"><img src=\"html_editor/preview.gif\" width=22 height=22 TITLE=Preview align=absbottom language=javascript onclick=<?php echo $f; ?>fpreview() name=<?php echo $f; ?>preview onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>preview','<?php echo $f; ?>preview2')\"   onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>preview','<?php echo $f; ?>preview1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>preview','<?php echo $f; ?>preview3')\"   onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>preview','<?php echo $f; ?>preview2')\"><img src=\"html_editor/hline.gif\" align=absmiddle><IMG src=\"html_editor/iletterbig.gif\" width=22 height=22 align=absbottom TITLE=\"Increase Font Size\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>format('fontsize',<?php echo $f; ?>fs++)\"; name=<?php echo $f; ?>letterbig onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>letterbig','<?php echo $f; ?>letterbig2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>letterbig','<?php echo $f; ?>letterbig1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>letterbig','<?php echo $f; ?>letterbig3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>letterbig','<?php echo $f; ?>letterbig2')\"><IMG src=\"html_editor/ilettersm.gif\" width=22 height=22 align=absbottom TITLE=\"Decrease Font Size\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>format('fontsize',<?php echo $f; ?>fs--)\"; name=<?php echo $f; ?>lettersm onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>lettersm','<?php echo $f; ?>lettersm2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>lettersm','<?php echo $f; ?>lettersm1')\"  onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>lettersm','<?php echo $f; ?>lettersm3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>lettersm','<?php echo $f; ?>lettersm2')\">");
document.write("<img src=\"html_editor/hline.gif\" align=absmiddle><IMG src=\"html_editor/ibold.gif\" align=\"absbottom\" width=23 height=22 TITLE=\"bold\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>format('bold');<?php echo $f; ?>.document.designMode='On';\" name=<?php echo $f; ?>bold onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>bold','<?php echo $f; ?>bold2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>bold','<?php echo $f; ?>bold1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>bold','<?php echo $f; ?>bold3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>bold','<?php echo $f; ?>bold2')\"><img src=\"html_editor/iitalic.gif\" align=\"absbottom\" width=\"23\" height=\"22\" TITLE=\"Italic\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>format('italic')\" name=<?php echo $f; ?>ital onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>ital','<?php echo $f; ?>ital2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>ital','<?php echo $f; ?>ital1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>ital','<?php echo $f; ?>ital3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>ital','<?php echo $f; ?>ital2')\"><img src=\"html_editor/iunder.gif\" align=\"absbottom\" width=\"23\" height=\"22\" TITLE=\"Underline\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>format('underline')\" name=<?php echo $f; ?>under onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>under','<?php echo $f; ?>under2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>under','<?php echo $f; ?>under1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>under','<?php echo $f; ?>under3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>under','<?php echo $f; ?>under2')\"><img src=\"html_editor/hline.gif\" align=absmiddle><img src=\"html_editor/textcol.gif\" align=\"absbottom\" width=\"22\" height=\"22\" TITLE=\"Foreground Color\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>foreColor()\" name=<?php echo $f; ?>textcol onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>textcol','<?php echo $f; ?>textcol2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>textcol','<?php echo $f; ?>textcol1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>textcol','<?php echo $f; ?>textcol3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>textcol','<?php echo $f; ?>textcol2')\"><img src=\"html_editor/backcol.gif\" align=\"absbottom\" width=\"22\" height=\"22\" TITLE=\"Background Color\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>backColor()\" name=<?php echo $f; ?>backcol onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>backcol','<?php echo $f; ?>backcol2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>backcol','<?php echo $f; ?>backcol1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>backcol','<?php echo $f; ?>backcol3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>backcol','<?php echo $f; ?>backcol2')\"><IMG src=\"html_editor/ilink.gif\" align=\"absbottom\" width=23 height=22 TITLE=\"Insert Hyperlink\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>format('createlink');\" name=<?php echo $f; ?>link onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>link','<?php echo $f; ?>link2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>link','<?php echo $f; ?>link1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>link','<?php echo $f; ?>link3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>link','<?php echo $f; ?>link2')\"><img src=\"html_editor/hline.gif\" align=absmiddle><img src=\"html_editor/ialeft.gif\" align=\"absbottom\" width=\"23\" height=\"22\" TITLE=\"Align Left\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>format('justifyleft')\" name=<?php echo $f; ?>aleft onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>aleft','<?php echo $f; ?>aleft2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>aleft','<?php echo $f; ?>aleft1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>aleft','<?php echo $f; ?>aleft3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>aleft','<?php echo $f; ?>aleft2')\"><img src=\"html_editor/iacenter.gif\" align=\"absbottom\" width=\"23\" height=\"22\" TITLE=\"Center\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>format('justifycenter')\" name=<?php echo $f; ?>acenter onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>acenter','<?php echo $f; ?>acenter2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>acenter','<?php echo $f; ?>acenter1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>acenter','<?php echo $f; ?>acenter3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>acenter','<?php echo $f; ?>acenter2')\"><img src=\"html_editor/iaright.gif\" align=\"absbottom\" width=\"23\" height=\"22\" TITLE=\"Align Right\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>format('justifyright')\" name=<?php echo $f; ?>aright onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>aright','<?php echo $f; ?>aright2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>aright','<?php echo $f; ?>aright1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>aright','<?php echo $f; ?>aright3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>aright','<?php echo $f; ?>aright2')\"><img src=\"html_editor/hline.gif\" align=absmiddle>");
document.write("<IMG src=\"html_editor/outdent.gif\" width=23 height=22 align=absbottom TITLE=\"Decrease Indent\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>format('outdent')\"; name=<?php echo $f; ?>outdent onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>outdent','<?php echo $f; ?>outdent2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>outdent','<?php echo $f; ?>outdent1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>outdent','<?php echo $f; ?>outdent3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>outdent','<?php echo $f; ?>outdent2')\"><IMG src=\"html_editor/indent.gif\" width=23 height=22 align=absbottom TITLE=\"Increase Indent\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>format('indent')\"; name=<?php echo $f; ?>indent onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>indent','<?php echo $f; ?>indent2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>indent','<?php echo $f; ?>indent1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>indent','<?php echo $f; ?>indent3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>indent','<?php echo $f; ?>indent2')\"><img src=\"html_editor/inumlist.gif\" align=\"absbottom\" width=\"23\" height=\"22\" TITLE=\"Numbered List\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>format('insertorderedlist')\" name=<?php echo $f; ?>numlist onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>numlist','<?php echo $f; ?>numlist2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>numlist','<?php echo $f; ?>numlist1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>numlist','<?php echo $f; ?>numlist3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>numlist','<?php echo $f; ?>numlist2')\"><img src=\"html_editor/iblist.gif\" align=\"absbottom\" width=\"23\" height=\"22\" TITLE=\"Bulletted List\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>format('insertunorderedlist')\" name=<?php echo $f; ?>blist onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>blist','<?php echo $f; ?>blist2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>blist','<?php echo $f; ?>blist1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>blist','<?php echo $f; ?>blist3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>blist','<?php echo $f; ?>blist2')\"><img src=\"html_editor/hline.gif\" align=absmiddle><img src=\"html_editor/spell.gif\" align=\"absbottom\" width=\"23\" height=\"22\" TITLE=\"Spell Check\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>sendtext()\" name=<?php echo $f; ?>spellcheck onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>spellcheck','<?php echo $f; ?>spellcheck2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>spellcheck','<?php echo $f; ?>spellcheck1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>spellcheck','<?php echo $f; ?>spellcheck3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>spellcheck','<?php echo $f; ?>spellcheck2')\"><img src=\"html_editor/help.gif\" align=\"absbottom\" width=\"22\" height=\"22\" TITLE=\"Help\" LANGUAGE=\"javascript\" onclick=\"<?php echo $f; ?>help()\" name=<?php echo $f; ?>thelp onMouseOver=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>thelp','<?php echo $f; ?>help2')\" onMouseOut=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>thelp','<?php echo $f; ?>help1')\" onMouseDown=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>thelp','<?php echo $f; ?>help3')\" onMouseUp=\"<?php echo $f; ?>hiLite('<?php echo $f; ?>thelp','<?php echo $f; ?>help2')\">");
document.write('<iframe ID="<?php echo $f; ?>" <?php echo $fparam; ?>></iframe>');
document.write('<input type=hidden name="<?php echo $f; ?>">');

<?php echo $f; ?>fs=4;

// preload images:
if (document.images) {
    <?php echo $f; ?>bold1 = new Image(23,22); <?php echo $f; ?>bold1.src = "html_editor/ibold.gif";
    <?php echo $f; ?>bold2 = new Image(23,22); <?php echo $f; ?>bold2.src = "html_editor/ibold_up.gif";
    <?php echo $f; ?>bold3 = new Image(23,22); <?php echo $f; ?>bold3.src = "html_editor/ibold_dn.gif";
    <?php echo $f; ?>ital1 = new Image(23,22); <?php echo $f; ?>ital1.src = "html_editor/iitalic.gif";
    <?php echo $f; ?>ital2 = new Image(23,22); <?php echo $f; ?>ital2.src = "html_editor/iitalic_up.gif";
    <?php echo $f; ?>ital3 = new Image(23,22); <?php echo $f; ?>ital3.src = "html_editor/iitalic_dn.gif";
    <?php echo $f; ?>under1 = new Image(23,22); <?php echo $f; ?>under1.src = "html_editor/iunder.gif";
    <?php echo $f; ?>under2 = new Image(23,22); <?php echo $f; ?>under2.src = "html_editor/iunder_up.gif";
    <?php echo $f; ?>under3 = new Image(23,22); <?php echo $f; ?>under3.src = "html_editor/iunder_dn.gif";
    <?php echo $f; ?>textcol1 = new Image(23,22); <?php echo $f; ?>textcol1.src = "html_editor/textcol.gif";
    <?php echo $f; ?>textcol2 = new Image(23,22); <?php echo $f; ?>textcol2.src = "html_editor/textcol_up.gif";
    <?php echo $f; ?>textcol3 = new Image(23,22); <?php echo $f; ?>textcol3.src = "html_editor/textcol_dn.gif";
    <?php echo $f; ?>backcol1 = new Image(23,22); <?php echo $f; ?>backcol1.src = "html_editor/backcol.gif";
    <?php echo $f; ?>backcol2 = new Image(23,22); <?php echo $f; ?>backcol2.src = "html_editor/backcol_up.gif";
    <?php echo $f; ?>backcol3 = new Image(23,22); <?php echo $f; ?>backcol3.src = "html_editor/backcol_dn.gif";
    <?php echo $f; ?>aleft1 = new Image(23,22); <?php echo $f; ?>aleft1.src = "html_editor/ialeft.gif";
    <?php echo $f; ?>aleft2 = new Image(23,22); <?php echo $f; ?>aleft2.src = "html_editor/ialeft_up.gif";
    <?php echo $f; ?>aleft3 = new Image(23,22); <?php echo $f; ?>aleft3.src = "html_editor/ialeft_dn.gif";
    <?php echo $f; ?>acenter1 = new Image(23,22); <?php echo $f; ?>acenter1.src = "html_editor/iacenter.gif";
    <?php echo $f; ?>acenter2 = new Image(23,22); <?php echo $f; ?>acenter2.src = "html_editor/iacenter_up.gif";
    <?php echo $f; ?>acenter3 = new Image(23,22); <?php echo $f; ?>acenter3.src = "html_editor/iacenter_dn.gif";
    <?php echo $f; ?>aright1 = new Image(23,22); <?php echo $f; ?>aright1.src = "html_editor/iaright.gif";
    <?php echo $f; ?>aright2 = new Image(23,22); <?php echo $f; ?>aright2.src = "html_editor/iaright_up.gif";
    <?php echo $f; ?>aright3 = new Image(23,22); <?php echo $f; ?>aright3.src = "html_editor/iaright_dn.gif";
    <?php echo $f; ?>numlist1 = new Image(23,22); <?php echo $f; ?>numlist1.src = "html_editor/inumlist.gif";
    <?php echo $f; ?>numlist2 = new Image(23,22); <?php echo $f; ?>numlist2.src = "html_editor/inumlist_up.gif";
    <?php echo $f; ?>numlist3 = new Image(23,22); <?php echo $f; ?>numlist3.src = "html_editor/inumlist_dn.gif";
    <?php echo $f; ?>blist1 = new Image(23,22); <?php echo $f; ?>blist1.src = "html_editor/iblist.gif";
    <?php echo $f; ?>blist2 = new Image(23,22); <?php echo $f; ?>blist2.src = "html_editor/iblist_up.gif";
    <?php echo $f; ?>blist3 = new Image(23,22); <?php echo $f; ?>blist3.src = "html_editor/iblist_dn.gif";
    <?php echo $f; ?>help1 = new Image(23,22); <?php echo $f; ?>help1.src = "html_editor/help.gif";
    <?php echo $f; ?>help2 = new Image(23,22); <?php echo $f; ?>help2.src = "html_editor/help_up.gif";
    <?php echo $f; ?>help3 = new Image(23,22); <?php echo $f; ?>help3.src = "html_editor/help_dn.gif";
    <?php echo $f; ?>clickme1 = new Image(25,17); <?php echo $f; ?>clickme1.src = "html_editor/spell.gif";
    <?php echo $f; ?>clickme2 = new Image(25,17); <?php echo $f; ?>clickme2.src = "html_editor/spell_up.gif";
    <?php echo $f; ?>clickme3 = new Image(25,17); <?php echo $f; ?>clickme3.src = "html_editor/spell_dn.gif";
    <?php echo $f; ?>indent1 = new Image(23,22); <?php echo $f; ?>indent1.src = "html_editor/indent.gif";
    <?php echo $f; ?>indent2 = new Image(23,22); <?php echo $f; ?>indent2.src = "html_editor/indent_up.gif";
    <?php echo $f; ?>indent3 = new Image(23,22); <?php echo $f; ?>indent3.src = "html_editor/indent_dn.gif";
    <?php echo $f; ?>outdent1 = new Image(23,22); <?php echo $f; ?>outdent1.src = "html_editor/outdent.gif";
    <?php echo $f; ?>outdent2 = new Image(23,22); <?php echo $f; ?>outdent2.src = "html_editor/outdent_up.gif";
    <?php echo $f; ?>outdent3 = new Image(23,22); <?php echo $f; ?>outdent3.src = "html_editor/outdent_dn.gif";
    <?php echo $f; ?>togglemd1 = new Image(22,22); <?php echo $f; ?>togglemd1.src = "html_editor/itogglemode.gif";
    <?php echo $f; ?>togglemd2 = new Image(22,22); <?php echo $f; ?>togglemd2.src = "html_editor/itogglemode_up.gif";
    <?php echo $f; ?>togglemd3 = new Image(22,22); <?php echo $f; ?>togglemd3.src = "html_editor/itogglemode_dn.gif";
    <?php echo $f; ?>preview1 = new Image(22,22); <?php echo $f; ?>preview1.src = "html_editor/preview.gif";
    <?php echo $f; ?>preview2 = new Image(22,22); <?php echo $f; ?>preview2.src = "html_editor/preview_up.gif";
    <?php echo $f; ?>preview3 = new Image(22,22); <?php echo $f; ?>preview3.src = "html_editor/preview_dn.gif";
    <?php echo $f; ?>letterbig1 = new Image(22,22); <?php echo $f; ?>letterbig1.src = "html_editor/iletterbig.gif";
    <?php echo $f; ?>letterbig2 = new Image(22,22); <?php echo $f; ?>letterbig2.src = "html_editor/iletterbig_up.gif";
    <?php echo $f; ?>letterbig3 = new Image(22,22); <?php echo $f; ?>letterbig3.src = "html_editor/iletterbig_dn.gif";
    <?php echo $f; ?>lettersm1 = new Image(22,22); <?php echo $f; ?>lettersm1.src = "html_editor/ilettersm.gif";
    <?php echo $f; ?>lettersm2 = new Image(22,22); <?php echo $f; ?>lettersm2.src = "html_editor/ilettersm_up.gif";
    <?php echo $f; ?>lettersm3 = new Image(22,22); <?php echo $f; ?>lettersm3.src = "html_editor/ilettersm_dn.gif";
    <?php echo $f; ?>spellcheck1 = new Image(23,22); <?php echo $f; ?>spellcheck1.src = "html_editor/spell.gif";
    <?php echo $f; ?>spellcheck2 = new Image(23,22); <?php echo $f; ?>spellcheck2.src = "html_editor/spell_up.gif";
    <?php echo $f; ?>spellcheck3 = new Image(23,22); <?php echo $f; ?>spellcheck3.src = "html_editor/spell_dn.gif";
    <?php echo $f; ?>link1 = new Image(23,22); <?php echo $f; ?>link1.src = "html_editor/ilink.gif";
    <?php echo $f; ?>link2 = new Image(23,22); <?php echo $f; ?>link2.src = "html_editor/ilink_up.gif";
    <?php echo $f; ?>link3 = new Image(23,22); <?php echo $f; ?>link3.src = "html_editor/ilink_dn.gif";
}

function <?php echo $f; ?>hiLite(imgName,imgObjName) {
    if (document.images) {
	document.images[imgName].src = eval(imgObjName + ".src");
    }
}

var bodyTag="<BODY bgcolor=#ffffff text=#000000>";
var bTextMode=false;

function <?php echo $f; ?>GetHtml() {
    if (bTextMode) {
	return <?php echo $f; ?>.document.body.innerText;
    }
    else {
	<?php echo $f; ?>cleanHtml();
	return <?php echo $f; ?>.document.body.innerHTML;
    }
}

function <?php echo $f; ?>SetHtml(sVal) {
    if (bTextMode) {
	<?php echo $f; ?>.document.body.innerText=sVal;
    } 
    else {
	<?php echo $f; ?>.document.body.style.fontSize='';
    }
}

<?php echo $f; ?>.document.open();
<?php echo $f; ?>.document.write(bodyTag);
<?php if($def) {
    $def=str_replace("\n",'',$def);
    $def=str_replace("\r",'',$def);
    $def=str_replace("&lt;",'<',$def);
    $def=str_replace("&gt;",'>',$def);
    $def=str_replace("&amp;",'&',$def);
    $def=str_replace("&quot;","'",$def);
    $def=str_replace("&#039;",'"',$def);
    echo "$f.document.write(\"$def\");";
}
?>

<?php echo $f; ?>.document.close();
<?php echo $f; ?>.document.designMode="On";

function <?php echo $f; ?>format(what,opt) {
    if (!<?php echo $f; ?>validateMode())
	return;
    if (opt==null)
	<?php echo $f; ?>.document.execCommand(what);
    else
	<?php echo $f; ?>.document.execCommand(what,"false",opt);
    <?php echo $f; ?>.focus();
}

function <?php echo $f; ?>setMode(newMode) {
    bTextMode = newMode;
    var cont;
    if (bTextMode) {
	<?php echo $f; ?>cleanHtml();
        cont=<?php echo $f; ?>.document.body.innerHTML;
	<?php echo $f; ?>.document.body.innerText=cont;
    }
    else {
        cont=<?php echo $f; ?>.document.body.innerText;
	<?php echo $f; ?>.document.body.innerHTML=cont;
    }
    <?php echo $f; ?>.focus();
}

// Sets the text color.
function <?php echo $f; ?>foreColor() {
    if (! <?php echo $f; ?>validateMode())
	return;
    var arr = showModalDialog("html_editor/SelColor.htm", "", "font-family:Verdana; font-size:12; dialogWidth:29em; help:no; status:no; dialogHeight:28em");
    if (arr != null)
	<?php echo $f; ?>format('foreColor', arr);
    else
	<?php echo $f; ?>.focus();
}

function <?php echo $f; ?>backColor() {
    if (!<?php echo $f; ?>validateMode())
	return;
    var arr = showModalDialog("html_editor/SelColor.htm", "", "font-family:Verdana; font-size:12; help:no; status:no; dialogWidth:29em; dialogHeight:28em");
    if (arr != null)
	<?php echo $f; ?>format('backColor', arr);
    else
	<?php echo $f; ?>.focus();
}

function <?php echo $f; ?>help() {
    var arr = showModalDialog("html_editor/help.htm", "", "help:no; status:no; dialogWidth:29em; dialogHeight:28em");
    if (arr != null)
	<?php echo $f; ?>format('help', arr);
    else
	<?php echo $f; ?>.focus();
}

function <?php echo $f; ?>cleanHtml() {
    var fonts = <?php echo $f; ?>.document.body.all.tags("FONT");
    var curr;
    for (var i = fonts.length - 1; i >= 0; i--) {
	curr = fonts[i];
	if (curr.style.backgroundColor == "#ffffff")
	    curr.outerHTML = curr.innerHTML;
    }
}

function <?php echo $f; ?>validateMode() {
    if (!bTextMode)
	return true;
    alert("Please uncheck the \"view source\" box to use this function.");
    <?php echo $f; ?>.focus();
    return false;
}

function <?php echo $f; ?>fpreview() {
    if (!<?php echo $f; ?>validateMode())
	return;
    var win=window.open("","","width=600,height=400,scrollbars=yes");
    win.document.write("<html><head><title>Preview</title></head>");
    win.document.write(<?php echo $f; ?>.document.body.outerHTML);
    win.document.write("</html>");
    win.document.close();
}

function <?php echo $f; ?>toggleMode() {
    var cont;
    if (!bTextMode) {
	<?php echo $f; ?>togglemd1.src="html_editor/itogglemode_dn.gif";
        <?php echo $f; ?>togglemd2.src="html_editor/itogglemode_dn.gif";
	bTextMode=true;
        <?php echo $f; ?>cleanHtml();
	cont=<?php echo $f; ?>.document.body.innerHTML;
        <?php echo $f; ?>.document.body.innerText=cont;
	<?php echo $f; ?>.document.body.style.fontFamily='Courier New';
        <?php echo $f; ?>.document.body.style.fontSize='10pt';
	<?php echo $f; ?>.document.body.style.background='#ffffff';
        <?php echo $f; ?>.document.body.style.color='#000000';
    }
    else {
	<?php echo $f; ?>togglemd1.src="html_editor/itogglemode.gif";
	<?php echo $f; ?>togglemd2.src="html_editor/itogglemode_up.gif";
	bTextMode=false;
	cont=<?php echo $f; ?>.document.body.innerText;
	<?php echo $f; ?>.document.body.innerHTML=cont;
	<?php echo $f; ?>.document.body.style.fontFamily='Georgia, Times New Roman, Times, serif';
	<?php echo $f; ?>.document.body.style.fontSize='12pt';
	<?php echo $f; ?>.document.body.style.background='#ffffff';
	<?php echo $f; ?>.document.body.style.color='#000000';
    }
    <?php echo $f; ?>hiLite('<?php echo $f; ?>togglemd','<?php echo $f; ?>togglemd1');
    <?php echo $f; ?>.focus();
}

function <?php echo $f; ?>sendtext() {
    var wboss_request;
    document.forms[0].<?php echo $f; ?>.value=<?php echo $f; ?>.document.body.innerHTML;
    var semi = new RegExp("\;","g");
    wboss_request='http://<?php echo $server_name2; ?>/cgi-bin/wboss.cgi?checkme=' + escape(document.forms[0].<?php echo $f; ?>.value.replace(semi,"\;"));
    wboss_request+='&form='+escape('forms[0]');
    wboss_request+='&field='+escape('<?php echo $f; ?>');
    wboss_request+='&spell=check&dirname=<?php echo dirname($PHP_SELF); ?>';
    window.open(wboss_request,'SpellChecker','width=480,height=370,top=150,left=150,scrollbars=1,location=true');
}
<?php
}
?>
