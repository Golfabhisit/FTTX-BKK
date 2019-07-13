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


if ($command == 'exit') {
    file_put_contents('./user/' . $userId . 'mode.json', 'step1');
  }
else {
#-get user mode-#
$modex = file_get_contents('./user/' . $userId . 'mode.json'); 
if(empty($modex)) {
    file_put_contents('./user/' . $userId . 'mode.json', 'step1');
}


if ($modex == 'step1') {
  #-get data form gg sheet-#
    $uri = "https://script.google.com/macros/s/AKfycbwOS1oiZsGYH1rwmVBGcIfc8e9d5BL51X4HBuW-gbwoz9qLmNJh/exec";
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);

  #-filter data form gg sheet by command-#  
    $results = array_filter($json['user'], function($user) use ($command) {
    return $user['SITE ID'] == $command;
    }
  );


#-sort array-#
$i=0;
$xsortdata = array();
foreach($results as $resultsz){
$xsortdata[$i] = $resultsz;
$i++;
}

#-encode $xsortdata array to json-#
$enxsortdata = json_encode($xsortdata);
#-put encoded data to file-#
file_put_contents('./user/' . $userId . 'data.json', $enxsortdata);



#-get choice by ran id-#
$site01 .= $xsortdata['0']['RAN ID'];
$site02 .= $xsortdata['1']['RAN ID'];
$site03 .= $xsortdata['2']['RAN ID'];
$site04 .= $xsortdata['3']['RAN ID'];
$site05 .= $xsortdata['4']['RAN ID'];
$site06 .= $xsortdata['5']['RAN ID'];

#-check data empty-#
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



$textz .= "PLEASE SPECIFY *RAN ID* FOR YOUR SEARCH";
#-เมื่ออยู่ใน step1 และค้นหาด้วย keyword ที่ไม่ตรงกับข้อมูลใดๆ-#
if(empty($results)) {
      $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array( 
          array(
                'type' => 'text',
                'text' => 'INFORMATION NOT FOUND'
)
        )
      );
    }
    #-เมื่ออยู่ใน step1 และค้นพบข้อมูล-#
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
                'label' => 'Cancel Search',
                'text' => 'exit'
                                 )
              ),array(
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


    #-put keyword mode for next step-#
    file_put_contents('./user/' . $userId . 'mode.json', 'step2');
    }
}

