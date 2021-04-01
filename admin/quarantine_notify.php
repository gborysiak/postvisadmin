<?php
$dbhost = 'localhost';  // Database Host to connect to
$dbuser = 'postfix';  // Username that has access to your Postfix Database
$dbpass = 'postfix';     // Password that has access to your Postfix Database
$postfixdatabase = 'postfix'; // Postfix Database
$dbconfig = 'mysqli';

$siteurl = "https://admin.lazytechs.com/"; //Site URL, include the trailing /
$email_reply = 'sysadmin@lazytechs.com'; //Reply e-mail to put in notification message header
$notification_subject = 'Mail Quarantine Summary for '; //Notification Message Subject

$format_text = '0'; //Choose format of notification
$notify_domain_admins = '0'; //Notify Admin Only

if($notify_domain_admins == '1'){
	$quarantine_notify0 = "SELECT DISTINCT admin.domain FROM admin WHERE admin.active = 1 AND admin.domain <> '' AND admin.superadmin <> 1;";
}
else{
	
	$quarantine_notify0 = "SELECT DISTINCT alias.goto as username FROM msgrcpt, msgs, quarantine, alias, maddr LEFT JOIN mailbox ON maddr.email LIKE mailbox.username WHERE maddr.email = alias.address AND msgrcpt.rid = maddr.id AND msgrcpt.mail_id = msgs.mail_id AND quarantine.mail_id = msgrcpt.mail_id AND alias.active = 1 AND mailbox.quarantine_notify = 1";
}




$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $postfixdatabase);

