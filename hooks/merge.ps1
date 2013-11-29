#!/bin/sh
# Use for Linux Bash & Windows Power Shell Script 1.0

# ASIKART Joomla! Extension Packager: merge script.
# Copyright (c) 2013 Asikart.com. All rights reserved.
# 
# When pulled, execute this script to copy files into your joomla site.

$AMP_PATH="D:/www"

$SITE="site_name"
$COM="flower"
$MOD="flower"
$PLG="flower"
$GUP="system"

# admin
cp -Force -r admin/* $AMP_PATH/$SITE/administrator/components/com_$COM
echo "Admin copied" ;

# site
# cp -Force -r site/* $AMP_PATH/$SITE/components/com_$COM
# echo "Site copied" ;

# library
cp -Force -r admin/windwalker/* $AMP_PATH/$SITE/libraries/windwalker
echo "Lib copied" ;

# modules site
# cp -Force -r modules/mod_$MOD/* $AMP_PATH/$SITE/modules/mod_$MOD
# echo "Module copied" ;

# modules admin
# cp -Force -r modules/mod_$MOD/* $AMP_PATH/$SITE/administrator/modules/mod_$MOD
# echo "Module copied" ;

# plugins
cp -Force -r plugins/plg_$GUP"_"$PLG/* $AMP_PATH/$SITE/plugins/$GUP/$PLG
echo "Plugin copied" ;


exit 0
