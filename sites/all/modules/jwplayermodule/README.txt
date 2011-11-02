
#############################################
# JW PLAYER MODULE                          #
#############################################

This module is provided by LongTail Video Inc.  It enables you to configure and embed the JW Player for Flash for use
on your Drupal website.  Full support for plugins, playlists, and internal and external media is provided.

For more information about the JW Player for Flash please visit http://www.longtailvideo.com.

Note that to use the LongTail Ad Solution you will need to apply on the LongTail site.

#############################################
# REQUIREMENTS                              #
#############################################

This module requires the jquery-ui module. (http://drupal.org/project/jquery_ui)

Iif you would like your player to play content stored on a node actviate the Drupal core Upload module.

#############################################
# INSTALLATION                              #
#############################################

Place the Module folder in your modules directory.

Download the non-commercial JW Player™ at http://www.longtailvideo.com/players/jw-flv-player/.

Extract the contents of the zip file.

Navigate to Administer > Site Building > Modules.

Check the enabled checkbox next to the Module's name.

Click on Save configuration.

Once the module is activated it will notify you that the JW Player needs to be installed.
Navigate to Administer > Site Configuration > JW Player setup > Upgrade.  Click "Instal Latest JW Player"  This will
download the player and install it.  The player is downloaded to (/sites/default/files/jwplayermodule/player/)

#############################################
# USAGE                                     #
#############################################

Create or edit a node.

Insert the following tag: [jwplayer|config=<Player name>|file=<your video] into the body.

<your video> can either be an URL or the description of a file you have uploaded to the node.

Save your node.

For more advanced and detailed description of the module please refer to the documentation located
on our support site.