#!/usr/bin/perl
#
#   clamdwatch v0.7.3, Copyright (C) Mike Cathey
#
#   ChangeLog
#
#   v0.7.3 - 04/15/2008 - Changed RAWSCAN to SCAN for the 0.93 upgrade.
#
#   v0.7.2 - 12/11/2007 - Changed default path to clamd socket.
#
#   v0.7.1 - 1/30/2005 - Fixed ownership issue with the temporary file.
#
#   v0.7 - 1/27/2005 - Fixed typo (-l != -L)
#
#   v0.7rc1 - 12/09/2004 - replaced eicar with the reverse()
#   			- a temp file is scanned instead of the script itself
#   			- now uses getopt to parse cmd line options
#   			- (-s /path/to/socket || port #) added switch to
#                          specify the socket path
#   			- (-t seconds) added switch to specify timeout
#   			- (-l) added logging function that can use syslog
#   			- (-h) added help switch
#   			- (-L /path/to/lockfile) now checks for the
#   			   lockfile to see if clamd was administratively
#   			   shutdown
#
#   v0.7test1 - 01/22/2004 - added support for clamd on a TCP socket
#
#   v0.6 - 01/09/2004 - changed exit codes so that it can be used in
#       cron with && (as shown in the INSTALL file)
#                      - added -q switch for same reason as above
#
#   v0.5 - 01/09/2004 - another attempt at fixing the path finding code
#
#   v0.4 - 01/08/2004 - fixed the code that finds the path of the script
#
#   v0.3 - 01/07/2004 - added a timeout for the result from the RAWSCAN
#
#   v0.2 - 01/07/2004 - added the Eicar signature and the code to have
#       clamd scan the script itself
#
#   v0.1 - 01/07/2004 - initial hack
#
#   Use this at your own risk!
#
#   This program is free software; you can redistribute it and/or modify
#   it under the terms of the GNU General Public License as published by
#   the Free Software Foundation; either version 2 of the License, or
#   (at your option) any later version.
#
#   This program is distributed in the hope that it will be useful,
#   but WITHOUT ANY WARRANTY; without even the implied warranty of
#   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with this program; if not, write to the Free Software
#   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#

use IO::Socket::UNIX;
use Getopt::Std;
use Sys::Syslog;
use File::Temp  qw/ :mktemp /;

my %options;
getopts('hqs:lL:t:', \%options);

if ( $options{h} ) {
    print("\n");
    print("\n");
    print("Synopsis:\n");
    print("  clamdwatch.pl [ -s socket ] [ -l ] [-q] [ -t timeout ] [ -L lockfile ] | -h \n");
    print("\n");
    print("  Examples:\n");
    print("\n");
    print("    clamdwatch.pl -s /tmp/clamd.socket -l -q -t 25 -L /var/lock/subsys/clamd\n");
    print("    clamdwatch.pl -s 3310 -l -q -t 25 -L /var/lock/subsys/clamd\n");
    print("\n");
    print("Switches:\n");
    print("\n");
    print("  -h\n");
    print("    Help (this).\n");
    print("\n");
    print("  -s socket\n");
    print("    Path to clamd socket or TCP port.\n");
    print("    Default: /tmp/clamd.socket\n");
    print("\n");
    print("  -l\n");
    print("    Enable logging to syslog.\n");
    print("    Default: disabled\n");
    print("\n");
    print("  -q\n");
    print("    Quiet mode.  Don't print \"Clamd Running\" to STDOUT\n");
    print("    So you can run the utility from cron without being sent\n");
    print("    an email every time it is ran.\n");
    print("    Default: disabled\n");
    print("\n");
    print("  -t timeout\n");
    print("    Timeout (in seconds) to wait for clamd to finish the scan.\n");
    print("    Default: 15\n");
    print("\n");
    print("  -L lockfile\n");
    print("    Path to clamd lockfile.  Use this option if you think you will\n");
    print("    be administratively shutting down clamd.\n");
    print("    If the lockfile (normally created by the init script) doesn't\n");
    print("    exist, it is assumed that clamd isn't supposed to be running\n");
    print("    and we exit without testing it.\n");
    print("\n");

    exit 0;
}