#-check user mode-#
elseif ($modex == 'step2') {
  #-get data form json file-#
    $urikey = file_get_contents('./user/' . $userId . 'data.json');
    $deckey = json_decode($urikey, true);
    #-filter data form json file by command-# 
    $results = array_filter($deckey, function($user) use ($command) {
    return $user['RAN ID'] == $command;
    }
  );


#-sort array-#
$i=0;
$sortdata = array();
foreach($results as $resultsz){
$sortdata[$i] = $resultsz;
$i++;
}

$ensortdata = json_encode($sortdata);
file_put_contents('./user/' . $userId . 'databykeyword.json', $ensortdata);

#-ครั้งแรกที่เลือก ran id เพื่อแสดงผลลัพธ์ จะดึงข้อมูลโดยตรงจากผลลัพธ์ของการเรียง array ใหม่ $sortdata-#
$text .= 'SITE ID : ' . $sortdata[0]['SITE ID'];
$text .= "\n";
$text .= 'RAN ID : ' . $sortdata[0]['RAN ID'];
$text .= "\n";
$text .= 'SITE NAME : ' . $sortdata[0]['SITE NAME'];
$text .= "\n";
$text .= 'TEAM : ' . $sortdata[0]['TEAM'];
$text .= "\n";
$text .= 'SITE STATUS : ' . $sortdata[0]['SITE STATUS'];
$text .= "\n";
$text .= 'SDE SSR : ' . $sortdata[0]['SDE SSR'];
$text .= "\n";
$text .= 'SDE RSA : ' . $sortdata[0]['SDE RSA'];
$text .= "\n";
$text .= 'STATUS PHOTO FOR SUB ECT <Group Line> : ' . $sortdata[0]['STATUS PHOTO FOR SUB ECT <Group Line>'];
$text .= "\n";
$text .= 'SIGNATURE PAT SDE : ' . $sortdata[0]['SIGNATURE PAT SDE'];
$text .= "\n";
$text .= 'PHOTO ON NAS : ' . $sortdata[0]['PHOTO ON NAS'];
$text .= "\n";
$text .= 'REMARK : ' . $sortdata[0]['REMARK'];
$text .= "\n";
$text .= 'LAST UPDATE : ' . $sortdata[0]['LAST UPDATE'];
$text .= "\n";
$text .= 'REMARK PHOTO : ' . $sortdata[0]['REMARK PHOTO'];
$text .= "\n";
$text .= 'STATUS PHOTO : ' . $sortdata[0]['STATUS PHOTO'];
$text .= "\n";
$text .= 'SUBMITED SDE DATA : ' . $sortdata[0]['SUBMITED SDE DATA'];
$text .= "\n";
$text .= 'STATUS SDE : ' . $sortdata[0]['STATUS SDE'];
$text .= "\n";
$text .= 'REMARK SDE : ' . $sortdata[0]['REMARK SDE'];

    $uribykey = file_get_contents('./user/' . $userId . 'data.json');
    $decbykey = json_decode($uribykey, true);

#-get choice by ran id-#
$sitekey01 .= $decbykey['0']['RAN ID'];
$sitekey02 .= $decbykey['1']['RAN ID'];
$sitekey03 .= $decbykey['2']['RAN ID'];
$sitekey04 .= $decbykey['3']['RAN ID'];
$sitekey05 .= $decbykey['4']['RAN ID'];
$sitekey06 .= $decbykey['5']['RAN ID'];

#-check data empty-#
if(empty($sitekey01)) {
  $sitekey01 .= '#N/A';
}
if(empty($sitekey02)) {
  $sitekey02 .= '#N/A';
}
if(empty($sitekey03)) {
  $sitekey03 .= '#N/A';
}
if(empty($sitekey04)) {
  $sitekey04 .= '#N/A';
}
if(empty($sitekey05)) {
  $sitekey05 .= '#N/A';
}
if(empty($sitekey06)) {
  $sitekey06 .= '#N/A';
}

#-เมื่ออยู่ใน step2 และค้นหาด้วย keyword ที่ไม่ตรงกับข้อมูลใดๆ จะส่ง quickreply ที่มีรายการ ran id จากการค้นหาครั้งแรก ให้ user เลือกอีกครั้ง หรือสามารถเลือกออกจากการค้นหาได้-#
if(empty($results)) {
    $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array( 
          array(
                'type' => 'text',
                'text' => 'INFORMATION NOT FOUND',
                'quickReply' => array(
                'items' => array(
                                   array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => 'Cancel Search',
                'text' => 'exit'
                                 )
              ),array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $sitekey01,
                'text' => $sitekey01
                                 )
              ),array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $sitekey02,
                'text' => $sitekey02
                                 )
              ),array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $sitekey03,
                'text' => $sitekey03
                                 )
              ),array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $sitekey04,
                'text' => $sitekey04
                                 )
              )
              ,array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $sitekey05,
                'text' => $sitekey05
                                 )
              )
              ,array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $sitekey06,
                'text' => $sitekey06
                                 )
              )
                                )
                                     )
     )
     )
     );

    }
    #-เมื่ออยู่ใน step2 และเลือก keyword ตามรายการ จะแสดงผลลัพธ์ และจะส่ง quickreply ที่มีรายการ ran id จากการค้นหาครั้งแรกให้ user เลือกอีกครั้ง หรือสามารถเลือกออกจากการค้นหาได้-#
else {
    $mreply = array(
        'replyToken' => $replyToken,
        'messages' => array( 
          array(
                'type' => 'text',
                'text' => $text,
                'quickReply' => array(
                'items' => array(
                                   array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => 'Cancel Search',
                'text' => 'exit'
                                 )
              ),array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $sitekey01,
                'text' => $sitekey01
                                 )
              ),array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $sitekey02,
                'text' => $sitekey02
                                 )
              ),array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $sitekey03,
                'text' => $sitekey03
                                 )
              ),array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $sitekey04,
                'text' => $sitekey04
                                 )
              )
              ,array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $sitekey05,
                'text' => $sitekey05
                                 )
              )
              ,array(
                'type' => 'action',
                'action' => array(
                'type' => 'message',
                'label' => $sitekey06,
                'text' => $sitekey06
                                 )
              )
                                )
                                     )
     )
     )
     );
  }
}

else {
}
}

if (isset($mreply)) {
    $result = json_encode($mreply);
    $client->replyMessage($mreply);
}  
?>
