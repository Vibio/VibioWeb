These modules were either:
a) in sites/all/modules on live site, and NOT in GitHub, and I don't know
what they're for and it isn't time to explore but I think they're for
various statistics that migth be superceded by Google Analytics, or
developer activities.  I think it's backwards: these should be on dev
and NOT on live.  
b) developer tools (backup_and_migrate) that don't really belong (enabled)
on live.  Also simple_menu.  This should pose no problem: enable in
dev database, not live.  (and not disasterous to screw up) 
