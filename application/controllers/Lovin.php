<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class Lovin extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
  }

   function row2()
  {
    $url = 'https://agents.farmers.com/ca?Source_Indicator=AP';
    $client = new Client();
    $request = $client->request('GET',$url);
    $response = $request->getBody()->getContents();
    $crawler = new Crawler($response);
    $writer = WriterFactory::Create(Type::XLSX);
    $output_file = 'C:\Users\Ellipsonic_WS\Desktop\agents.farmers\california132.xlsx';
    $writer->openToFile($output_file);
    $writer->addRow(['state','city','agentName','agentWebAdd','address1','address2','city','state','zipcode','licenceNO','Phone','fax']);
    for ($i=132; $i <= 260 ; $i++) {
      foreach ($crawler->filterXPath('//ul/li['.$i.']/a') as $citylinks) {
        echo "cityLink : ";print_r('https://agents.farmers.com/ca/'.$citylinks->getAttribute('href'));echo "<br>";
        $explode = explode('/',$citylinks->getAttribute('href'));
        // echo "explode ";print_r($explode);echo "<br>";
        $city = $explode[1];
        // echo "City: ".$city."<br>";
        echo "cityLink : ";print_r('https://agents.farmers.com/ca/'.$city);echo "<br>";
        $citylink='https://agents.farmers.com/ca/'.$city;
        $request2 = $client->request('GET',$citylink);
        $response2 = $request2->getBody()->getContents();
        $crawler2 = new Crawler($response2);
        $agentlink = $crawler2->filter('.location-title a')->count() > 0 ? $crawler2->filter('.location-title a')->attr('href') : "OOPS";
        $thirdLink = 'https://agents.farmers.com/'.$agentlink;
        foreach ($crawler2->filter('.location-title a') as $agentlinks) {
        echo "agentlinks: ";print_r('https://agents.farmers.com/'.$agentlinks->getAttribute('href'));echo "<br>";
        $agentlink = 'https://agents.farmers.com/'.$agentlinks->getAttribute('href');
        $request3 = $client->request('GET',$agentlink);
        $response3 = $request3->getBody()->getContents();
        $crawler3 = new Crawler($response3);
        $agentName = $crawler3->filter('.location-name')->count() > 0 ? $crawler3->filter('.location-name')->eq(0)->text() : "";
        // echo "agentName: ".$agentName."
        $licence = $crawler3->filter('.agent-license')->count() > 0 ? $crawler3->filter('.agent-license')->eq(0)->text() : "";
        $address1 = $crawler3->filter('.c-address-street-1')->count() > 0 ? $crawler3->filter('.c-address-street-1')->eq(0)->text() : "";
        // echo "address1: ".$address1."
        $address2 = $crawler3->filter('.c-address-street-2')->count() > 0 ? $crawler3->filter('.c-address-street-2')->eq(0)->text() : "";
        // echo "address2: ".$address2."
        $city = $crawler3->filter('.c-address-city')->count() > 0 ? $crawler3->filter('.c-address-city')->eq(0)->text() : "";
        // echo "city: ".$city."
        $state = $crawler3->filter('.c-address-state')->count() > 0 ? $crawler3->filter('.c-address-state')->eq(0)->text() : "" ;
        // echo "state: ".$state."
        $zip = $crawler3->filter('.c-address-postal-code')->count() > 0 ? $crawler3->filter('.c-address-postal-code')->eq(0)->text() : "";
        // echo "zip: ".$zip."
        $phone = $crawler3->filter('#telephone')->count() > 0 ? $crawler3->filter('#telephone')->eq(0)->text() : "";
        // echo "ph: ";print_r($phone);echo "
        $fax = $crawler3->filter('#fax-number')->count() > 0 ? $crawler3->filter('#fax-number')->eq(0)->text() : "";
        // echo "fax ";print_r($fax);echo "
        $writer->addRow(['california',$city,$agentName,$thirdLink,$address1,$address2,$city,$state,$zip,$licence,$phone,$fax]);
          }
        }
    }
    $writer->close();
  }

  public function agent_farmers_full_websit()
  {
		$url = 'https://agents.farmers.com/';
		$client = new Client();
		$request = $client->request('GET',$url);
		$response = $request->getBody()->getContents();
		$crawler = new Crawler($response);
		for ($z=4; $z <= 18; $z++) {
		foreach ($crawler->filterXPath('//div/div/div/div/ul/li['.$z.']/a') as  $statelink) {
		  $stateName=$statelink->nodeValue;
		  echo "<br>";echo " <h2> State:";print_r('https://agents.farmers.com/'.$statelink->getAttribute('href'));echo "</h2><br>";
      $state_Link='https://agents.farmers.com/'.$statelink->getAttribute('href').'/';
		  $file_formate = '.xlsx';
		  $file_dr = 'C:\Users\Prudence Labs\OneDrive\Prudence Lab\Shared Reference\Prudence General\Data Mining\agents.farmers.com\output\!by php\\';
		  $file_path = $file_dr.$stateName.$file_formate;
		  // echo "File Path: ";print_r($file_path);echo "<br>";
		  $file_dr2 = 'C:\Users\Prudence Labs\Desktop\General test\lovin\\';
		  $file_path2 = $file_dr2.$stateName.$file_formate;
		  $writer = WriterFactory::Create(Type::XLSX);
		  // $save_path = 'C:\Users\Prudence Labs\Desktop\General test\\';
		  $output_file = $file_path;
		  $writer->openToFile($output_file);
		  $writer->addRow(['state','city','Agent_Name','Agent_WebAddress','Address_Line_1','Address_Line_2','city','state','zipcode','licenceNO','Phone','fax']);

		  // print_r('https://agents.farmers.com/'.$statelink->getAttribute('href'));echo "<br>";
		  $request1 = $client->request('GET','https://agents.farmers.com/'.$statelink->getAttribute('href'));
		  $response1 = $request1->getBody()->getContents();
		  $crawler1 = new Crawler($response1);
		  //for ($c=1; $c <=35 ; $c++) {
				foreach ($crawler1->filter(' .c-directory-list-content-item a') as $citylink) {
				  //city xpath : //div/div/div/div/ul/li[1]/a   sel :  .c-directory-list-content-item a

				  // echo "city : ";print_r('https://agents.farmers.com/'.$citylink->getAttribute('href'));echo "<br>";
				  $explode = explode('/',$citylink->getAttribute('href'));
				  $city = $explode[1];
				  $city_link=$state_Link.$city;
				  // echo "cityLink : ";print_r($city_link);echo "<br>";
          echo "City &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : ".$city."<br>";

				  $request2 = $client->request('GET',$city_link);
				  $response2 = $request2->getBody()->getContents();
				  $crawler2 = new Crawler($response2);
				  // echo "city:   ";print_r('https://agents.farmers.com/'.$citylink->getAttribute('href'));
				  foreach ($crawler2->filter('.location-title a') as $agentLink) {
						// echo "agentLink: ";print_r('https://agents.farmers.com/'.$agentLink->getAttribute('href'));echo "<br>";
						$request3 = $client->request('GET','https://agents.farmers.com/'.$agentLink->getAttribute('href'));
						$response3 = $request3->getBody()->getContents();
						$crawler3 = new Crawler($response3);

						$agentlink = 'https://agents.farmers.com/'.$agentLink->getAttribute('href');
						$Agent_WebAddress = $agentlink;
						// echo "Agent_WebAddres: ".$Agent_WebAddress."<br>";
						$agentName = $crawler3->filter('.location-name')->count() > 0 ? $crawler3->filter('.location-name')->eq(0)->text() : "";
						// echo "agentName: ".$agentName."<br>";
						$licence = $crawler3->filter('.agent-license')->count() > 0 ? $crawler3->filter('.agent-license')->eq(0)->text() : "";
						// echo "licence: ".$licence."<br>";
						$address1 = $crawler3->filter('.c-address-street-1')->count() > 0 ? $crawler3->filter('.c-address-street-1')->eq(0)->text() : "";
						// echo "address1: ".$address1."<br>";
						$address2 = $crawler3->filter('.c-address-street-2')->count() > 0 ? $crawler3->filter('.c-address-street-2')->eq(0)->text() : "";
						// echo "address2: ".$address2."<br>";
						$city = $crawler3->filter('.c-address-city')->count() > 0 ? $crawler3->filter('.c-address-city')->eq(0)->text() : "";
						// echo "City &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : ".$city."<br>";
						$state = $crawler3->filter('.c-address-state')->count() > 0 ? $crawler3->filter('.c-address-state')->eq(0)->text() : "" ;
						// echo "state: ".$state."<br>";
						$zip = $crawler3->filter('.c-address-postal-code')->count() > 0 ? $crawler3->filter('.c-address-postal-code')->eq(0)->text() : "";
						// echo "zip: ".$zip."<br>";
						$phone = $crawler3->filter('#telephone')->count() > 0 ? $crawler3->filter('#telephone')->eq(0)->text() : "";
						// echo "ph: ";print_r($phone);echo "<br>";
						$fax = $crawler3->filter('#fax-number')->count() > 0 ? $crawler3->filter('#fax-number')->eq(0)->text() : "";
						// echo "fax: ";print_r($fax);echo "<br>";
						$writer->addRow([$stateName,$city,$agentName,$Agent_WebAddress,$address1,$address2,$city,$state,$zip,$licence,$phone,$fax]);
            // $writer->close();die();
					}

				}
			//}

				$writer->close();
		}
	}
}

  public function demo()
  {
    echo "Project Web Crawler :https://www.travelers.com/FindAgent/All";echo "<br>";
    $url = 'https://www.travelers.com/FindAgent/All';
    $client = new Client();
    $request = $client->request('GET',$url);
    $response = $request->getBody()->getContents();
    $crawler = new Crawler($response);
    // for ($z=3; $z <=4 ; $z++) {
      for ($j=13; $j >= 12 ; $j--) {
        foreach ($crawler->filterXPath('//div[1]/section/div[1]/div/ul[3]/li['.$j.']/a') as $stateLink) {
          // echo "Statelink: ";print_r('https://www.travelers.com'.$stateLink->getAttribute('href'));echo "<br>";
          echo "<h2>State: ";print_r("$stateLink->nodeValue");echo "</h2><br>";
          $str1 = '.xlsx';
          $str2 = 'C:\Users\Ellipsonic_WS\OneDrive\Prudence General\Data Mining\travelers.com_FindAgent_All\Output\\';
          $stateName=$stateLink->nodeValue;
          $new_str = $str2.$stateName.$str1;
          // echo "File_Path: ";print_r("$new_str");echo "<br>";
          $writer = WriterFactory::Create(Type::XLSX);
          $output_file = $new_str;
          $writer->openToFile($output_file);
          $writer->addRow(['State','City','City_URL','Agent_Name','Agent_WebAddress','Address_Line_1','Address_Line_2','Address_Line_3','Address_Line_4','Email']);
          // print_r('https://www.travelers.com'.$stateLink->getAttribute('href'));echo "<br>";
          $request1 = $client->request('GET','https://www.travelers.com'.$stateLink->getAttribute('href'));
          $response1 = $request1->getBody()->getContents();
          $crawler1 = new Crawler($response1);
          // $cities = $crawler1->filterXPath('//div[1]/section/div[1]/div[1]/ul[1]/li[1]/a')->attr('href');
          for ($i=1; $i <=26 ; $i++) {
            foreach ($crawler1->filterXPath('//div[1]/section/div[1]/div['.$i.']/ul/li/a') as $cities) {
              echo "City &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :";
              print_r($cities->nodeValue);echo "<br>";
              // echo "City_Link: ";print_r('https://www.travelers.com'.$cities->getAttribute('href'));echo "<br>";
              // print_r('https://www.travelers.com'.$cities);echo "<br>";
              $request2 = $client->request('GET','https://www.travelers.com'.$cities->getAttribute('href'));
              $response2 = $request2->getBody()->getContents();
              $crawler2 = new Crawler($response2);
              foreach ($crawler2->filterXPath('//*[@id="agent_table"]/div/div/span[1]/a') as $agency) {
                // $agency = $crawler2->filterXPath('//*[@id="agent_table"]/div/div/span[1]/a')->attr('href');
                $request3 = $client->request('GET','https://www.travelers.com'.$agency->getAttribute('href'));
                $response3 = $request3->getBody()->getContents();
                $crawler3 = new Crawler($response3);
                $City_URL='https://www.travelers.com'.$cities->getAttribute('href');
                // print_r($City_URL);echo "<br>";
                $Agent_Name = $crawler3->filterXPath('//html/body/div[1]/section/div[1]/h2')->eq(0)->text();
                // echo "Agent_Name: ";print_r($Agent_Name);echo "<br>";
                $Agent_WebAddress='https://www.travelers.com'.$agency->getAttribute('href');
                // echo "Agent_Web_Address &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : ";print_r($Agent_WebAddress);echo "<br>";
                $address1 =  $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[2]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[2]')->eq(0)->text(): "";
                // echo "Add 1: ";print_r($address1);echo "<br>";
                $address2 =  $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[4]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[4]')->eq(0)->text(): "";
                // echo "Add 2: ";print_r($address2);echo "<br>";
                $address3 =  $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[6]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[6]')->eq(0)->text(): "";
                // echo "Add 3: ";print_r($address3);echo "<br>";
                $address4 =  $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[7]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[7]')->eq(0)->text(): "";
                // echo "Add 4: ";print_r($address4);echo "<br>";
                $email = $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[8]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[8]')->eq(0)->text(): "";
                // echo "Email: ";print_r($email);echo "<br>";echo "<br>";
                $writer->addRow([$stateLink->nodeValue,$cities->nodeValue,$City_URL,$Agent_Name,$Agent_WebAddress,$address1,$address2,$address3,$address4,$email]);
              }

            }
          }
          $writer->close();
        }
      }
    // }


  }
  public function Puerto_Rico()
  {
    echo "Project Web Crawler :https://www.travelers.com/FindAgent/All";echo "<br>";
    $url = 'https://www.travelers.com/FindAgent/All';
    $client = new Client();
    $request = $client->request('GET',$url);
    $response = $request->getBody()->getContents();
    $crawler = new Crawler($response);
    // for ($z=3; $z <=4 ; $z++) {
      // for ($j=13; $j >= 12 ; $j--) {
        foreach ($crawler->filterXPath('//div[1]/section/div[1]/div/ul[3]/li[13]/a') as $stateLink) {
          // echo "Statelink: ";print_r('https://www.travelers.com'.$stateLink->getAttribute('href'));echo "<br>";
          echo "<h2>State: ";print_r("$stateLink->nodeValue");echo "</h2><br>";
          $str1 = '.xlsx';
          $str2 = 'C:\Users\Ellipsonic_WS\OneDrive\Prudence General\Data Mining\travelers.com_FindAgent_All\Output\\';
          $stateName=$stateLink->nodeValue;
          $new_str = $str2.$stateName.$str1;
          // echo "File_Path: ";print_r("$new_str");echo "<br>";
          $writer = WriterFactory::Create(Type::XLSX);
          $output_file = $new_str;
          $writer->openToFile($output_file);
          $writer->addRow(['State','City','City_URL','Agent_Name','Agent_WebAddress','Address_Line_1','Address_Line_2','Address_Line_3','Address_Line_4','Email']);
          // print_r('https://www.travelers.com'.$stateLink->getAttribute('href'));echo "<br>";
          $request1 = $client->request('GET','https://www.travelers.com'.$stateLink->getAttribute('href'));
          $response1 = $request1->getBody()->getContents();
          $crawler1 = new Crawler($response1);
          // $cities = $crawler1->filterXPath('//div[1]/section/div[1]/div[1]/ul[1]/li[1]/a')->attr('href');
          for ($i=1; $i <=26 ; $i++) {
            foreach ($crawler1->filterXPath('//div[1]/section/div[1]/div['.$i.']/ul/li/a') as $cities) {
              echo "City &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :";
              print_r($cities->nodeValue);echo "<br>";
              // echo "City_Link: ";print_r('https://www.travelers.com'.$cities->getAttribute('href'));echo "<br>";
              // print_r('https://www.travelers.com'.$cities);echo "<br>";
              $request2 = $client->request('GET','https://www.travelers.com'.$cities->getAttribute('href'));
              $response2 = $request2->getBody()->getContents();
              $crawler2 = new Crawler($response2);
              foreach ($crawler2->filterXPath('//*[@id="agent_table"]/div/div/span[1]/a') as $agency) {
                // $agency = $crawler2->filterXPath('//*[@id="agent_table"]/div/div/span[1]/a')->attr('href');
                $request3 = $client->request('GET','https://www.travelers.com'.$agency->getAttribute('href'));
                $response3 = $request3->getBody()->getContents();
                $crawler3 = new Crawler($response3);
                $City_URL='https://www.travelers.com'.$cities->getAttribute('href');
                // print_r($City_URL);echo "<br>";
                $Agent_Name = $crawler3->filterXPath('//html/body/div[1]/section/div[1]/h2')->eq(0)->text();
                // echo "Agent_Name: ";print_r($Agent_Name);echo "<br>";
                $Agent_WebAddress='https://www.travelers.com'.$agency->getAttribute('href');
                // echo "Agent_Web_Address &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : ";print_r($Agent_WebAddress);echo "<br>";
                $address1 =  $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[2]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[2]')->eq(0)->text(): "";
                // echo "Add 1: ";print_r($address1);echo "<br>";
                $address2 =  $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[4]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[4]')->eq(0)->text(): "";
                // echo "Add 2: ";print_r($address2);echo "<br>";
                $address3 =  $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[6]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[6]')->eq(0)->text(): "";
                // echo "Add 3: ";print_r($address3);echo "<br>";
                $address4 =  $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[7]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[7]')->eq(0)->text(): "";
                // echo "Add 4: ";print_r($address4);echo "<br>";
                $email = $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[8]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[8]')->eq(0)->text(): "";
                // echo "Email: ";print_r($email);echo "<br>";echo "<br>";
                $writer->addRow([$stateLink->nodeValue,$cities->nodeValue,$City_URL,$Agent_Name,$Agent_WebAddress,$address1,$address2,$address3,$address4,$email]);
              }

            }
          }
          $writer->close();
        }
      // }
    // }


  }
  public function Pennsylvania()
  {
    echo "Project Web Crawler :https://www.travelers.com/FindAgent/All";echo "<br>";
    $url = 'https://www.travelers.com/FindAgent/All';
    $client = new Client();
    $request = $client->request('GET',$url);
    $response = $request->getBody()->getContents();
    $crawler = new Crawler($response);
    // for ($z=3; $z <=4 ; $z++) {
      // for ($j=13; $j >= 12 ; $j--) {
        foreach ($crawler->filterXPath('//div[1]/section/div[1]/div/ul[3]/li[12]/a') as $stateLink) {
          // echo "Statelink: ";print_r('https://www.travelers.com'.$stateLink->getAttribute('href'));echo "<br>";
          echo "<h2>State: ";print_r("$stateLink->nodeValue");echo "</h2><br>";
          $str1 = '.xlsx';
          $str2 = 'C:\Users\Ellipsonic_WS\OneDrive\Prudence General\Data Mining\travelers.com_FindAgent_All\Output\\';
          $stateName=$stateLink->nodeValue;
          $new_str = $str2.$stateName.$str1;
          // echo "File_Path: ";print_r("$new_str");echo "<br>";
          $writer = WriterFactory::Create(Type::XLSX);
          $output_file = $new_str;
          $writer->openToFile($output_file);
          $writer->addRow(['State','City','City_URL','Agent_Name','Agent_WebAddress','Address_Line_1','Address_Line_2','Address_Line_3','Address_Line_4','Email']);
          // print_r('https://www.travelers.com'.$stateLink->getAttribute('href'));echo "<br>";
          $request1 = $client->request('GET','https://www.travelers.com'.$stateLink->getAttribute('href'));
          $response1 = $request1->getBody()->getContents();
          $crawler1 = new Crawler($response1);
          // $cities = $crawler1->filterXPath('//div[1]/section/div[1]/div[1]/ul[1]/li[1]/a')->attr('href');
          for ($i=1; $i <=26 ; $i++) {
            foreach ($crawler1->filterXPath('//div[1]/section/div[1]/div['.$i.']/ul/li/a') as $cities) {
              echo "City &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :";
              print_r($cities->nodeValue);echo "<br>";
              // echo "City_Link: ";print_r('https://www.travelers.com'.$cities->getAttribute('href'));echo "<br>";
              // print_r('https://www.travelers.com'.$cities);echo "<br>";
              $request2 = $client->request('GET','https://www.travelers.com'.$cities->getAttribute('href'));
              $response2 = $request2->getBody()->getContents();
              $crawler2 = new Crawler($response2);
              foreach ($crawler2->filterXPath('//*[@id="agent_table"]/div/div/span[1]/a') as $agency) {
                // $agency = $crawler2->filterXPath('//*[@id="agent_table"]/div/div/span[1]/a')->attr('href');
                $request3 = $client->request('GET','https://www.travelers.com'.$agency->getAttribute('href'));
                $response3 = $request3->getBody()->getContents();
                $crawler3 = new Crawler($response3);
                $City_URL='https://www.travelers.com'.$cities->getAttribute('href');
                // print_r($City_URL);echo "<br>";
                $Agent_Name = $crawler3->filterXPath('//html/body/div[1]/section/div[1]/h2')->eq(0)->text();
                // echo "Agent_Name: ";print_r($Agent_Name);echo "<br>";
                $Agent_WebAddress='https://www.travelers.com'.$agency->getAttribute('href');
                // echo "Agent_Web_Address &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : ";print_r($Agent_WebAddress);echo "<br>";
                $address1 =  $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[2]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[2]')->eq(0)->text(): "";
                // echo "Add 1: ";print_r($address1);echo "<br>";
                $address2 =  $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[4]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[4]')->eq(0)->text(): "";
                // echo "Add 2: ";print_r($address2);echo "<br>";
                $address3 =  $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[6]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[6]')->eq(0)->text(): "";
                // echo "Add 3: ";print_r($address3);echo "<br>";
                $address4 =  $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[7]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[7]')->eq(0)->text(): "";
                // echo "Add 4: ";print_r($address4);echo "<br>";
                $email = $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[8]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[8]')->eq(0)->text(): "";
                // echo "Email: ";print_r($email);echo "<br>";echo "<br>";
                $writer->addRow([$stateLink->nodeValue,$cities->nodeValue,$City_URL,$Agent_Name,$Agent_WebAddress,$address1,$address2,$address3,$address4,$email]);
              }

            }
          }
          $writer->close();
        }
      // }
    // }


  }
  function traveler()
 {
   $url = 'https://www.travelers.com/FindAgent/All';
   $client = new Client();
   $request = $client->request('GET',$url);
   $response = $request->getBody()->getContents();
   $crawler = new Crawler($response);
   $writer = WriterFactory::Create(Type::XLSX);
   $output_file = 'C:\Users\Ellipsonic_WS\Desktop\agents.farmers\alltravels.xlsx';
   $writer->openToFile($output_file);
   $writer->addRow(['state','city','title','address1','address2','address3','address4','email']);
   // for ($j=1; $j <=1 ; $j++) {
     foreach ($crawler->filterXPath('//div[1]/section/div[1]/div/ul[1]/li/a') as $stateLink) {
       print_r('https://www.travelers.com'.$stateLink->getAttribute('href'));echo "<br>";
       $request1 = $client->request('GET','https://www.travelers.com'.$stateLink->getAttribute('href'));
       $response1 = $request1->getBody()->getContents();
       $crawler1 = new Crawler($response1);
       // $cities = $crawler1->filterXPath('//div[1]/section/div[1]/div[1]/ul[1]/li[1]/a')->attr('href');
       for ($i=1; $i <=26 ; $i++) {
         foreach ($crawler1->filterXPath('//div[1]/section/div[1]/div['.$i.']/ul/li/a') as $cities) {
           print_r($cities->nodeValue);
           print_r('https://www.travelers.com'.$cities->getAttribute('href'));echo "<br>";
           // print_r('https://www.travelers.com'.$cities);echo "<br>";
           $request2 = $client->request('GET','https://www.travelers.com'.$cities->getAttribute('href'));
           $response2 = $request2->getBody()->getContents();
           $crawler2 = new Crawler($response2);
           foreach ($crawler2->filterXPath('//*[@id="agent_table"]/div/div/span[1]/a') as $agency) {
             // $agency = $crawler2->filterXPath('//*[@id="agent_table"]/div/div/span[1]/a')->attr('href');
             print_r('https://www.travelers.com'.$agency->getAttribute('href'));echo "<br>";
             $request3 = $client->request('GET','https://www.travelers.com'.$agency->getAttribute('href'));
             $response3 = $request3->getBody()->getContents();
             $crawler3 = new Crawler($response3);
             $title = $crawler3->filterXPath('//html/body/div[1]/section/div[1]/h2')->eq(0)->text();
             print_r($title);
             $address1 =  $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[2]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[2]')->eq(0)->text(): "";
             print_r($address1);echo "<br>";
             $address2 =  $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[4]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[4]')->eq(0)->text(): "";
             print_r($address2);echo "<br>";
             $address3 =  $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[6]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[6]')->eq(0)->text(): "";
             print_r($address3);echo "<br>";
             $address4 =  $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[7]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[7]')->eq(0)->text(): "";
             print_r($address4);echo "<br>";
             $email = $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[8]')->count() > 0 ? $crawler3->filterXPath('//html/body/div[1]/section/div[1]/div[2]/div[8]')->eq(0)->text(): "";
             print_r($email);echo "<br>";
             $writer->addRow([$stateLink->nodeValue,$cities->nodeValue,$title,$address1,$address2,$address3,$address4,$email]);
           }

         }
       }
     }
   // }

   // $stateLink=$crawler->filterXPath('//div[1]/section/div[1]/div/ul[1]/li[1]/a')->attr('href');


$writer->close();
 }


 function State_form(){
   $url = 'https://www.statefarm.com/agent/US';
     $client = new Client();
     $request = $client->request('GET',$url);
     $response = $request->getBody()->getContents();
     $crawler = new Crawler($response);
     $State_data =array();
     foreach ($crawler->filter('.sfx-text  a') as $link) {
       $State_data[]=$link;
    }
for ($i=13; $i <=34 ; $i++) {
  // $state_link = 'https://www.statefarm.com'.$State_data[$i]->getAttribute('href');

     // foreach ($crawler->filter('.sfx-text  a') as $link) {
      $state_link = 'https://www.statefarm.com'.$State_data[$i]->getAttribute('href');
       $request1 = $client->request('GET','https://www.statefarm.com'.$State_data[$i]->getAttribute('href'));
   // $state_link="https://www.statefarm.com/agent/US/IL";
   //  $request1 = $client->request('GET',$state_link);  #stateListUS #stateListUS > div:nth-child(1) > div > ul > li:nth-child(1)
   //*[@id="stateListUS"]/div[1]/div/ul/li[1]
   //*[@id="stateListUS"]/div[2]/div/ul/li[2]
       $response1 = $request1->getBody()->getContents();
       $crawler1 = new Crawler($response1);
       echo "<h2>State: ";print_r($State_data[$i]->nodeValue);echo "</h2><br>";
       $str1 = '.xlsx';
       $str2 = 'D:\OneDrive\Prudence Lab\Shared Reference\Prudence General\Data Mining\stateform\php\full\\';
       $stateName=$State_data[$i]->nodeValue;
       // print_r($stateName);die();
       // $stateName='Illinois';
       $new_str = $str2.$stateName.$str1;

       $writer = WriterFactory::Create(Type::XLSX);
       $output_file = $new_str;
       $writer->openToFile($output_file);
       $writer->addRow(['State','City','City_URL','Agent_Name','Agent_WebAddress','Address_Line_1','Address_Line_2','Address_Line_3','Address_Line_4','Address_Line_5','Zip','Phone_Number','Fax','Agent_WebSite','License','Email']);

       foreach ($crawler1->filter('.sfx-text a') as $cities) {
         // print_r('https://www.statefarm.com'.$cities->getAttribute('href'));
         $citie='https://www.statefarm.com'.$cities->getAttribute('href');
         $City_Name=$cities->nodeValue;
         echo "City &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :";print_r($City_Name);echo "<br>";
         $request2 = $client->request('GET','https://www.statefarm.com'.$cities->getAttribute('href'));
         $response2 = $request2->getBody()->getContents();
         $crawler2 = new Crawler($response2);
         $AgentDetails = $crawler2->filter('.toggle-content a');
         foreach ($AgentDetails as $main) {
           $Agent_link = $main->getAttribute('href');
           // $temp_test_url="https://www.statefarm.com/agent/US/TX/Flower-mound/Brett-Darby-6HQL93JG000";
           // $temp_test_url2="https://www.statefarm.com/agent/US/AL/Abbeville/Ron-Abernathy-CYLNB1YS000";
            $request3 = $client->request('GET',$Agent_link);
            $response3 = $request3->getBody()->getContents();
            $crawler3 = new Crawler($response3);
            $Agent_Name =$crawler3->filter('#AgentNameLabelId > span')->count() > 0 ? $crawler3->filter('#AgentNameLabelId > span')->eq(0)->text(): "";
            // echo "Name :";print_r($Agent_Name);echo "<br>";
            // echo "Agent_Web_Address :";print_r($Agent_link);echo "<br>";
            for ($i=1; $i <= 3; $i++) {
              $address =$crawler3->filterXPath('//*[@id="locStreetContent_mainLocContent"]/text()['.$i.']')->count() > 0 ? $crawler3->filterXPath('//*[@id="locStreetContent_mainLocContent"]/text()['.$i.']')->eq(0)->text(): "";
              // echo "Add :";print_r($address);echo "<br>";
              $address1=$address =$crawler3->filterXPath('//*[@id="locStreetContent_mainLocContent"]/text()[1]')->count() > 0 ? $crawler3->filterXPath('//*[@id="locStreetContent_mainLocContent"]/text()[1]')->eq(0)->text(): "";
              $address2=$address =$crawler3->filterXPath('//*[@id="locStreetContent_mainLocContent"]/text()[2]')->count() > 0 ? $crawler3->filterXPath('//*[@id="locStreetContent_mainLocContent"]/text()[2]')->eq(0)->text(): "";
              $address3=$address =$crawler3->filterXPath('//*[@id="locStreetContent_mainLocContent"]/text()[3]')->count() > 0 ? $crawler3->filterXPath('//*[@id="locStreetContent_mainLocContent"]/text()[3]')->eq(0)->text(): "";
                }
                $addressLocality =$crawler3->filter('#panelourLocation > div.span5 > div:nth-child(3) > div:nth-child(2) > span > span:nth-child(1)')->count() > 0 ? $crawler3->filter('#panelourLocation > div.span5 > div:nth-child(3) > div:nth-child(2) > span > span:nth-child(1)')->eq(0)->text(): "";
                // $City_Name=explode(',',$addressLocality);
                // die($City_Name[0]);
                // echo "Address_Line_3 :";print_r($addressLocality);echo "<br>";
                $addressRegion =$crawler3->filter('#panelourLocation > div.span5 > div:nth-child(3) > div:nth-child(2) > span > span:nth-child(2)')->count() > 0 ? $crawler3->filter('#panelourLocation > div.span5 > div:nth-child(3) > div:nth-child(2) > span > span:nth-child(2)')->eq(0)->text(): "";
                // echo "Address_Line_4 :";print_r($addressRegion);echo "<br>";
                $postalCode =$crawler3->filter('#panelourLocation > div.span5 > div:nth-child(3) > div:nth-child(2) > span > span:nth-child(3)')->count() > 0 ? $crawler3->filter('#panelourLocation > div.span5 > div:nth-child(3) > div:nth-child(2) > span > span:nth-child(3)')->eq(0)->text(): "";
                // echo "ZIP :";print_r($postalCode);echo "<br>";
            $Phone =$crawler3->filter('#offNumber_tab_mainLocContent_0 > span')->count() > 0 ? $crawler3->filter('#offNumber_tab_mainLocContent_0 > span')->eq(0)->text(): "";
            // echo "Phone:";print_r($Phone);echo "<br>";
            $Fax =$crawler3->filter('#faxNumber_mainLocContent > span')->count() > 0 ? $crawler3->filter('#faxNumber_mainLocContent > span')->eq(0)->text(): "";
            // echo "Fax :";print_r($Fax);echo "<br>";

            if($crawler3->filter('#panelourLocation > div.span5> div.hidden-phone > div:nth-child(2) > span')){
                $Lic =  $crawler3->filter('#panelourLocation > div.span5> div.hidden-phone > div:nth-child(2) > span')->count() > 0 ?   $crawler3->filter('#panelourLocation > div.span5> div.hidden-phone > div:nth-child(2) > span')->eq(0)->text():"";
                  }
            elseif ($crawler3->filter('#panelourLocation > div.span5 > div:nth-child(17) > div:nth-child(2) > span')) {
                $Lic = $crawler3->filter('#panelourLocation > div.span5 > div:nth-child(17) > div:nth-child(2) > span')->count() > 0 ? $crawler3->filter('#panelourLocation > div.span5 > div:nth-child(17) > div:nth-child(2) > span')->eq(0)->text():"";
            }
            elseif ($crawler3->filter('#panelourLocation > div.span5 > div:nth-child(20) > div:nth-child(2) > span')) {
              $Lic =   $crawler3->filter('#panelourLocation > div.span5 > div:nth-child(20) > div:nth-child(2) > span')->count() > 0 ? $crawler3->filter('#panelourLocation > div.span5 > div:nth-child(20) > div:nth-child(2) > span')->eq(0)->text():"";
            }
            elseif ($crawler3->filter('#panelourLocation > div.span5 > div:nth-child(18) > div:nth-child(2) > span')) {
                $Lic = $crawler3->filter('#panelourLocation > div.span5 > div:nth-child(18) > div:nth-child(2) > span')->count() > 0 ? $crawler3->filter('#panelourLocation > div.span5 > div:nth-child(18) > div:nth-child(2) > span')->eq(0)->text():"";
            }
            // echo "Lic:";print_r($Lic);echo "<br>";
            $website =$crawler3->filter('#agentlink_mainLocContent_0')->count() > 0 ? $crawler3->filter('#agentlink_mainLocContent_0')->eq(0)->text(): "";
            // echo "website :";print_r($website);echo "<br>";
            $email="";
            $writer->addRow([$stateName,$City_Name,$citie,$Agent_Name,$Agent_link,$address1,$address2,$address3,$addressLocality,$addressRegion,$postalCode,$Phone,$Fax,$website,$Lic,$email]);
            // $writer->addRow(['State','City','City_URL','Agent_Name','Agent_WebAddress','Address_Line_1','Address_Line_2','Address_Line_3','Address_Line_4','Address_Line_5','Zip','Phone_Number','Fax','Agent_WebSite','Email']);
         // $writer->close();
          }


       }
           $writer->close();
     }

 }


}

 ?>
