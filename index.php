<?php 

include_once('./db-connect.php'); 

$dbconn = connect_db(); 

$html = file_get_contents('https://en.wikipedia.org/wiki/December_10');

$start = stripos($html, 'id="Births"');

$end = stripos($html, '</ul>', $offset = $start);

$length = $end - $start;

$htmlSection = substr($html, $start, $length);

preg_match_all('@<li>(.+)</li>@', $htmlSection, $matches);
$listItems = $matches[1];

echo "Who was born on December 10th\n";

echo "=============================\n\n";

foreach ($listItems as $item) {
    preg_match('@(\d+)@', $item, $yearMatch);
    $year = (int) $yearMatch[0];
    preg_match('@;\s<a\b[^>]*>(.*?)</a>@i', $item, $nameMatch);
    $name = $nameMatch[1];
    echo "{$name} was born in {$year} <br>";

    $sql = "INSERT INTO scrapping (name, dbyear) VALUES ($name, $year)"; 
    $dbconn->exec($sql);
}