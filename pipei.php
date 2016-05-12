<?php

$preg='/<a .*?href="(.*?)".*?>/is';
$str ='<a href="链接">123</a><a href="链接" target="_blank">345</a><a  target="_blank" href="链接">678</a>';
preg_match($preg,$str,$match);
var_dump($match);

$str = "ss000asdf{|22|}999";

// $pattern = '/\{\|([0-9]*)\|\}/isU';
$pattern = '/\|([0-9]+)/';
$str = 'pp44asd阿斯顿发射点f{|99f|}asd00a{|555|}sas阿斯蒂芬';

preg_match_all( $pattern, $str,$res );

print_r($res);


 ?>
 <!doctype html>
 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<title>Document</title>
 </head>
 <body>
 	<div id="res"></div>
 </body>
<script>
	var pattern = /<img(.*)src=\"([^\"]+)\">$/i;
	// var pattern = /<img(.*) src=(.*)>/;
	var str = '<img class="em" src="/chat/dd.jpg">asdfads<img class="rr" src="asdf">';
	console.log(str.replace(pattern, 22));
	// document.getElementById('res').value = str.match(pattern);
</script>

 </html>

