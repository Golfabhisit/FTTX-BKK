<?php
#-------------------------[Include]-------------------------#
require_once('./include/line_class.php');
require_once('./unirest-php-master/src/Unirest.php');
#-------------------------[Token]-------------------------#
$channelAccessToken = 'ZlhhA5Fiilnmt1dIjtCaXhHTitcuNEe17Xt6urxAbDL+kSYvZFZIQGkWyySS+cHNZQbeOUP0LQnsWt0Xhxicn8UzQqS1bd+L5cBdThhIBW64mDQk+5ZfawPcQ/Yu0R8JSbsMdPnPytgbwffmdS/HzQdB04t89/1O/w1cDnyilFU='; 
$channelSecret = '8e0cddeba7891bcf205e2e331d433833';
#-------------------------[Events]-------------------------#
$client = new LINEBotTiny($channelAccessToken, $channelSecret);
$userId     = $client->parseEvents()[0]['source']['userId'];
$groupId    = $client->parseEvents()[0]['source']['groupId'];
$replyToken = $client->parseEvents()[0]['replyToken'];
$timestamp  = $client->parseEvents()[0]['timestamp'];
$type       = $client->parseEvents()[0]['type'];
$message    = $client->parseEvents()[0]['message'];
$profile    = $client->profil($userId);
$repro = json_encode($profile);
$messageid  = $client->parseEvents()[0]['message']['id'];
$msg_type      = $client->parseEvents()[0]['message']['type'];
$msg_message   = $client->parseEvents()[0]['message']['text'];
$msg_title     = $client->parseEvents()[0]['message']['title'];
$msg_address   = $client->parseEvents()[0]['message']['address'];
$msg_latitude  = $client->parseEvents()[0]['message']['latitude'];
$msg_longitude = $client->parseEvents()[0]['message']['longitude'];
#----command option----#
$usertext = explode(" ", $message['text']);
$command = $usertext[0];
$options = $usertext[1];
if (count($usertext) > 2) {
    for ($i = 2; $i < count($usertext); $i++) {
        $options .= '+';
        $options .= $explode[$i];
    }
}
#------------------------------------------
$modex = file_get_contents('./user/' . $userId . 'mode.json');
if ($modex == 'Normal') {
    #$uri = "https://itdev.win/test";
    #$urikey = file_get_contents($uri);
    #$json = json_decode($urikey, true);
    $uri = "https://script.google.com/macros/s/AKfycbwOS1oiZsGYH1rwmVBGcIfc8e9d5BL51X4HBuW-gbwoz9qLmNJh/exec";
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);
    $results = array_filter($json['user'], function($user) use ($command) {
    return $user['RAN ID'] == $command;
    }
  );
$i=0;
$bb = array();
foreach($results as $resultsz){
$bb[$i] = $resultsz;
$i++;
}
$site01 .= $bb['0']['RAN ID'];
$site02 .= $bb['1']['RAN ID'];
$site03 .= $bb['2']['RAN ID'];
$site04 .= $bb['3']['RAN ID'];
$site05 .= $bb['4']['RAN ID'];
$site06 .= $bb['5']['RAN ID'];
if(empty($site01)) {
  $site01 .= '#N/A';
}
if(empty($site02)) {
  $site02 .= '#N/A';
}
if(empty($site03)) {
  $site03 .= '#N/A';
}
if(empty($site04)) {
  $site04 .= '#N/A';
}
if(empty($site05)) {
  $site05 .= '#N/A';
}
if(empty($site06)) {
  $site06 .= '#N/A';
}
$textz .= "PLEASE SPECIFY *RAN ID* FOR YOUR SEARCH.";
if(empty($results)) {
      $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array( 
          array(
                'type' => 'text',
                'text' => 'INFORMATION NOT FOUND.'
)
        )
      );
    }
else {
    $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array( 
          array(
                'type' => 'text',
                'text' => $textz,
                'quickReply' => array(
                'items' => array(
                                   array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $site01,
                'text' => $site01
                                 )
              ),array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $site02,
                'text' => $site02
                                 )
              ),array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $site03,
                'text' => $site03
                                 )
              ),array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $site04,
                'text' => $site04
                                 )
              )
              ,array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $site05,
                'text' => $site05
                                 )
              )
              ,array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $site06,
                'text' => $site06
                                 )
              )
                                )
                                     )
     )
     )
     );
$enbb = json_encode($bb);
    file_put_contents('./user/' . $userId . 'data.json', $enbb);
    file_put_contents('./user/' . $userId . 'mode.json', 'keyword');
    }
}
elseif ($modex == 'keyword') {
    $urikey = file_get_contents('./user/' . $userId . 'data.json');
    $deckey = json_decode($urikey, true);
    $results = array_filter($deckey, function($user) use ($command) {
    return $user['RAN ID'] == $command;
    }
  );
$i=0;
$zaza = array();
foreach($results as $resultsz){
$zaza[$i] = $resultsz;
$i++;
}
$enzz = json_encode($zaza);
    file_put_contents('./user/' . $userId . 'data.json', $enzz);
$text .= 'SITE : ' . $zaza[0]['SITE ID'];
$text .= "\n";
$text .= 'SITE ID : ' . $zaza[0]['RAN ID'];
$text .= "\n";
$text .= 'SITE NAME : ' . $zaza[0]['SITE NAME'];
$text .= "\n";
$text .= 'TEAM : ' . $zaza[0]['TEAM'];
$text .= "\n";
$text .= 'SITE STATUS : ' . $zaza[0]['SITE STATUS'];
$text .= "\n";
$text .= 'SDE SSR : ' . $zaza[0]['SDE SSR'];
$text .= "\n";
$text .= 'SDE RSA : ' . $zaza[0]['SDE RSA'];
$text .= "\n";
$text .= 'STATUS PHOTO FOR SUB ECT <Group Line> : ' . $zaza[0]['STATUS PHOTO FOR SUB ECT <Group Line>'];
$text .= "\n";
$text .= 'SIGNATURE PAT SDE : ' . $zaza[0]['SIGNATURE PAT SDE'];
$text .= "\n";
$text .= 'PHOTO ON NAS : ' . $zaza[0]['PHOTO ON NAS'];
$text .= "\n";
$text .= 'REMARK : ' . $zaza[0]['REMARK'];
$text .= "\n";
$text .= 'LAST UPDATE : ' . $zaza[0]['LAST UPDATE'];
$text .= "\n";
$text .= 'REMARK PHOTO : ' . $zaza[0]['REMARK PHOTO'];
$text .= "\n";
$text .= 'STATUS PHOTO : ' . $zaza[0]['STATUS PHOTO'];
$text .= "\n";
$text .= 'SUBMITED SDE DATA : ' . $zaza[0]['SUBMITED SDE DATA'];
$text .= "\n";
$text .= 'STATUS SDE : ' . $zaza[0]['STATUS SDE'];
$text .= "\n";
$text .= 'REMARK SDE : ' . $zaza[0]['REMARK SDE'];
    $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array( 
          array(
                'type' => 'text',
                'text' => $text
     )
     )
     );
    file_put_contents('./user/' . $userId . 'mode.json', 'Normal');
}
else {
  file_put_contents('./user/' . $userId . 'mode.json', 'Normal');
}
if (isset($mreply)) {
    $result = json_encode($mreply);
    $client->replyMessage($mreply);
}  
?>
