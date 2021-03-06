<?php
/* $Id$ */
/*
 snort_interfaces.php
 part of m0n0wall (http://m0n0.ch/wall)

 Copyright (C) 2003-2004 Manuel Kasper <mk@neon1.net>.
 Copyright (C) 2008-2009 Robert Zelaya.
 All rights reserved.

 Redistribution and use in source and binary forms, with or without
 modification, are permitted provided that the following conditions are met:

 1. Redistributions of source code must retain the above copyright notice,
 this list of conditions and the following disclaimer.

 2. Redistributions in binary form must reproduce the above copyright
 notice, this list of conditions and the following disclaimer in the
 documentation and/or other materials provided with the distribution.

 THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
 INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
 AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
 OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 POSSIBILITY OF SUCH DAMAGE.
 */

/*

TODO: Nov 12 09
Clean this code up its ugly
Important add error checking

*/

require_once("guiconfig.inc");
require_once("/usr/local/pkg/snort/snort_gui.inc");
require_once("/usr/local/pkg/snort/snort.inc");

global $g;

if (!is_array($config['installedpackages']['snortglobal']['rule'])) {
	$config['installedpackages']['snortglobal']['rule'] = array();
}
//nat_rules_sort();
$a_nat = &$config['installedpackages']['snortglobal']['rule'];

$id = $_GET['id'];
if (isset($_POST['id']))
$id = $_POST['id'];

if (isset($_GET['dup'])) {
	$id = $_GET['dup'];
	$after = $_GET['dup'];
}

