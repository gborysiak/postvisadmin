PostVis Admin - README.txt
Version 1.3
Written by Roger_Smith(rogersmith@lazytechs.com)
-------------------------------------------------------------
PostVis Admin is an Easy to use admin interface for Postfix and Amavis-New.  All settings are stored in SQL and are editable via a web interface.  After using many other Postfix Admin tools, I finally decided that I just wanted one tool for everything and started writing PostVis Admin.  I am no where near a PHP expert, so I expect the script can be optimized greatly, which I will be working doing in future release.

Requirements:
- Apache 1.3 or higher
- PHP 4 
- MySQL 4.1.3 or higher
- PostFix 2.2.x or higher w/ MySQL support
- Amavis-New w/ MySQL support
- Spamassassin 3.x
- Virus Scanner - Optional - (ClamAV Recommended, but many scanners work with Amavis-New)

Recommended Software:
- phpMyAdmin (Provides easy access to MySQL for quick editing of databases)
- ClamAV (Free Virus Scanning daemon)

Features Include:

- SuperAdmin to manage server wide settings
- Per domain Admin editing for
- Amavis-New Policy creation and editing
- Per user assignment of Amavis-New filtering policy
- Mail History Viewing, AVG Spam scores per domain tracking
- Easy Database Cleanup and pruning

Known Limitations:
- Spam Only can be released by Domain Admins or SuperAdmins at this time. User interface planned for future release.

Change Log:
Check http://postvisadmin.sourceforge.net/ for further information.

-------------------------------------------------------------

For Installation Documentation please view the INSTALL.txt

Support/Questions/FAQ
----------------------------------------------------------
For questions or support issues please post in the forums on our sourceforge.net project page:

http://postvisadmin.sourceforge.net/

You can also email me at rogersmith@lazytechs.com and I will respond as soon as possible.

FAQ's
----------------------------------------------------------
Q) I do not have a need for the Amavis-New 

A) PostVis Admin is designed to be used with Amavis-New as well as an interface to Postfix. 
PostVis Admin can be used in this scenario, but you might what to check out PostFix Admin.

Q) I'm getting <insert error here> when I try to do <insert various task>.

A) I can not guarantee that this will work on every system out there.  If you email me details I will work to get it
functional for you.

More to come, please send feature request or any recommendation to the forums at the project page. 