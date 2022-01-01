<?php
// PostVis Admin
// 
// 20220101 GRBOFR creation

$time_offset = date("Z")/60/60; //Get current timezone offset from server timezone setting.

// ****************DO NOT EDIT BELOW THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING*****************//

// Mail Log / Quantine Logs Queries ###### Normally Should not have to Edit ########
// Queries are example queries provided by Amavis-New SQL documentation


$query_clean = "SELECT count(*) as cnt, avg(bspam_level), sender.domain FROM msgs LEFT JOIN msgrcpt on msgs.mail_id=msgrcpt.mail_id LEFT JOIN maddr AS sender ON msgs.sid=sender.id LEFT JOIN maddr AS recip ON msgrcpt.rid=recip.id WHERE msgs.content='C' GROUP BY sender.domain ORDER by cnt DESC LIMIT 20";

$query_spamavg = "SELECT count(*) as cnt, avg(bspam_level), sender.domain FROM msgs LEFT JOIN msgrcpt on msgs.mail_id=msgrcpt.mail_id LEFT JOIN maddr AS sender ON msgs.sid=sender.id LEFT JOIN maddr AS recip ON msgrcpt.rid=recip.id WHERE msgs.content='S' GROUP BY sender.domain ORDER BY cnt DESC LIMIT 20";

$query_volume = "SELECT count(*) as cnt, avg(bspam_level), sender.domain FROM msgs LEFT JOIN msgrcpt on msgs.mail_id=msgrcpt.mail_id LEFT JOIN maddr AS sender ON msgs.sid=sender.id LEFT JOIN maddr AS recip ON msgrcpt.rid=recip.id GROUP BY sender.domain ORDER BY cnt DESC";

$query_spam_count = "SELECT count(*) as cnt, avg(bspam_level), sender.domain FROM msgs LEFT JOIN msgrcpt on msgs.mail_id=msgrcpt.mail_id LEFT JOIN maddr AS sender ON msgs.sid=sender.id LEFT JOIN maddr AS recip ON msgrcpt.rid=recip.id WHERE msgs.content='S' GROUP BY sender.domain ORDER BY cnt DESC";

$query_top_spam_scores = "SELECT count(*) as cnt, avg(bspam_level), sender.domain FROM msgs LEFT JOIN msgrcpt on msgs.mail_id=msgrcpt.mail_id LEFT JOIN maddr AS sender ON msgs.sid=sender.id LEFT JOIN maddr AS recip ON msgrcpt.rid=recip.id GROUP BY sender.domain ORDER BY bspam_level DESC";

$query_latestmail = "SELECT  UNIX_TIMESTAMP()-msgs.time_num AS age, SUBSTRING(policy,1,2) as pb,   msgs.content AS c, dsn_sent as dsn, ds, bspam_level AS level, size,   SUBSTRING(sender.email,1,18) AS s,   SUBSTRING(recip.email,1,18)  AS r,   SUBSTRING(msgs.subject,1,10) AS subj   FROM msgs LEFT JOIN msgrcpt         ON msgs.mail_id=msgrcpt.mail_id             LEFT JOIN maddr AS sender ON msgs.sid=sender.id LEFT JOIN maddr AS recip  ON msgrcpt.rid=recip.id   WHERE msgs.content IS NOT NULL AND UNIX_TIMESTAMP()-msgs.time_num < 120   ORDER BY msgs.time_num DESC;";

$query_top_ip = "SELECT count(*) as cnt, client_addr FROM msgs  LEFT JOIN msgrcpt on msgs.mail_id=msgrcpt.mail_id  LEFT JOIN maddr AS sender ON msgs.sid=sender.id  LEFT JOIN maddr AS recip ON msgrcpt.rid=recip.id  GROUP BY  client_addr ORDER BY 1 DESC";

// Cleanup Queries -- 
// Default Queries provided by Amavis SQL Documentation to tiddy up the SQL Tables that amavis uses

$msgscleanup1 = "DELETE FROM msgs WHERE time_iso < now() - INTERVAL 1 hour AND content IS NULL;";
$msgscleanup2 = "DELETE FROM maddr WHERE NOT EXISTS (SELECT 1 FROM msgs WHERE sid=id) AND NOT EXISTS (SELECT 1 FROM msgrcpt WHERE rid=id);";

$quarantinecleanup = "DELETE FROM quarantine WHERE NOT EXISTS (SELECT 1 FROM msgs WHERE mail_id=quarantine.mail_id)"; 
$quarantinecleanup2 = "DELETE FROM msgrcpt WHERE NOT EXISTS (SELECT 1 FROM msgs WHERE mail_id=msgrcpt.mail_id)";
$quarantinecleanup3 = 'DELETE FROM msgs WHERE time_iso < now()- INTERVAL 14 day AND (content=\'V\' OR content=\'S\');';


$awlcleanup1 = "DELETE FROM awl WHERE lastupdate <= DATE_SUB(SYSDATE(), INTERVAL 6 MONTH)";
$awlcleanup2 = "DELETE FROM awl WHERE lastupdate <= DATE_SUB(SYSDATE(), INTERVAL 6 MONTH)";

// Blacklist and Whitelist Queryies to display the lists

$whitelist_query = "SELECT wblist.rid, wblist.sid, wblist.wb, mailaddr.email as sender, users.email as recipient FROM wblist LEFT JOIN mailaddr ON wblist.sid = mailaddr.id LEFT JOIN users ON wblist.rid = users.id WHERE wblist.wb = 'W'";
$blacklist_query = "SELECT wblist.rid, wblist.sid, wblist.wb, mailaddr.email as sender, users.email as recipient FROM wblist LEFT JOIN mailaddr ON wblist.sid = mailaddr.id LEFT JOIN users ON wblist.rid = users.id WHERE wblist.wb = 'B'";
$wb_query = "SELECT wblist.rid, wblist.sid, wblist.wb, mailaddr.email as sender, users.email as recipient FROM wblist LEFT JOIN mailaddr ON wblist.sid = mailaddr.id LEFT JOIN users ON wblist.rid = users.id";


$quarantine_query = "SELECT DISTINCT quarantine.mail_id, secret_id, rs, bspam_level, (UNIX_TIMESTAMP(time_iso) + (3600 * $time_offset)) AS time_iso, SUBSTRING(sender.email,1,35) AS sender, SUBSTRING(recipient.email,1,28) AS recipient, size, msgs.content AS quaratinefor, SUBSTRING( subject, 1, 25) AS subject FROM `quarantine` LEFT JOIN msgrcpt ON msgrcpt.mail_id = quarantine.mail_id LEFT JOIN msgs ON msgs.mail_id = quarantine.mail_id LEFT JOIN maddr AS recipient ON msgrcpt.rid = recipient.id LEFT JOIN maddr AS sender ON msgs.sid = sender.id WHERE msgrcpt.rs != 'R' AND msgrcpt.rs != 'D'";

?>