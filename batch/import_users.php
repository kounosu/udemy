<?php

require_once("library/log.php");

$logFile = __DIR__ . "/log/import_users.php";
writeLog($logFile, "開始");
$dateCount = 0;

try {
    $pdo = new PDO("mysql:host=db;dbname=udemy_db;charset=utf8", "udemy_user", "udemy_pass");
    $fp = fopen(__DIR__ . "/import_users.csv", "r");

    $pdo->beginTransaction();

    while ($data = fgetcsv($fp)) {
        $sql = "SELECT COUNT(*) AS count FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":id" => $data[0]]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $param = [
            ":id" => $data[0],
            ":name" => $data[1],
            ":name_kana" => $data[2],
            ":birthday" => $data[3],
            ":gender" => $data[4],
            ":organization" => $data[5],
            ":post" => $data[6],
            ":start_date" => $data[7],
            ":tel" => $data[8],
            ":mail_address" => $data[9]
        ];

        if ($result["count"] === 0) {
            $sql = "INSERT INTO users (id, name, name_kana, birthday, gender, organization, post, start_date, tel, mail_address, created, updated) VALUES (:id, :name, :name_kana, :birthday, :gender, :organization, :post, :start_date, :tel, :mail_address, NOW(), NOW())";
        } else {
            $sql = "UPDATE users SET name = :name, name_kana = :name_kana, birthday = :birthday, gender = :gender, organization = :organization, post = :post, start_date = :start_date, tel = :tel, mail_address = :mail_address, updated = NOW() WHERE id = :id";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($param);
        $dateCount++;
    }

    $pdo->commit();
    fclose($fp);
} catch (Exception $e) {
    $pdo->rollBack();
    $dateCount = 0;
    writeLog($logFile, "エラー発生" . $e->getMessage());
}

writeLog($logFile, "終了[$dateCount]件");
?>