if (isset($id) && $a_nat[$id]) {

	/* old options */
	$pconfig['def_ssl_ports_ignore'] = $a_nat[$id]['def_ssl_ports_ignore'];
	$pconfig['flow_depth'] = $a_nat[$id]['flow_depth'];
	$pconfig['max_queued_bytes'] = $a_nat[$id]['max_queued_bytes'];
	$pconfig['max_queued_segs'] = $a_nat[$id]['max_queued_segs'];
	$pconfig['perform_stat'] = $a_nat[$id]['perform_stat'];
	$pconfig['http_inspect'] = $a_nat[$id]['http_inspect'];
	$pconfig['other_preprocs'] = $a_nat[$id]['other_preprocs'];
	$pconfig['ftp_preprocessor'] = $a_nat[$id]['ftp_preprocessor'];
	$pconfig['smtp_preprocessor'] = $a_nat[$id]['smtp_preprocessor'];
	$pconfig['sf_portscan'] = $a_nat[$id]['sf_portscan'];
	$pconfig['dce_rpc_2'] = $a_nat[$id]['dce_rpc_2'];
	$pconfig['dns_preprocessor'] = $a_nat[$id]['dns_preprocessor'];
	$pconfig['def_dns_servers'] = $a_nat[$id]['def_dns_servers'];
	$pconfig['def_dns_ports'] = $a_nat[$id]['def_dns_ports'];
	$pconfig['def_smtp_servers'] = $a_nat[$id]['def_smtp_servers'];
	$pconfig['def_smtp_ports'] = $a_nat[$id]['def_smtp_ports'];
	$pconfig['def_mail_ports'] = $a_nat[$id]['def_mail_ports'];
	$pconfig['def_http_servers'] = $a_nat[$id]['def_http_servers'];
	$pconfig['def_www_servers'] = $a_nat[$id]['def_www_servers'];
	$pconfig['def_http_ports'] = $a_nat[$id]['def_http_ports'];
	$pconfig['def_sql_servers'] = $a_nat[$id]['def_sql_servers'];
	$pconfig['def_oracle_ports'] = $a_nat[$id]['def_oracle_ports'];
	$pconfig['def_mssql_ports'] = $a_nat[$id]['def_mssql_ports'];
	$pconfig['def_telnet_servers'] = $a_nat[$id]['def_telnet_servers'];
	$pconfig['def_telnet_ports'] = $a_nat[$id]['def_telnet_ports'];
	$pconfig['def_snmp_servers'] = $a_nat[$id]['def_snmp_servers'];
	$pconfig['def_snmp_ports'] = $a_nat[$id]['def_snmp_ports'];
	$pconfig['def_ftp_servers'] = $a_nat[$id]['def_ftp_servers'];
	$pconfig['def_ftp_ports'] = $a_nat[$id]['def_ftp_ports'];
	$pconfig['def_ssh_servers'] = $a_nat[$id]['def_ssh_servers'];
	$pconfig['def_ssh_ports'] = $a_nat[$id]['def_ssh_ports'];
	$pconfig['def_pop_servers'] = $a_nat[$id]['def_pop_servers'];
	$pconfig['def_pop2_ports'] = $a_nat[$id]['def_pop2_ports'];
	$pconfig['def_pop3_ports'] = $a_nat[$id]['def_pop3_ports'];
	$pconfig['def_imap_servers'] = $a_nat[$id]['def_imap_servers'];
	$pconfig['def_imap_ports'] = $a_nat[$id]['def_imap_ports'];
	$pconfig['def_sip_proxy_ip'] = $a_nat[$id]['def_sip_proxy_ip'];
	$pconfig['def_sip_proxy_ports'] = $a_nat[$id]['def_sip_proxy_ports'];
	$pconfig['def_auth_ports'] = $a_nat[$id]['def_auth_ports'];
	$pconfig['def_finger_ports'] = $a_nat[$id]['def_finger_ports'];
	$pconfig['def_irc_ports'] = $a_nat[$id]['def_irc_ports'];
	$pconfig['def_nntp_ports'] = $a_nat[$id]['def_nntp_ports'];
	$pconfig['def_rlogin_ports'] = $a_nat[$id]['def_rlogin_ports'];
	$pconfig['def_rsh_ports'] = $a_nat[$id]['def_rsh_ports'];
	$pconfig['def_ssl_ports'] = $a_nat[$id]['def_ssl_ports'];
	$pconfig['barnyard_enable'] = $a_nat[$id]['barnyard_enable'];
	$pconfig['barnyard_mysql'] = $a_nat[$id]['barnyard_mysql'];
	$pconfig['enable'] = $a_nat[$id]['enable'];
	$pconfig['uuid'] = $a_nat[$id]['uuid'];
	$pconfig['interface'] = $a_nat[$id]['interface'];
	$pconfig['descr'] = $a_nat[$id]['descr'];
	$pconfig['whitelistname'] = $a_nat[$id]['whitelistname'];
	$pconfig['homelistname'] = $a_nat[$id]['homelistname'];
	$pconfig['externallistname'] = $a_nat[$id]['externallistname'];
	$pconfig['suppresslistname'] = $a_nat[$id]['suppresslistname'];
	$pconfig['performance'] = $a_nat[$id]['performance'];
	$pconfig['blockoffenders7'] = $a_nat[$id]['blockoffenders7'];
	$pconfig['alertsystemlog'] = $a_nat[$id]['alertsystemlog'];
	$pconfig['tcpdumplog'] = $a_nat[$id]['tcpdumplog'];
	$pconfig['snortunifiedlog'] = $a_nat[$id]['snortunifiedlog'];
	$pconfig['configpassthru'] = $a_nat[$id]['configpassthru'];
	$pconfig['barnconfigpassthru'] = base64_decode($a_nat[$id]['barnconfigpassthru']);
	$pconfig['rulesets'] = $a_nat[$id]['rulesets'];
	$pconfig['rule_sid_off'] = $a_nat[$id]['rule_sid_off'];
	$pconfig['rule_sid_on'] = $a_nat[$id]['rule_sid_on'];


	if (!$pconfig['interface'])
	$pconfig['interface'] = "wan";
} else {
	$pconfig['interface'] = "wan";
}

if (isset($_GET['dup']))
unset($id);

$if_real = convert_friendly_interface_to_real_interface_name2($pconfig['interface']);
$snort_uuid = $config['installedpackages']['snortglobal']['rule'][$id]['uuid'];


/* alert file */
$d_snortconfdirty_path = "/var/run/snort_conf_{$snort_uuid}_{$if_real}.dirty";

/* this will exec when alert says apply */
if ($_POST['apply']) {

	if (file_exists($d_snortconfdirty_path)) {
			
		write_config();
			
		sync_snort_package_all($id, $if_real, $snort_uuid);
		sync_snort_package();
			
		unlink($d_snortconfdirty_path);
			
	}

}


