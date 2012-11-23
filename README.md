# Daptags Codeigniter tag cloud library

Daptags is a simple but powerful library generating tag clouds. You can put tags even after your article, and when setting an article item status to inactive, corresponding tags will be hidden, too. Hovering a tag in tag cloud a popup shows the count of articles they contain the tag. The tags have a separate table, where the corresponding article ids are stored in serialized arrays.

***

## Dependencies

Strict dependency: [__Base_Model__](https://github.com/jamierumbelow/codeigniter-base-model) by Jamie Rumbelow  
You have to install it to use this library!

Optional dependency: [__Cache__](http://getsparks.org/packages/cache) Spark by Phil Sturgeon  
If you want a simple file based cache then use this library.

## Installation

* Copy the necessary __config__, __library__ and __model__ files into the same folders on your machine.  
Example controller and views doesn't have to copied, they are only theoretical "visual" examples how can you use this library, and integrate into your system. This readme uses the example controller for demonstration purposes.

* Insert the __daptags.sql__ into your database

* Use css styles in your stylesheet (in __tags_index.php__ _view_) for setting the tag elements size

## Setup

Edit the __daptags.php__ _config_ file.

* $limit - limit the number of tags in tag cloud  
* $divider - html tag(s) for dividing tags  
* $tags_order - ordering tags aplphabetically ascending or descending  
* $tag_container - class name of the tag cloud containing DIV  
* $cache - Enable or disable file cache  
* $cache_ttl - Cache lifetime settings in ms

## Usage

### example/index()

Generates the tag cloud and shows it in the view.

![Tag cloud](http://devartpro.com/assets/daptags01.jpg "Tag cloud")

### example/article($id)

Loads an article with the corresponding tags with it

![Tags after article](http://devartpro.com/assets/daptags02.jpg "Tags after article")

### example/tag($tag)

Displays the articles after clicking a tag in tag cloud

![Articles by tag](http://devartpro.com/assets/daptags03.jpg "Articles by tag")  

_Note: the library uses shortened uri like /tag, you have to create a route in the routes.php._

### example/add()

CRUD - Create method, for saving tags

### example/edit($id)

CRUD - Update method, for editing tags (remove if deleted, add if new entered)  
$id - id of the article

### example/status($id)

Setting the status of article makes it's tags hide or show  
$id - id of the article

### example/delete($id)

Delete all tags which the deleted article contained.  
$id - id of the article

## Caching

Using cache you can speed up tag cloud. Enable it in the config file after you installed Cache spark.
You can also set the TTL value of the cache.

## License 

[MIT License](http://www.opensource.org/licenses/MIT)

## Author

C. 2012 Barna Szalai <b.sz@devartpro.com>

Feel free to contant me if you have any questions!



