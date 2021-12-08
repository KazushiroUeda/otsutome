<?php
    // URLのパラメータにdate=yyyymmddを指定すれば、当日と翌日のおつとめ時間を返す。
    // URLパラメータが無効の場合は今日と明日のおつとめ時間を返す。
    if(isset($_GET['date'])&&preg_match('/^[0-9]{8}$/',$_GET['date'])==true){
        $day_raw = $_GET['date'];
        $day_format = substr($day_raw,0,4).'-'.substr($day_raw,4,2).'-'.substr($day_raw,6,2);
        $day_data = new DateTime($day_format);
    }else{
        $day_data = new DateTime('today');
    }

    $today = $day_data->format('md');
    $tommorow_data = $day_data->modify('+1 day');
    $tommorow = $tommorow_data->format('md');

    $json = @file_get_contents('data.json');
    $data = json_decode( $json , true );
    $result = array();

    foreach($data as $d):
        if($d['date']==$tommorow):
            $d_array = array(
                'date' => $d['date'],
                'morning' => $d['morning'],
                'evening' => $d['evening'],
                'type' => 'tommorow',
            );
            array_push($result,$d_array);
            unset($d_array);
        endif;
        if($d['date']==$today):
            $d_array = array(
                'date' => $d['date'],
                'morning' => $d['morning'],
                'evening' => $d['evening'],
                'type' => 'today',
            );
            array_push($result,$d_array);
            unset($d_array);
        endif;
    endforeach;
    $result_json = json_encode($result);

    $type = $_GET['type'];
    switch ($type) :
        case 'json':
            echo $result_json;
            break;
        default:
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="viewport-fit=auto, width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>教会本部のおつとめの時間</title>
</head>
<body>
    <p>URLに?date=<?php echo date('Ymd');?>を追加すると指定日のおつとめの時間を返します。</p>
    <p><?php echo '指定された日付は【'.$today.'】です。</p>';?></p>
    <p style="margin-bottom:2em;"><?php echo '指定日('.$result[0]['date'].')の朝づとめは'.$result[0]['morning'].'です。'?><br><?php echo '指定日('.$result[0]['date'].')の夕づとめは'.$result[0]['evening'].'です。'?></p>
    <p style="margin-bottom:2em;"><?php echo '指定日翌日('.$result[1]['date'].')の朝づとめは'.$result[1]['morning'].'です。'?><br><?php echo '指定日翌日('.$result[1]['date'].')の夕づとめは'.$result[1]['evening'].'です。'?></p>
    <?php
        echo '<h2>JSON</h2>';
        echo '<pre style="white-space: pre-wrap ;max-width:100%;">';
        echo $result_json;
        echo '</pre>';
    ?>
</body>
</html>
<?php
        break;
    endswitch;
?>
