<?php

require("lib/config.php");


// Create c9 user
`groupadd $conf_c9_group`;
`useradd $conf_c9_user -g $conf_c9_group -m`;
`mkdir /home/$conf_c9_user/workspace`;
`chown $conf_c9_user:$conf_c9_group /home/$conf_c9_user/workspace`;


// PERMISSIONS SETUP

// Create some directories (git doesn't permit empty directories)
$directories=array("log", "watch", "data", "c9fork", "last", "web/buildservice");
foreach($directories as $dir) {
	`mkdir $conf_base_path/$dir`;
	`chmod 755 $conf_base_path/$dir`;
}

// Directories and files that should be writable by user processes
$user_writable=array("log", "watch", "last");
foreach($user_writable as $path) {
	`chgrp $conf_c9_group $conf_base_path/$path`;
	`chmod 775 $conf_base_path/$path`;
}

// Directories and files that should be writable from web
$web_writable = array("register", "log/admin.php.log", "log/autotest.log");
foreach($web_readable as $path) {
	if (!file_exists("$conf_base_path/$path")) `touch $conf_base_path/$path`;
	`chown www-data $conf_base_path/$path`;
	`chmod 755 $conf_base_path/$path`;
}

// Directories and files that should be readable from web but not by users
$web_readable = array("users");
foreach($web_readable as $path) {
	if (!file_exists("$conf_base_path/$path")) `touch $conf_base_path/$path`;
	`chgrp www-data $conf_base_path/$path`;
	`chmod 750 $conf_base_path/$path`;
}


// INSTALLATION

// Install Cloud9
echo Downloading Cloud9 IDE
`git clone $cloud9_git_url $conf_base_path/c9fork`;
echo Installing Cloud9 IDE
`cd $conf_base_path/c9fork; ./scripts/install_sdk.sh`;
`chmod 755 $conf_base_path/c9fork -R`;
`cd /home/$conf_c9_user; ln -s $conf_base_path/c9fork fork`;

// Do we need this?
`chmod 644 $conf_base_path/c9fork/build/standalone/skin/default/*`;

// Populate "static" folder with symlinks
`ln -s $conf_base_path/c9fork/node_modules/architect-build/build_support/mini_require.js $conf_base_path/web/static/mini_require.js`;
`ln -s $conf_base_path/c9fork/plugins/c9.nodeapi/events.js $conf_base_path/web/static/lib/events.js`;
`ln -s $conf_base_path/c9fork/node_modules/architect $conf_base_path/web/static/lib/architect`;
`ln -s $conf_base_path/c9fork/plugins $conf_base_path/web/static/plugins`;

// Install Buildservice
echo Downloading Buildservice
`git clone $buildservice_git_url $conf_base_path/web/buildservice`;
`cp $conf_base_path/web/buildservice.c9/* $conf_base_path/web/buildservice`;
`rm -fr $conf_base_path/web/buildservice.c9`;

// Prepare SVN path
`mkdir $conf_svn_path`;
`chown $conf_c9_user:$conf_c9_group $conf_svn_path`;

// Allow web scripts to use sudo to execute system-level webide commands
`echo >> /etc/sudoers`;
`echo >> /etc/sudoers`;
`echo Cmnd_Alias WEBIDECTL=$conf_base_path/bin/webidectl >> /etc/sudoers`;
`echo Cmnd_Alias USERSTATS=$conf_base_path/bin/userstats >> /etc/sudoers`;
`echo Cmnd_Alias WSACCESS=$conf_base_path/bin/wsaccess >> /etc/sudoers`;
`echo >> /etc/sudoers`;
`echo www-data ALL=NOPASSWD: WEBIDECTL >> /etc/sudoers`;
`echo www-data ALL=NOPASSWD: USERSTATS >> /etc/sudoers`;
`echo www-data ALL=NOPASSWD: WSACCESS >> /etc/sudoers`;


?>