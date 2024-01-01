<?php

require_once("library/log.php");

$logFile = __DIR__. "/log/import_users.php";
writeLog($logFile, "開始");
$dateCount = 0;

try{
    // データベース接続
    $username = "udemy_user";
    $password = "udemy_pass";
    $hostname = "db";
    $db = "udemy_db";
    $pdo = new PDO("mysql:host={$hostname};dbname={$db};charset=utf8", $username, $password);

    //社員情報csvオープン
    $fp = fopen(__DIR__ . "/import_users.csv", "r");

    //トランザクション開始
    $pdo->beginTransaction();

    //ファイルを1行づつ読込、終端まで繰り返し
    while ($data = fgetcsv($fp)) {
        //var_dump($data);
        //社員番号をキーに社員情報取得SQLを実行
        $sql = "SELECT COUNT(*) AS count FROM users WHERE id = :id";
        $param = [":id" => $data[0]];
        $stmt = $pdo->prepare($sql);
        $stmt->execute($param);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // デバック
        // var_dump($data[0]);
        // var_dump($result);

        //SQLの結果件数は0件？
        if ($result["count"] === 0) {
            //社員情報更新SQLの実行
            // var_dump($data[0]);
            // var_dump("登録");
            $sql = "INSERT INTO users (";
            $sql .= " id, ";
            $sql .= " name, ";
            $sql .= " name_kana, ";
            $sql .= " birthday, ";
            $sql .= " gender, ";
            $sql .= " organization, ";
            $sql .= " post, ";
            $sql .= " start_date, ";
            $sql .= " tel, ";
            $sql .= " mail_address, ";
            $sql .= " created, ";
            $sql .= " updated ";
            $sql .= ") VALUES( ";
            $sql .= " :id, ";
            $sql .= " :name, ";
            $sql .= " :name_kana, ";
            $sql .= " :birthday, ";
            $sql .= " :gender, ";
            $sql .= " :organization, ";
            $sql .= " :post, ";
            $sql .= " :start_date, ";
            $sql .= " :tel, ";
            $sql .= " :mail_address, ";
            $sql .= " NOW(), "; //作成日時
            $sql .= " NOW()"; //更新日時
            $sql .= " ) "; 
        } else {
            //社員情報登録SQLの実行
            // var_dump($data[0]);
            // var_dump("更新");
            $sql = "UPDATE users";
            $sql .= "SET name = :name,";
            $sql .= "name_kana = :name_kana,";
            $sql .= "birthday = :birthday,";
            $sql .= "gender =:gender,";
            $sql .= "organization = :organization,";
            $sql .= "post = :post,";
            $sql .= "start_date = :start_date,";
            $sql .= "tel = :tel,";
            $sql .= "mail_address = :mail_address,";
            $sql .= "updated = NOW()"; //更新日時
            $sql .= "WHERE id = :id";
            $sql = "UPDATE users SET 
            name = :name,
            name_kana = :name_kana,
            birthday = :birthday,
            gender = :gender,
            organization = :organization,
            post = :post,
            start_date = :start_date,
            tel = :tel,
            mail_address = :mail_address,
            updated = NOW()
            WHERE id = :id";
        }
        $param = array(
            "id" => $data[0],
            "name" => $data[1],
            "name_kana" => $data[2],
            "birthday" => $data[3],
            "gender" => $data[4],
            "organization" => $data[5],
            "post" => $data[6],
            "start_date" => $data[7],
            "tel" => $data[8],
            "mail_address" => $data[9],
        );
        $stmt = $pdo->prepare($sql);
        $stmt->execute($param);
        $dateCount++;
    }

    //コミット
    $pdo->commit();

    //出力ファイルクローズ
    fclose($fp);
}catch(Exception $e){
    //ロールバック
    $pdo->rollBack();
    $dateCount = 0;
    writeLog($logFile, "エラー発生".$e->getMessage());
}

writeLog($logFile, "終了[$dateCount]件");
?>