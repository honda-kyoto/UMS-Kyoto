<?php

$fp = fopen("sample.txt", "r");
while ($line = fgets($fp)) {
  echo "$line<br />";
}
fclose($fp);

?>