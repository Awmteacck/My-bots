<?php

$pais = trim(substr($message, 5));


sendMessage($chat_id,$pais , $message_id);

if (empty($pais)) {
    sendMessage($chat_id, "❌ Wrong format! Use country abbreviations. For example, /fake us", $message_id);
    exit();
}


  $ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.fakexy.com/fake-address-generator-'.$pais.'');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$headers = array();
$headers[] = 'authority: www.fakexy.com';
$headers[] = 'method: GET';
$headers[] = 'path: /fake-address-generator-us';
$headers[] = 'scheme: https';
$headers[] = 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7';
$headers[] = 'accept-language: es,es-ES;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6';
$headers[] = 'sec-fetch-dest: document';
$headers[] = 'sec-fetch-mode: navigate';
$headers[] = 'sec-fetch-site: none';
$headers[] = 'sec-fetch-user: ?1';
$headers[] = 'upgrade-insecure-requests: 1';
$headers[] = 'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36 Edg/113.0.1774.50';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$curl = curl_exec($ch);
curl_close($ch);

$street = getstr($curl, '<td>Street</td>
        						<td>','</td>');
        						
$city = getstr($curl, '<td>City/Town</td>
        						<td>','</td>');	
	        				
$province = getstr($curl, '<td>State/Province/Region</td>
        						<td>','</td>');
        						
$zip = getstr($curl, '<td>Zip/Postal Code</td>
        						<td>','</td>');	        						
        						
$phone = getstr($curl, '<td>Phone Number</td>
        						<td>','</td>');
        						
$country = getstr($curl, '<td>Country</td>
        						<td>','</td>');
        				
$fullname = getstr($curl, '<td>Full Name</td>
	        				<td>','</td>');	  
	        				
$ssn = getstr($curl, '<td>Social Security Number</td>
	        				<td>','</td>');	 

if (strpos($curl, "Error was encountered") !== false) {
    sendMessage($chat_id, "❌ Wrong format! Use country abbreviations. For example, /fake us", $message_id);
    exit();
}

$cmdbin = "<em>
<b>[⨶] Country : </b> <code>$country</code>
<b>[⨶] City : </b> <code>$city</code>
<b>[⨶] State : </b> <code>$province</code>
<b>[⨶] Zip : </b> <code>$zip</code>
<b>[⨶] Street : </b> <code>$street</code>
━━━━━━━━━━━━━━━━━━
<b>[⨶] FullName : </b>  <code>$fullname</code>
<b>[⨶] SSN : </b> <code>$ssn</code>
<b>[⨶] PhoneNumber : </b> <code>$phone</code>
━━━━━━━━━━━━━━━━━━
<b>[🜲] USER </b> ➜ @$username
<b>[🜲] $propietario</b></em>";

sendMessage($chat_id, $cmdbin, $message_id);