mapjs-pukiwiki-plugin
===========

This software is a Wiki plugin that allows you to place a Mindmap
editor (MapJs) on your Wiki page. A created Mindmap can be saved as a
part of the Wiki source of the Wiki page.

Dependencies
------------
* [Pukiwiki 1.4.6 or later](http://pukiwiki.sourceforge.jp/)
* [MapJs](https://github.com/mindmup/mapjs)

Install
-------
1. Set up MapJs. See [README.md](https://github.com/mindmup/mapjs/blob/master/README.md) and confirm that test/index.html works properly.
2. Copy files to Pukiwiki's plugin directory.
```
mkdir $PUKIWIKI_PLUGIN/mapjs
cp -r $MAPJS/src/ $MAPJS/lib/ $PUKIWIKI_PLUGIN/mapjs/
cp $MAPJS_PUKIWIKI_PLUGIN/src/*.php $PUKIWIKI_PLUGIN/
cp $MAPJS_PUKIWIKI_PLUGIN/src/*.js $PUKIWIKI_PLUGIN/mapjs/src/
```

Usage
-------
```
#teo_mapjs(size){{

	(more than one empty line)

}}
```

If you want to make a Mindmap frame whose size is 400pt, write the following code. Note that you can put only one teo_mapjs plugin on a page. The resulting mup code will be written in the empty line between "{{" and "}}".
```
    #teo_mapjs(400pt){{
    
    }}
```

Licence
-------
* GNU GPL v3 (*.php files)
* MIT (*.js)