if ($_POST["Submit"]) {

	/* check for overlaps */
	foreach ($a_nat as $natent) {
		if (isset($id) && ($a_nat[$id]) && ($a_nat[$id] === $natent))
		continue;
		if ($natent['interface'] != $_POST['interface'])
		continue;
	}

	/* if no errors write to conf */
	if (!$input_errors) {
		$natent = array();
		/* repost the options already in conf */

		if ($pconfig['interface'] != "") { $natent['interface'] = $pconfig['interface']; }
		if ($pconfig['enable'] != "") { $natent['enable'] = $pconfig['enable']; }
		if ($pconfig['uuid'] != "") { $natent['uuid'] = $pconfig['uuid']; }
		if ($pconfig['descr'] != "") { $natent['descr'] = $pconfig['descr']; }
		if ($pconfig['performance'] != "") { $natent['performance'] = $pconfig['performance']; }
		if ($pconfig['blockoffenders7'] != "") { $natent['blockoffenders7'] = $pconfig['blockoffenders7']; }
		if ($pconfig['alertsystemlog'] != "") { $natent['alertsystemlog'] = $pconfig['alertsystemlog']; }
		if ($pconfig['tcpdumplog'] != "") { $natent['tcpdumplog'] = $pconfig['tcpdumplog']; }
		if ($pconfig['snortunifiedlog'] != "") { $natent['snortunifiedlog'] = $pconfig['snortunifiedlog']; }
		if ($pconfig['def_ssl_ports_ignore'] != "") { $natent['def_ssl_ports_ignore'] = $pconfig['def_ssl_ports_ignore']; }
		if ($pconfig['flow_depth'] != "") { $natent['flow_depth'] = $pconfig['flow_depth']; }
		if ($pconfig['max_queued_bytes'] != "") { $natent['max_queued_bytes'] = $pconfig['max_queued_bytes']; }
		if ($pconfig['max_queued_segs'] != "") { $natent['max_queued_segs'] = $pconfig['max_queued_segs']; }
		if ($pconfig['perform_stat'] != "") { $natent['perform_stat'] = $pconfig['perform_stat']; }
		if ($pconfig['http_inspect'] != "") { $natent['http_inspect'] = $pconfig['http_inspect']; }
		if ($pconfig['other_preprocs'] != "") { $natent['other_preprocs'] = $pconfig['other_preprocs']; }
		if ($pconfig['ftp_preprocessor'] != "") { $natent['ftp_preprocessor'] = $pconfig['ftp_preprocessor']; }
		if ($pconfig['smtp_preprocessor'] != "") { $natent['smtp_preprocessor'] = $pconfig['smtp_preprocessor']; }
		if ($pconfig['sf_portscan'] != "") { $natent['sf_portscan'] = $pconfig['sf_portscan']; }
		if ($pconfig['dce_rpc_2'] != "") { $natent['dce_rpc_2'] = $pconfig['dce_rpc_2']; }
		if ($pconfig['dns_preprocessor'] != "") { $natent['dns_preprocessor'] = $pconfig['dns_preprocessor']; }
		if ($pconfig['def_dns_servers'] != "") { $natent['def_dns_servers'] = $pconfig['def_dns_servers']; }
		if ($pconfig['def_dns_ports'] != "") { $natent['def_dns_ports'] = $pconfig['def_dns_ports']; }
		if ($pconfig['def_smtp_servers'] != "") { $natent['def_smtp_servers'] = $pconfig['def_smtp_servers']; }
		if ($pconfig['def_smtp_ports'] != "") { $natent['def_smtp_ports'] = $pconfig['def_smtp_ports']; }
		if ($pconfig['def_mail_ports'] != "") { $natent['def_mail_ports'] = $pconfig['def_mail_ports']; }
		if ($pconfig['def_http_servers'] != "") { $natent['def_http_servers'] = $pconfig['def_http_servers']; }
		if ($pconfig['def_www_servers'] != "") { $natent['def_www_servers'] = $pconfig['def_www_servers']; }
		if ($pconfig['def_http_ports'] != "") { $natent['def_http_ports'] = $pconfig['def_http_ports'];	}
		if ($pconfig['def_sql_servers'] != "") { $natent['def_sql_servers'] = $pconfig['def_sql_servers']; }
		if ($pconfig['def_oracle_ports'] != "") { $natent['def_oracle_ports'] = $pconfig['def_oracle_ports']; }
		if ($pconfig['def_mssql_ports'] != "") { $natent['def_mssql_ports'] = $pconfig['def_mssql_ports']; }
		if ($pconfig['def_telnet_servers'] != "") { $natent['def_telnet_servers'] = $pconfig['def_telnet_servers']; }
		if ($pconfig['def_telnet_ports'] != "") { $natent['def_telnet_ports'] = $pconfig['def_telnet_ports']; }
		if ($pconfig['def_snmp_servers'] != "") { $natent['def_snmp_servers'] = $pconfig['def_snmp_servers']; }
		if ($pconfig['def_snmp_ports'] != "") { $natent['def_snmp_ports'] = $pconfig['def_snmp_ports']; }
		if ($pconfig['def_ftp_servers'] != "") { $natent['def_ftp_servers'] = $pconfig['def_ftp_servers']; }
		if ($pconfig['def_ftp_ports'] != "") { $natent['def_ftp_ports'] = $pconfig['def_ftp_ports']; }
		if ($pconfig['def_ssh_servers'] != "") { $natent['def_ssh_servers'] = $pconfig['def_ssh_servers']; }
		if ($pconfig['def_ssh_ports'] != "") { $natent['def_ssh_ports'] = $pconfig['def_ssh_ports']; }
		if ($pconfig['def_pop_servers'] != "") { $natent['def_pop_servers'] = $pconfig['def_pop_servers']; }
		if ($pconfig['def_pop2_ports'] != "") { $natent['def_pop2_ports'] = $pconfig['def_pop2_ports']; }
		if ($pconfig['def_pop3_ports'] != "") { $natent['def_pop3_ports'] = $pconfig['def_pop3_ports']; }
		if ($pconfig['def_imap_servers'] != "") { $natent['def_imap_servers'] = $pconfig['def_imap_servers']; }
		if ($pconfig['def_imap_ports'] != "") { $natent['def_imap_ports'] = $pconfig['def_imap_ports']; }
		if ($pconfig['def_sip_proxy_ip'] != "") { $natent['def_sip_proxy_ip'] = $pconfig['def_sip_proxy_ip']; }
		if ($pconfig['def_sip_proxy_ports'] != "") { $natent['def_sip_proxy_ports'] = $pconfig['def_sip_proxy_ports']; }
		if ($pconfig['def_auth_ports'] != "") { $natent['def_auth_ports'] = $pconfig['def_auth_ports']; }
		if ($pconfig['def_finger_ports'] != "") { $natent['def_finger_ports'] = $pconfig['def_finger_ports']; }
		if ($pconfig['def_irc_ports'] != "") { $natent['def_irc_ports'] = $pconfig['def_irc_ports']; }
		if ($pconfig['def_nntp_ports'] != "") { $natent['def_nntp_ports'] = $pconfig['def_nntp_ports']; }
		if ($pconfig['def_rlogin_ports'] != "") { $natent['def_rlogin_ports'] = $pconfig['def_rlogin_ports']; }
		if ($pconfig['def_rsh_ports'] != "") { $natent['def_rsh_ports'] = $pconfig['def_rsh_ports']; }
		if ($pconfig['def_ssl_ports'] != "") { $natent['def_ssl_ports'] = $pconfig['def_ssl_ports']; }
		if ($pconfig['snortunifiedlog'] != "") { $natent['snortunifiedlog'] = $pconfig['snortunifiedlog'];	}
		if ($pconfig['configpassthru'] != "") { $natent['configpassthru'] = $pconfig['configpassthru'];	}
		if ($pconfig['rulesets'] != "") { $natent['rulesets'] = $pconfig['rulesets']; }
		if ($pconfig['rule_sid_off'] != "") { $natent['rule_sid_off'] = $pconfig['rule_sid_off']; }
		if ($pconfig['rule_sid_on'] != "") { $natent['rule_sid_on'] = $pconfig['rule_sid_on']; }
		if ($pconfig['whitelistname'] != "") { $natent['whitelistname'] = $pconfig['whitelistname']; }
		if ($pconfig['homelistname'] != "") { $natent['homelistname'] = $pconfig['homelistname']; }
		if ($pconfig['externallistname'] != "") { $natent['externallistname'] = $pconfig['externallistname']; }
		if ($pconfig['suppresslistname'] != "") { $natent['suppresslistname'] = $pconfig['suppresslistname']; }

		/* post new options */
		$natent['barnyard_enable'] = $_POST['barnyard_enable'] ? on : off;
		$natent['barnyard_mysql'] = $_POST['barnyard_mysql'] ? $_POST['barnyard_mysql'] : $pconfig['barnyard_mysql'];
		$natent['barnconfigpassthru'] = base64_encode($_POST['barnconfigpassthru']) ? base64_encode($_POST['barnconfigpassthru']) : $pconfig['barnconfigpassthru'];
		if ($_POST['barnyard_enable'] == "on") { $natent['snortunifiedlog'] = on; }else{ $natent['snortunifiedlog'] = off; } if ($_POST['barnyard_enable'] == "") { $natent['snortunifiedlog'] = off; }

		if (isset($id) && $a_nat[$id])
		$a_nat[$id] = $natent;
		else {
			if (is_numeric($after))
			array_splice($a_nat, $after+1, 0, array($natent));
			else
			$a_nat[] = $natent;
		}

		write_config();

		/* after click go to this page */
		touch($d_snortconfdirty_path);
		header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
		header( 'Cache-Control: no-store, no-cache, must-revalidate' );
		header( 'Cache-Control: post-check=0, pre-check=0', false );
		header( 'Pragma: no-cache' );
		sleep(2);
		header("Location: snort_barnyard.php?id=$id");
		exit;
	}
}

