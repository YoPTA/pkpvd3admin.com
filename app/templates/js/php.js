function chr(ascii) 
{
	return String.fromCharCode(ascii);
}

function explode( delimiter, string ) 
{
	var emptyArray = { 0: '' };

	if ( arguments.length != 2
		|| typeof arguments[0] == 'undefined'
		|| typeof arguments[1] == 'undefined' )
	{
		return null;
	}

	if ( delimiter === ''
		|| delimiter === false
		|| delimiter === null )
	{
		return false;
	}

	if ( typeof delimiter == 'function'
		|| typeof delimiter == 'object'
		|| typeof string == 'function'
		|| typeof string == 'object' )
	{
		return emptyArray;
	}

	if ( delimiter === true ) {
		delimiter = '1';
	}

	return string.toString().split ( delimiter.toString() );
}

function strpos(haystack, needle, offset)
{
	var i = haystack.indexOf( needle, offset );
	return i >= 0 ? i : false;
}

function substr(str, index, len)
{
	return str.substr(index, len);
}

function preg_match(regexp, str)
{
	var rexp = new RegExp(substr(regexp, 1, regexp.length - 2));
	return rexp.test(str);
}

function base_convert(number, frombase, tobase) 
{
	return parseInt(number + '', frombase | 0).toString(tobase | 0);
}

function file_basename(str) 
{
	return str.split('\\').pop().split('/').pop();
}

function file_extractfilename(value) 
{
	var result = value;
	
	value = result.split('.');
	
	for (var i = 0; i < value.length - 1; i++)
	{
		if (i == 0)
		{
			result = '';
		}
		if (result != '')
		{
			result = result + '.';
		}
		result = result + value[i];
	}
	
	return result;
}

function str_replace (search, replace, subject ) 
{
	if(!(replace instanceof Array)){
		replace=new Array(replace);
		if(search instanceof Array){
			while(search.length>replace.length){
				replace[replace.length]=replace[0];
			}
		}
	}
	if(!(search instanceof Array))search=new Array(search);
	while(search.length>replace.length){
		replace[replace.length]='';
	}
	if(subject instanceof Array){
		for(k in subject){
			subject[k]=str_replace(search,replace,subject[k]);
		}
		return subject;
	}
	for(var k=0; k<search.length; k++){
		var i = subject.indexOf(search[k]);
		while(i>-1){
			subject = subject.replace(search[k], replace[k]);
			i = subject.indexOf(search[k],i);
		}
	}
	return subject;
}

function strlen(str) 
{
	return str.length;
}

function strtolower(str) 
{
	return str.toLowerCase();
}

function strtoupper(str) 
{
	return str.toUpperCase();
}

function strtr(str, from, to) 
{
    if (typeof from === 'object') {
    	var cmpStr = '';
    	for (var j=0; j < str.length; j++){
    		cmpStr += '0';
    	}
    	var offset = 0;
    	var find = -1;
    	var addStr = '';
        for (fr in from) {
        	offset = 0;
        	while ((find = str.indexOf(fr, offset)) != -1){
				if (parseInt(cmpStr.substr(find, fr.length)) != 0){
					offset = find + 1;
					continue;
				}
				for (var k =0 ; k < from[fr].length; k++){
					addStr += '1';
				}
				cmpStr = cmpStr.substr(0, find) + addStr + cmpStr.substr(find + fr.length, cmpStr.length - (find + fr.length));
				str = str.substr(0, find) + from[fr] + str.substr(find + fr.length, str.length - (find + fr.length));
				offset = find + from[fr].length + 1;
				addStr = '';
        	}
        }
        return str;
    }

	for(var i = 0; i < from.length; i++) {
		str = str.replace(new RegExp(from.charAt(i),'g'), to.charAt(i));
	}

    return str;
}

function is_numeric(s) 
{
	return (!isNaN(s)) ? true : false;
}

function is_numericaff(s, notnull) 
{
	notnull = notnull || false;
	
	if (!is_numeric(s))
	{
		return false;
	}
	
	return (notnull) ? ((parseInt(s) > 0) ? true : false) : ((parseInt(s) >= 0) ? true : false);
}

function ltrim(s) 
{
	var ptrn = /\s*((\S+\s*)*)/;
	return s.replace(ptrn, "$1");
}

