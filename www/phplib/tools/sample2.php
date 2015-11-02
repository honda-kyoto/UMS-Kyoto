<html>
<head><title>PHP TEST</title></head>
<body>

<?php

$fp = fopen('sample.txt', 'r');

$count = 0;

if ($fp){
    if (flock($fp, LOCK_SH)){
        while (!feof($fp)) {
            $buffer = fgets($fp);
            print($buffer.'<br>');
            $count++;
        }

        print('行は'.$count.'行でした<br>');

        flock($fp, LOCK_UN);
    }else{
        print('ファイルロックに失敗しました');
    }
}

$flag = fclose($fp);

if ($flag){
    print('無事クローズしました');
}else{
    print('クローズに失敗しました');
}

?>
</body>
</html>
