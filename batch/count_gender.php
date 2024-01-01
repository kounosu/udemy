<?php
//CSVファイルのオープンとクローズ
//$fp = fopen("開きたいファイル","モード"); モード: r = 読込, w:書込, a:追伸書込
//while ($data = fgetcsv($fp)){
//    何かしらの処理    
//}
//fclose($fp)

//社員情報csvオープン
$fp = fopen(__DIR__ . "/input.csv", "r");
//ファイル1行ずつ読込、終端まで繰り返し
$lineCount = 0;
$manCount = 0;
$womanCount = 0;
while ($data = fgetcsv($fp)) {
    $lineCount++;
    if ($lineCount === 1) {
        //次の行に進む
        continue;
    }
    // var_dump($data);

    //男性？
    if ($data[4] === "男性") {
        //男性人数　＝　男性人数　＋　１
        $manCount++;
    } else {
        //女性人数　＝　女性人数　＋　１
        $womanCount++;
    }
}
//社員情報csvクローズ
fclose($fp);

//デバック
// echo "${manCount},${womanCount}";

//出力ファイルオープン
$fpOut = fopen(__DIR__."/output.csv","w");

//ヘッダー行書き込み
$header = ["男性","女性"];
fputcsv($fpOut,$header);

//男性人数、女性人数書き込み
$outputData = [$manCount,$womanCount];
fputcsv($fpOut,$outputData);

//出力ファイルクローズ
fclose($fpOut);
?>
