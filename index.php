<?php
header("Access-Control-Allow-Origin: *");


// Check if UTF-8 response requested
header("Content-Type: application/json; charset=UTF-8");

$rounds = 1;
$proxy = getRandomProxy();


// Get the url to load through the proxy
{
  // Url Provided, continue loading rounds
  $url = "http://duckduckgo.com";
  $result = proxy($proxy, $url);

  $testProx = "";
  while(strlen($result) < 5)
  {
    $testProx = getRandomProxy();
    $result = proxy($testProx, $url);
    break;
  }
  if(strlen($proxy) > 2)
  {
  $loop = 0;
  while ($loop < $rounds)
   {
       proxy($testProx, $url, 1000);
       $loop++;
    }
  }

 // finished round's, response
  http_response_code(200);
 $response = array(["proxy"=> $testProx]);
  echo json_encode($response,JSON_PRETTYPRINT);
  exit;
}




// functions for program
function proxy($proxy, $url, $dur = 1000){
$url = ($url);
$agent = getRandomAgent();
$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, trim($proxy));
curl_setopt($ch, CURLOPT_URL, $url);
$ref = "http://google.com";
curl_setopt($ch, CURLOPT_REFERER, $ref);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT_MS, $dur);
curl_setopt($ch, CURLOPT_USERAGENT,  $agent);
curl_setopt($ch, CURLOPT_HEADER, 0);
$page = curl_exec($ch);
curl_close($ch);
return $page;
}

function getRandomProxy()
{
  $proxies = file('https://api.proxyscrape.com/?request=getproxies&proxytype=http&timeout=2000&country=all&ssl=all&anonymity=all');
 return trim($proxies[array_rand($proxies,1)]);
}

function getRandomAgent()
{
if (hasParam('agent'))
{
  return $_GET['agent'];
}
else
{
$bits = file('agent.list');
 return trim($bits[array_rand($bits,1)]);
}
}


function hasParam($param) 
{
   return array_key_exists($param, $_REQUEST);
}

?>
