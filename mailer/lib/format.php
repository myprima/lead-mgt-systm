<?php
/*
 * Formatting library
 *
 * Copyright (C) 2001 Max Rudensky		  <fonin@ziet.zhitomir.ua>
 * Copyright (C) 1999,2000 Alex Rozhik		  <rozhik@ziet.zhitomir.ua>
 * All right reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this software
 *    must display the following acknowledgement:
 * This product includes software developed by the Max Rudensky
 * and its contributors.
 * 4. Neither the name of the author nor the names of its contributors
 *    may be used to endorse or promote products derived from this software
 *    without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 *
 * $Id: format.php,v 1.2 2005/04/19 12:07:28 dmitry Exp $
 */

function FormatArray( $format, $array ) {
    if( is_array( $array ) ) {
	while ( list( $key, $val ) = each( $array ) ) {
		if((empty($val) || $val=="&nbsp;") && (basename($_SERVER['PHP_SELF'])=='survey_summary.php' || basename($_SERVER['PHP_SELF'])=='survey_detailed.php')){
			$val = 'Did not answer question';
		}
	    $format=ereg_replace( "\\$\\($key\\)",$val,$format);
	}
    }
    return $format;
}    


function FormatVar( $format, $key, $value ) {
    return ereg_replace( "\\$\\($key\\)",$value,$format);
}    


function FormatRemove( $format ) {
    return preg_replace( "/(\\$\\(\\w+\\))/"," ",$format);
}    

function FormatRemove2( $format ) {
    return ereg_replace( "(\\\$\\\(.+?\\\))"," ",$format);
}    


function SQLFormat( $query, $format ) {
    $h=SQLQuery( $query );
    $r=SQLFetchRow( $h );
    $ret=FormatArray( $format, $r );
    SQLFree( $h );
    return $ret;
}


function FormatDBH( $h, $format="" ) {
    global $format_color1, $format_color2;

    if(!isset($format_color1)) {
	$format_color1='#ffffff';
    }
    if(!isset($format_color2)) {
	$format_color2='#e9e9e9';
    }
    $i=1;
    $res="";
    while( $row=SQLFetchRow($h) ) {
	$f=FormatArray( $format, $row );
	$f=FormatVar( $f,"\\#",$i);
	$f=FormatVar( $f,"bgcolor",$i);
	if(( $i %2) != 0 ) {
	    $f=eregi_replace("\\$\\(:tr\\)","\n<tr>",$f);
	    $f=eregi_replace("\\$\\(tr:\\)","\n",$f);
	    $f=eregi_replace("\\$\\(:\\)"," bgcolor=$format_color1 ",$f);
	}
	else {
	    $f=eregi_replace("\\$\\(:tr\\)","\n",$f);
	    $f=eregi_replace("\\$\\(:\\)"," bgcolor=$format_color2 ",$f);
	    $f=eregi_replace("\\$\\(tr:\\)","\n</tr>",$f);
	}
	$res.=$f; $i++;
    }

    return $res;
}    


function FormatDBQ( $query,$format ) {
    global $format_color1, $format_color2;

    $h=SQLQuery( $query );
    $res=FormatDBH( $h, $format );
    SQLFree( $h );
    return $res;
}

?>
