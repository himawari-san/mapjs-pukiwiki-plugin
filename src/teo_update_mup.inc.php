<?php
/*
    Copyright (C) 2014 Masaya YAMAGUCHI

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

define(PLUGIN_OPEN_REGEXP, "/^#teo_mapjs\(.*?\)\s*\{\{/u");
define(PLUGIN_CLOSE_REGEXP, "/^\}\}/u");


function plugin_teo_update_mup_init() {
}

function plugin_teo_update_mup_action() {
  global $post, $vars;

  $new_wiki_source = "";

  // current mup data
  $new_mup = $post["pagecontent"];
  // unescape the mup escaped by Javascript
  $new_mup = preg_replace('~\%u([0-9a-f]+)~ei','i3u(hexdec("\\1"))', $new_mup);
  // replace "}" with "}\n" if "}" is not followed by "\n"
  $new_mup = preg_replace("/\}(?!\n)/", "}\n", $new_mup);

  // mapjs_id (not used yet)
  $mapjs_id = 0;

  // wiki source
  $wiki_source = get_source($vars['refer'], TRUE);


  $c_plugin = 0;
  $flag_target = 0;
  
  foreach($wiki_source as $line){
    if(preg_match(PLUGIN_OPEN_REGEXP, $line, $matches)){
      if($c_plugin == $mapjs_id){
	$flag_target = 1;
      }
      $c_plugin++;
    } else if($flag_target == 1){
      if(preg_match(PLUGIN_CLOSE_REGEXP, $line)){
	$new_wiki_source .= $new_mup;
	$flag_target = 0;
      } else {
	continue;
      }
    }
    $new_wiki_source .= $line;
  }
  
  // write $new_wiki_source to the server
  page_write($vars['refer'], $new_wiki_source);

}

// i3u was posted to http://php.mirror.camelnetwork.com/manual/ja/function.utf8-encode.php
// by emze
function i3u($i) { // returns UCS-16 or UCS-32 to UTF-8 from an integer
  $i=(int)$i; // integer?
  if ($i<0) return false; // positive?
  if ($i<=0x7f) return chr($i); // range 0
  if (($i & 0x7fffffff) <> $i) return '?'; // 31 bit?
  if ($i<=0x7ff) return chr(0xc0 | ($i >> 6)) . chr(0x80 | ($i & 0x3f));
  if ($i<=0xffff) return chr(0xe0 | ($i >> 12)) . chr(0x80 | ($i >> 6) & 0x3f)
      . chr(0x80  | $i & 0x3f);
  if ($i<=0x1fffff) return chr(0xf0 | ($i >> 18)) . chr(0x80 | ($i >> 12) & 0x3f)
      . chr(0x80 | ($i >> 6) & 0x3f) . chr(0x80  | $i & 0x3f);
  if ($i<=0x3ffffff) return chr(0xf8 | ($i >> 24)) . chr(0x80 | ($i >> 18) & 0x3f)
      . chr(0x80 | ($i >> 12) & 0x3f) . chr(0x80 | ($i >> 6) & 0x3f) . chr(0x80  | $i & 0x3f);
  return chr(0xfc | ($i >> 30)) . chr(0x80 | ($i >> 24) & 0x3f) . chr(0x80 | ($i >> 18) & 0x3f)
      . chr(0x80 | ($i >> 12) & 0x3f) . chr(0x80 | ($i >> 6) & 0x3f) . chr(0x80  | $i & 0x3f);
}

?>
