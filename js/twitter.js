/**
Copyright (c) 2008, Michele Costantino Soccio. All rights reserved.
Copyright (c) 2008, Alan Hogan. All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/**
First attempts to make link cliccables by Michele C. Soccio, http://www.soccio.it/michelinux/
Thanks to excellent work by Alan Hogan (http://alanhogan.com/contact) for making it much 
better and make @user link cliccable as well.

You can hotlink this script from http://www.soccio.it/code/twitter/blogger.js
For any comments or suggestions: 
http://www.soccio.it/michelinux/2007/11/09/clickable-links-in-twitter-htmljs-badge/
*/

function twitterCallback2(obj) {
	var wwwregular = /\bwww\.\w.\w/ig;
	var regular = /((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g;
	var atregular = /\B@([_a-z0-9]+)/ig;
	var twitters = obj;
	var statusHTML = "";
	var username = "";

	for (var i=0; i<twitters.length; i++){
		var posttext = "";
		posttext = twitters[i].text.replace(wwwregular, 'http://$&');
		posttext = posttext.replace(regular, '<a href="$1">$1</a>');
		posttext = posttext.replace(atregular, '@<a href="http://twitter.com/$1">$1</a>');
		
		username = twitters[i].user.screen_name
		statusHTML += ('<li><span>'+posttext+'</span> <a style="font-size:85%" href="http://twitter.com/'+username+'/statuses/'+twitters[i].id+'" title="Tweet Permalink">'+relative_time(twitters[i].created_at)+'</a></li>')
	}
	document.getElementById('twitter_update_list').innerHTML = statusHTML;
}

function relative_time(time_value) {
  var values = time_value.split(" ");
  time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
  var parsed_date = Date.parse(time_value);
  var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
  var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
  delta = delta + (relative_to.getTimezoneOffset() * 60);

  if (delta < 60) {
    return 'less than a minute ago';
  } else if(delta < 120) {
    return 'about a minute ago';
  } else if(delta < (60*60)) {
    return (parseInt(delta / 60)).toString() + ' minutes ago';
  } else if(delta < (120*60)) {
    return 'about an hour ago';
  } else if(delta < (24*60*60)) {
    return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
  } else if(delta < (48*60*60)) {
    return '1 day ago';
  } else {
    return (parseInt(delta / 86400)).toString() + ' days ago';
  }
}