$pgtitle = "Snort: Interface: $id$if_real Barnyard2 Edit";
include("/usr/local/pkg/snort/snort_head.inc");

?>
<body
	link="#0000CC" vlink="#0000CC" alink="#0000CC">


<?php include("fbegin.inc"); ?>
<?if($pfsense_stable == 'yes'){echo '<p class="pgtitle">' . $pgtitle . '</p>';}?>

<?php
echo "{$snort_general_css}\n";
?>

<!-- hack to fix the hardcoed fbegin link in header -->
<div id="header-left2"><a href="../index.php" id="status-link2"><img
	src="./images/transparent.gif" border="0"></img></a></div>

<div class="body2">

<noscript>
<div class="alert" ALIGN=CENTER><img
	src="../themes/<?php echo $g['theme']; ?>/images/icons/icon_alert.gif" /><strong>Please
enable JavaScript to view this content
</CENTER></div>
</noscript>

<script language="JavaScript">
<!--

function enable_change(enable_change) {
	endis = !(document.iform.barnyard_enable.checked || enable_change);
	// make shure a default answer is called if this is envoked.
	endis2 = (document.iform.barnyard_enable);

<?php
/* make shure all the settings exist or function hide will not work */
/* if $id is emty allow if and discr to be open */
if($id != "") 
{
echo "	
	document.iform.interface.disabled = endis2;\n";
}
?>
    document.iform.barnyard_mysql.disabled = endis;
    document.iform.barnconfigpassthru.disabled = endis;
}
//-->
</script>
<body link="#0000CC" vlink="#0000CC" alink="#0000CC">
<form action="snort_barnyard.php" method="post"
	enctype="multipart/form-data" name="iform" id="iform"><?php

	/* Display Alert message */
	if ($input_errors) {
		print_input_errors($input_errors); // TODO: add checks
	}

	if ($savemsg) {
		print_info_box2($savemsg);
	}

	if (file_exists($d_snortconfdirty_path)) {
		echo '<p>';

		if($savemsg) {
			print_info_box_np2("{$savemsg}");
		}else{
			print_info_box_np2('
			The Snort configuration has changed and snort needs to be restarted on this interface.<br>
			You must apply the changes in order for them to take effect.<br>
			');
		}
	}

	?>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="tabnavtbl"><?php
		if($id != "")
		{

			echo '<div class="newtabmenu" style="margin:1px 0px; width:775px;">' . "\n";
			echo '<!-- Tabbed bar code -->' . "\n";
			echo '<ul class="newtabmenu">' . "\n";
			echo '<li><a href="/snort/snort_interfaces.php"><span>Snort Interfaces</span></a></li>' . "\n";
			echo "<li><a href=\"/snort/snort_interfaces_edit.php?id={$id}\"><span>If Settings</span></a></li>\n";
			echo "<li><a href=\"/snort/snort_rulesets.php?id={$id}\"><span>Categories</span></a></li>\n";
			echo "<li><a href=\"/snort/snort_rules.php?id={$id}\"><span>Rules</span></a></li>\n";
			echo "<li><a href=\"/snort/snort_define_servers.php?id={$id}\"><span>Servers</span></a></li>\n";
			echo "<li><a href=\"/snort/snort_preprocessors.php?id={$id}\"><span>Preprocessors</span></a></li>\n";
			echo "<li class=\"newtabmenu_active\"><a href=\"/snort/snort_barnyard.php?id={$id}\"><span>Barnyard2</span></a></li>\n";
			echo '</ul>' . "\n";
			echo '</div>' . "\n";

		}
		?></td>
	</tr>
	<tr>
		<td class="tabcont">
		<table width="100%" border="0" cellpadding="6" cellspacing="0">
		<?php
		/* display error code if there is no id */
		if($id == "")
		{
			echo "
				<style type=\"text/css\">
				.noid {
				position:absolute;
				top:10px;
				left:0px;
				width:94%;
				background:#FCE9C0;
				background-position: 15px; 
				border-top:2px solid #DBAC48;
				border-bottom:2px solid #DBAC48;
				padding: 15px 10px 85% 50px;
				}
				</style> 
				<div class=\"alert\" ALIGN=CENTER><img src=\"/themes/{$g['theme']}/images/icons/icon_alert.gif\"/><strong>You can not edit options without an interface ID.</CENTER></div>\n";

		}
		?>
			<tr>
				<td colspan="2" valign="top" class="listtopic">General Barnyard2
				Settings</td>
			</tr>
			<tr>
				<td width="22%" valign="top" class="vncellreq2">Enable</td>
				<td width="78%" class="vtable"><?php
				// <input name="enable" type="checkbox" value="yes" checked onClick="enable_change(false)">
				// care with spaces
				if ($pconfig['barnyard_enable'] == "on")
				$checked = checked;
				if($id != "")
				{
					$onclick_enable = "onClick=\"enable_change(false)\">";
				}
				echo "
					<input name=\"barnyard_enable\" type=\"checkbox\" value=\"on\" $checked $onclick_enable
					<strong>Enable Barnyard2 on this Interface</strong><br>
					This will enable barnyard2 for this interface. You will also have to set the database credentials.</td>\n\n";
				?>
			
			</tr>
			<tr>
				<td width="22%" valign="top" class="vncell2">Interface</td>
				<td width="78%" class="vtable"><select name="interface"
					class="formfld">
					<?php
					$interfaces = array('wan' => 'WAN', 'lan' => 'LAN', 'pptp' => 'PPTP', 'pppoe' => 'PPPOE');
					for ($i = 1; isset($config['interfaces']['opt' . $i]); $i++) {
						$interfaces['opt' . $i] = $config['interfaces']['opt' . $i]['descr'];
					}
					foreach ($interfaces as $iface => $ifacename): ?>
					<option value="<?=$iface;?>"
					<?php if ($iface == $pconfig['interface']) echo "selected"; ?>><?=htmlspecialchars($ifacename);?>
					</option>
					<?php endforeach; ?>
				</select><br>
				<span class="vexpl">Choose which interface this rule applies to.<br>
				Hint: in most cases, you'll want to use WAN here.</span></td>
			</tr>
			<tr>
				<td colspan="2" valign="top" class="listtopic">Mysql Settings</td>
			</tr>
			<tr>
				<td width="22%" valign="top" class="vncell2">Log to a Mysql Database</td>
				<td width="78%" class="vtable"><input name="barnyard_mysql"
					type="text" class="formfld" id="barnyard_mysql" size="100"
					value="<?=htmlspecialchars($pconfig['barnyard_mysql']);?>"> <br>
				<span class="vexpl">Example: output database: alert, mysql,
				dbname=snort user=snort host=localhost password=xyz<br>
				Example: output database: log, mysql, dbname=snort user=snort
				host=localhost password=xyz</span></td>
			</tr>
			<tr>
				<td colspan="2" valign="top" class="listtopic">Advanced Settings</td>
			</tr>
			<tr>
				<td width="22%" valign="top" class="vncell2">Advanced configuration
				pass through</td>
				<td width="78%" class="vtable"><textarea name="barnconfigpassthru"
					cols="100" rows="7" id="barnconfigpassthru" class="formpre"><?=htmlspecialchars($pconfig['barnconfigpassthru']);?></textarea>
				<br>
				Arguments here will be automatically inserted into the running
				barnyard2 configuration.</td>
			</tr>
			<tr>
				<td width="22%" valign="top">&nbsp;</td>
				<td width="78%"><input name="Submit" type="submit" class="formbtn"
					value="Save"><input type="button" class="formbtn" value="Cancel"
					onclick="history.back()"> <?php if (isset($id) && $a_nat[$id]): ?>
				<input name="id" type="hidden" value="<?=$id;?>"> <?php endif; ?></td>
			</tr>
			<tr>
				<td width="22%" valign="top">&nbsp;</td>
				<td width="78%"><span class="vexpl"><span class="red"><strong>Note:</strong></span>
				<br>
				Please save your settings befor you click start. </td>
			</tr>
		</table>

</table>
</form>

</div>

<script language="JavaScript">
<!--
enable_change(false);
//-->
</script>
					<?php include("fend.inc"); ?>
</body>
</html>