function rtrim(s) 
{
	var ptrn = /((\s*\S+)*)\s*/;
	return s.replace(ptrn, "$1");
}

function trim(s) 
{
	return ltrim(rtrim(s));
}

function rand2(min, max)
{
    var range = max - min + 1;
    var n = Math.floor(Math.random() * range) + min;
    return n;
}

function is_array(input)
{
    return typeof(input)=='object'&&(input instanceof Array);
}

function str_pad( input, pad_length, pad_string, pad_type ) {	// Pad a string to a certain length with another string
	// 
	// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// + namespaced by: Michael White (http://crestidg.com)

	var half = '', pad_to_go;

	var str_pad_repeater = function(s, len){
			var collect = '', i;

			while(collect.length < len) collect += s;
			collect = collect.substr(0,len);

			return collect;
		};

	if (pad_type != 'STR_PAD_LEFT' && pad_type != 'STR_PAD_RIGHT' && pad_type != 'STR_PAD_BOTH') { pad_type = 'STR_PAD_RIGHT'; }
	if ((pad_to_go = pad_length - input.length) > 0) {
		if (pad_type == 'STR_PAD_LEFT') { input = str_pad_repeater(pad_string, pad_to_go) + input; }
		else if (pad_type == 'STR_PAD_RIGHT') { input = input + str_pad_repeater(pad_string, pad_to_go); }
		else if (pad_type == 'STR_PAD_BOTH') {
			half = str_pad_repeater(pad_string, Math.ceil(pad_to_go/2));
			input = half + input + half;
			input = input.substr(0, pad_length);
		}
	}

	return input;
}

function preg_quote(str, delimiter) 
{
  //  discuss at: http://phpjs.org/functions/preg_quote/
  // original by: booeyOH
  // improved by: Ates Goral (http://magnetiq.com)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: Onno Marsman
  //   example 1: preg_quote("$40");
  //   returns 1: '\\$40'
  //   example 2: preg_quote("*RRRING* Hello?");
  //   returns 2: '\\*RRRING\\* Hello\\?'
  //   example 3: preg_quote("\\.+*?[^]$(){}=!<>|:");
  //   returns 3: '\\\\\\.\\+\\*\\?\\[\\^\\]\\$\\(\\)\\{\\}\\=\\!\\<\\>\\|\\:'

  return String(str)
    .replace(new RegExp('[.\\\\+*?\\[\\^\\]$(){}=!<>|:\\' + (delimiter || '') + '-]', 'g'), '\\$&');
}

var b36arr=['0','1','2','3','4','5','6','7','8','9',
            'a','b','c','d','e','f','g','h','i','j',
            'k','l','m','n','o','p','q','r','s','t',
            'u','v','w','x','y','z'];
function base_convert(num,frombase,tobase){
  var str=num.toString();
  var len=str.length;
  var p=1;
  var b10=0;
  for(i=len;i>0;i--){
    b=str.charCodeAt(i-1);
    c=str.charAt(i);
    if(b>=48 && b<=57){
      b=b-48;
    }else if(b>=97 && b<=122){
      b=b-97+10;
    }else if(b>=65 && b<=90){
      b=b-65+10;
    }
    b10=b10+b*p;
    p=p*frombase;
  }
  var newval='';
  var ost=0;
  while(b10>0){
    ost=b10%tobase;
    b10=Math.floor(b10/tobase);
    newval=b36arr[ost]+''+newval;
  }
  return newval;
}

var Base64 = 
{
	// private property
	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

	// public method for encoding
	encode : function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;

		input = Base64._utf8_encode(input);

		while (i < input.length) {

			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);

			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;

			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}

			output = output +
			this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
			this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

		}

		return output;
	},

	// public method for decoding
	decode : function (input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;

		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

		while (i < input.length) {

			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));

			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;

			output = output + String.fromCharCode(chr1);

			if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			}

		}
		
		output = Base64._utf8_decode(output);

		return output;

	},

	// private method for UTF-8 encoding
	_utf8_encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";

		for (var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}

		}

		return utftext;
	},

	// private method for UTF-8 decoding
	_utf8_decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;

		while ( i < utftext.length ) {

			c = utftext.charCodeAt(i);

			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}

		}

		return string;
	}
}

function base64_encode( data ) 
{	
	return Base64.encode(data);
}

function base64_decode( data ) 
{
	return Base64.decode(data);
}