Notes go here: https://docs.google.com/document/d/1bJNHSiMDJb5ReWBVeW3E94ioN7NfD5-fgbiMBsbKEOs/edit?hl=en_US

20111009: 
v1.0 created an alternative to Private and Public.  Imagecache depends on knowing which of Private and Public you are using.  Problems in imagecache can be pretty quickly hacked around once realize this.  Long term: I’m not sure there’s really a point to not using a slightly modified Public or Private, is there?  If not, uncomplexify and redrupalify....

big problems with imagecache: it’s not the file structure or .htaccess, but that neither PRIVATE nor PUBLIC is used.  See especially the change in imagecache.module  imagecache_create_url
