<?php

/**
 * Author: Erik Kristensen
 * Email: erik@erikkristensen.com
 *
 * Developed for: pfSense.com
 *
 * Copyright (c) 2009, pfSense.com
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
 * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in thedocumentation and/or other materials provided with the distribution.
 * Neither the name of the <ORGANIZATION> nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS;
 * OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

require_once('filter.inc');

$nagios_check_path = "/usr/local/libexec/nagios";

function nrpe2_custom_php_install_command() {
	global $g, $config;
	conf_mount_rw();

	$ip = $config['interfaces']['lan']['ipaddr'];

	$config['installedpackages']['nrpe2']['config'][0]['enabled'] = "on";
	$config['installedpackages']['nrpe2']['config'][0]['server_address'] = $ip;
	$config['installedpackages']['nrpe2']['config'][0]['server_port'] = 5666;
	$config['installedpackages']['nrpe2']['config'][0]['allowed_hosts'] = "127.0.0.1";

	$config['installedpackages']['nrpe2']['config'][0]['row'] = array(
		0 => array(
			'name' => 'check_users',
			'command' => 'check_users',
			'warning' => '5',
			'critical' => '10'
		),
		1 => array(
			'name' => 'check_load',
			'command' => 'check_load',
			'warning' => '15,10,5',
			'critical' => '30,25,20',
		),
		2 => array(
			'name' => 'check_root',
			'command' => 'check_disk',
			'warning' => '20%',
			'critical' => '10%',
			'extra' => '-p /'
		),
		3 => array(
			'name' => 'check_var',
			'command' => 'check_disk',
			'warning' => '20%',
			'critical' => '10%',
			'extra' => '-p /var/run'
		),
		4 => array(
			'name' => 'check_zombie_procs',
			'command' => 'check_procs',
			'warning' => '5',
			'critical' => '10',
			'extra' => '-s Z'
		),
		5 => array(
			'name' => 'check_total_procs',
			'command' => 'check_procs',
			'warning' => '150',
			'critical' => '200'
		)
	);
	unlink_if_exists('/usr/local/etc/rc.d/nrpe2');
	$fd = fopen('/usr/local/etc/rc.d/nrpe2.sh', 'w');
	$rc_file = <<<EOD
#!/bin/sh
#
# $FreeBSD: ports/net-mgmt/nrpe2/files/nrpe2.in,v 1.3 2008/12/25 20:24:43 miwi Exp $
#

# PROVIDE: nrpe2
# REQUIRE: NETWORKING
# KEYWORD: shutdown

#
# Add the following lines to /etc/rc.conf to enable nrpe2:
# nrpe2_enable (bool):    Set to "NO" by default.
#                         Set it to "YES" to enable nrpe2.
# nrpe2_flags (str):      Set to "" by default.
# nrpe2_configfile (str): Set to "/usr/local/etc/nrpe.cfg" by default.
# nrpe2_pidfile (str):    Set to "/var/spool/nagios/nrpe2.pid" by default.
#

. /etc/rc.subr

nrpe2_enable=\${nrpe2_enable-"YES"}
name="nrpe2"
rcvar=`set_rcvar`

command="/usr/local/sbin/nrpe2"
command_args="-d"
extra_commands="reload"

sig_reload=HUP

[ -z "\${nrpe2_flags}" ] && nrpe2_flags=""
[ -z "\${nrpe2_configfile}" ] && nrpe2_configfile="/usr/local/etc/nrpe.cfg"
[ -z "\${nrpe2_pidfile}" ] && nrpe2_pidfile="/var/run/nrpe2.pid"

load_rc_config "\${name}"

pidfile="\${nrpe2_pidfile}"

required_files="\${nrpe2_configfile}"
command_args="\${command_args} -c \${nrpe2_configfile}"

run_rc_command "$1"
EOD;
	fwrite($fd, $rc_file);
	fclose($fd);
	chmod('/usr/local/etc/rc.d/nrpe2.sh', 0755);

	conf_mount_ro();
}

function nrpe2_custom_php_deinstall_command() {
	global $g, $config;
	conf_mount_rw();

	conf_mount_ro();
}

function nrpe2_custom_php_write_config() {
	global $g, $config, $nagios_check_path;
	conf_mount_rw();

	foreach ($config['installedpackages']['nrpe2']['config'][0]['row'] as $cmd) {
		if (is_executable("{$nagios_check_path}/{$cmd['command']}"))
			$cmds[] = "command[{$cmd['name']}]={$nagios_check_path}/{$cmd['command']} -w {$cmd['warning']} -c {$cmd['critical']} {$cmd['extra']}\n";
	}
	$commands = implode($cmds);

	$server_port = $config['installedpackages']['nrpe2']['config'][0]['server_port'];
	$server_address = $config['installedpackages']['nrpe2']['config'][0]['server_address'];
	$allowed_hosts = $config['installedpackages']['nrpe2']['config'][0]['allowed_hosts'];

	$fd = fopen('/usr/local/etc/nrpe.cfg', 'w');
	$nrpe_cfg = <<<EOD
log_facility=daemon
pid_file=/var/run/nrpe2.pid
server_port={$server_port}
server_address={$server_address}
nrpe_user=nagios
nrpe_group=nagios
allowed_hosts={$allowed_hosts}
dont_blame_nrpe=0
debug=0
command_timeout=60
connection_timeout=300
{$commands}
EOD;
	fwrite($fd, $nrpe_cfg);
	fclose($fd);
	conf_mount_ro();
}

function nrpe2_custom_php_service() {
	global $g, $config;

	if ($config['installedpackages']['nrpe2']['config'][0]['enabled'] == "on") {
		exec("/usr/local/etc/rc.d/nrpe2.sh restart");
	}
	else {
		exec("/usr/local/etc/rc.d/nrpe2.sh stop");
	}
}

function nrpe2_get_commands() {
	global $nagios_check_path;
	$commands = glob("{$nagios_check_path}/check_*");
	$cmdarr = array();
	foreach ($commands as $cmd)
		$cmdarr[]["command"] = basename($cmd);
	return $cmdarr;
}
?>