$result0 = $mysqli->query($quarantine_notify0);
while($row0=@mysqli_fetch_array($result0)) {

if($notify_domain_admins == '1'){
	$quarantine_notify1 = "SELECT DISTINCT msgs.time_iso AS rx_date, alias.address AS email, alias.goto, msgs.from_addr AS from_addr, msgs.subject AS subject, msgs.mail_id, msgs.secret_id FROM msgrcpt, msgs, quarantine, alias, maddr WHERE maddr.email = alias.address AND msgrcpt.rid = maddr.id AND msgrcpt.mail_id = msgs.mail_id AND quarantine.mail_id = msgrcpt.mail_id AND alias.active = 1 AND msgrcpt.rs NOT IN ('R','D') AND alias.address LIKE '%" . $row0['domain'] . "' ORDER BY msgs.time_iso DESC;";
}
else{
	$quarantine_notify1 = "SELECT DISTINCT msgs.time_iso AS rx_date, alias.address AS email, alias.goto, msgs.from_addr AS from_addr, msgs.subject AS subject, msgs.mail_id, msgs.secret_id FROM msgrcpt, msgs, quarantine, alias, maddr, mailbox WHERE maddr.email = alias.address AND msgrcpt.rid = maddr.id AND msgrcpt.mail_id = msgs.mail_id AND quarantine.mail_id = msgrcpt.mail_id AND alias.active = 1 AND msgrcpt.rs NOT IN ('R','D') AND alias.goto = '" . $row0['username'] . "' ORDER BY msgs.time_iso DESC;";
}
	
	$result = $mysqli->query($quarantine_notify1);
	
	$body = '';

	if($format_text =='1'){
		while($row=@mysqli_fetch_array($result)) {

				$body .= ' ' . substr($row['from_addr'] . '                                   ',0,35);
				$body .= ' ' . substr($row['subject'] . '                                        ',0,40);
				$body .= $row['rx_date'];
				$body .= "\r\n";

				$email_to = $row['goto'];
				$email_address = $row['email'];
		}
		
		if($result)	{
			if($notify_domain_admins == '1'){
				$email_address = $row0['domain'];
				$quarantine_notify2 = "SELECT DISTINCT admin.username FROM admin WHERE admin.active = 1 AND admin.superadmin <> 1;";
				$email_to = '';
				$result2 = $mysqli->query($quarantine_notify2);
				while($row2=@mysqli_fetch_array($result2)) {
					$email_to .= $row2['username'] . ',';
				}
			}
			else{
			}
			
			$to = $email_to;
			
			$headers = 'From: ' . $email_reply; 
			
			$body_header = 'Message(s) Sent to: ' . $email_address;
			$body_header .=  "\r\n";
			$body_header .= ' Message From:                      ';
			$body_header .= ' Subject:                                ';
			$body_header .= 'Date Received:     ';
			$body_header .=  "\r\n";
			
			$body_footer = '';
			
			$message = $body_header;
			$message .= $body;
			$message .= $body_footer;
			
			$subject = $notification_subject . date("F j, Y"); 
			
			mail($to, $subject, $message, $headers);
		}
		else {
		}
	}
	else{	
		while($row=@mysqli_fetch_array($result)) {
			
				$body .= '<tr>';
				$body .= '<td>';
				$body .= $row['from_addr'];
				$body .= '</td>';
				$body .= '<td>';
				$body .= substr($row['subject'],0,50);
				$body .= '</td>';
				$body .= '<td>';
				$body .= $row['rx_date'];
				$body .= '</td>';
				$body .= '<td>';
				$body .= '<td>';
                                $body .= '<a href=' .$siteurl . 'admin/quarantine_release.php?mail_id=' . urlencode($row['mail_id']) . '&secret_id=' . urlencode($row['secret_id']) . '&request=release>Release</a>';
$body .= '<td>';
                                $body .= '<a href=' .$siteurl . 'admin/quarantine_release.php?mail_id=' . urlencode($row['mail_id']) . '&secret_id=' . urlencode($row['secret_id']) . '&request=delete>Delete</a>';
                                $body .= '</td>';
                                $body .= '</td>';
				$body .= '</td>';
				$body .= '</tr>';

				$email_to = $row['goto'];
				$email_address = $row['email'];
		}
		
		if($result)	{
			if($notify_domain_admins == '1'){
				$email_address = $row0['domain'];
				$quarantine_notify2 = "SELECT DISTINCT admin.username FROM admin WHERE admin.active = 1 AND admin.superadmin <> 1;";
				$email_to = '';
				$result2 = $mysqli->query($quarantine_notify2);
				while($row2=@mysqli_fetch_array($result2)) {
					$email_to .= $row2['username'] . ',';
				}
			}
			else{
			}
			
			$to = $email_to;
			
			$headers = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n" . 'From: ' . $email_reply . "\n\n"; 
			
			$body_header = '<HTML>';
			$body_header .= '</HEAD>';
			$body_header .= '<BODY>';
			$body_header .= '<BR>';
			$body_header .= '<P>Message(s) Sent To: ' . $email_address;
			$body_header .= '<BR>';
			$body_header .= '<P>The following messages were quarantined before they reached your inbox. You may request that the messages be delivered to your inbox by clicking on Release to the right of the message.';
			$body_header .= '<BR><BR>';
			$body_header .= '<div width="500">';
			$body_header .= '<table border="0" cellspacing="2" cellpadding="2">';
			$body_header .= '<tr>';
			$body_header .= '<td>';
			$body_header .= '<h6>Message From:</h6>';
			$body_header .= '</td>';
			$body_header .= '<td>';
			$body_header .= '<h6> Subject:</h6>';
			$body_header .= '</td>';		
			$body_header .= '<td>';
			$body_header .= '<h6> Date Received:</h6>';
			$body_header .= '</td>';
			$body_header .= '<td>';
			$body_header .= '</td>';
			$body_header .= '<td>';
                        $body_header .= '</td>';
			$body_header .= '</tr>';
			
			$body_footer = '</table>';
			$body_footer .= '</div>';
			$body_footer .= '</BODY>';
			$body_footer .= '</HTML>';
			
			$message = $body_header;
			$message .= $body;
			$message .= $body_footer;
			
			$subject = $notification_subject . date("F j, Y"); 
			
			mail($to, $subject, $message, $headers);
			
		}
		else {
		}
	}
}
?>
