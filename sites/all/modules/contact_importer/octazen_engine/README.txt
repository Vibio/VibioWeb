$Id: README.txt,v 1.1.4.5 2010/05/03 00:43:53 dalin Exp $

OCTAZEN ENGINE
================

The Octazen Engine provides integration with Contact Importer for the Octazen retrieval service.
Note that there is a fee for the library.

NOTE: Octazen appears to have been acquired by Lunar Systems.  This engine will probably work with
the Lunarsys Contact Importer (perhaps with just a few minor tweaks):
http://nepal.lunarsys.com/12-ls-contact-importer.html
The module will continue to work for existing Octaen customers, but new installations will need 
to tweak the code to work with Lunar, or just use a different engine.

Once you have the library place it into the sites/all/libraries folder.  Your directory structure
will look like the following:

sites
| + all
| | + libraries
| | | + abimporter
| | | | + lib
| | | | | + [several php files]
| | | | + abi.php
| | | | + abiconfig.php
| | | | + index.html

Octazen distributes its library with a fairly useless Drupal module.  Do not let this confuse you.
You can delete these other files.

