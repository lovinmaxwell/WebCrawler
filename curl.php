<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://www.waterwellbids.com/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$output = curl_exec($ch);
curl_close($ch);
echo $output;

$dom = new DOMDocument();
   $dom->loadHTML($output);
  // print_r($dom->loadHTML($output));die();
   $xpath = new DOMXPath($dom);

   // This is the xpath for a number under a bar ....
   // /html/body/div[2]/div[1]/div/div/ul/li[6]/span
   // How may I get it?
   // The following code doesn't work, it's only to show my goals ..

   $greenWaitingNumber = $xpath->query('//*[@id="content"]/div/table/tr[2]/td[2]/a');
   //$theText = (string).$greenWaitingNumber;
print_r( $greenWaitingNumber);
