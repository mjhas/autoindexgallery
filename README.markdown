# autoindexgallery #

This is a angular.js/bootstrap enhancement for mod_autoindex of apache. It provides an image gallery for apache mod_autoindex.

Apache Configuration:
=============

  1. Copy the gallerylib folder to your document root to make it accessible.
  2. Enhance your Apache Configuration with the following

```apache
    IndexOptions +Charset=UTF-8 -FancyIndexing +SuppressHTMLPreamble -HTMLTable +SuppressLastModified +SuppressDescription +SuppressColumnSorting
    HeaderName /gallerylib/header.html
    ReadmeName /gallerylib/footer.html

    <Location /gallerylib/>
      Options -Indexes
    </Location>

    #optional configuration for generating thumbnails
    RewriteEngine on
    RewriteCond %{REQUEST_URI} ^/gallery(.*)
    RewriteCond %{QUERY_STRING} ^small
    RewriteRule ^(.*)$ \/gallerylib/thumb.php%{REQUEST_URI}
```


Example
=============
https://haslgruebler.eu/gallery/