# "CONFIG" section
#
# $Socket values:
#   = "3310" (as in the tcp port; make sure $ip is correct if you use this)
#   = "/var/run/clamav/clamd.ctl"
my $Socket = $options{s} || "/var/run/clamav/clamd.ctl";
my $log = $options{l} || 0;
my $ip = "127.0.0.1";
my $timeout = $options{t} || 15;
my $lockFile = $options{L} || "/var/run/clamav/clamd.pid";
my $quiet = $options{q} || 0;
my $sock;

# reversed eicar
my $data = "*H+H\$!ELIF-TSET-SURIVITNA-DRADNATS-RACIE\$}7)CC7)^P(45XZP\\4\[PA\@\%P!O5X";
srand;
my ($fh, $tempFile) = mkstemp( "/tmp/clamdwatch-XXXXXXXXXXXXXXXX" );
chmod('0644', $tempFile);

# If the lockfile isn't there we assume that clamd was
# shutdown administratively.
unless ( !defined($lockFile) || ($lockFile eq "") ) {
    if ( ! -e $lockFile ) {
        logState("$lockFile not present. clamd not tested.");
	cleanUp();
        exit 1;
    }
}


# why waste time creating the IO::Socket instance if the socket isn't there
#
if ( $Socket !~ /^[0-9]+$/ ) {

    if ( ! -e $Socket ) {
        logState("$Socket missing! It doesn't look like clamd is running.");
	cleanUp();
        exit 0;
    } else {
        $sock = new IO::Socket::UNIX(Type => SOCK_STREAM,
                                        Timeout => $timeout,
                                        Peer => $Socket );
    }
} else {
    $sock = IO::Socket::INET->new( PeerAddr => $ip,
                                   PeerPort => $Socket,
                                   Proto     => 'tcp');
}

if (!$sock || $@ ) { # there could be a stale file from a dead clamd
    logState("Clamd Not Running");
    cleanUp();
    exit 0;
}

if ( $sock->connected ) { 

    # put eicar in the temporary file
    print $fh scalar reverse($data);
    close $fh;

    my $err = "";

    # ask clamd to scan the temporary file
    $sock->send("SCAN $tempFile");

    # set the $timeout and die with a useful error if
    # clamd isn't responsive
    eval {
        local $SIG{ALRM} = sub { cleanUp(); die "timeout\n" };
	alarm($timeout);
        $sock->recv($err, 200);
	alarm(0);
    };
    if ($@) {

	# FIXME we should still remove $tempFile here :\
    	die unless $@ eq "timeout\n";
        logState("Clamd not responding to SCAN request");
	cleanUp();
	exit 0;

    } else { # clamd responded to the request

        if ( $err =~ /.*Eicar.*FOUND/i ) { # everything is good
	    if ( ! $quiet ) {
                logState("Clamd Running");
	    }
            cleanUp();
            exit 1;
        } elsif ( $err =~ /OK$/ ) { # it didn't find the virus
            logState("Clamd didn't find the EICAR pattern. Your virus database(s) could be borked!");
            cleanUp();
            exit 0;
        } else {
	    # you should never get here
	    logState("Clamd is in an unknown state.");
	    logState("It returned: $err");
            cleanUp();
            exit 0;
	}

    }

} else {
    # you should never get here either
    logState("Unknown State (Clamd Useless)");
    cleanUp();
    exit 0;
}

################################################################################
# functions below here

sub logState($) {
    my ($message) = @_;

    if ( $log ) {
        do openlog("clamdwatch",'cons,pid','user');
        do syslog('mail|info',$message);
        do closelog();
    }

    if ( !$quiet ) {
        print "$message\n";
    }

}

sub cleanUp {
    unlink($tempFile);
}

