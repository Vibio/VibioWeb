# tiny perl script by cataldo

print "
running eat_untar to untar the downloaded files and put them in old/

";
opendir ( DIR, ".");

#@files = grep { /\.tar\.gz$/ } readdir(DIR);
@files = grep { /\.tar\.gz$/ } readdir(DIR);
#die "done runnig;";

foreach ( @files ) {
  #print $_;
  `tar -zxf $_`;
  `mv $_ old/`;
}

print "done\n\n";



