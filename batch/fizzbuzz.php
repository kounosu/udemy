<?php

// 入力値を受け取る
$value = $argv[1];

if ($value % 3 === 0 && $value % 5 ===  0) {
    // ３と５で割り切れる
    //fizzbuzzを出力
    echo "fizzbuzz\n";
} else if ($value % 3 === 0) {
    //３で割り切れる
    //fizzを出力
    echo "fizz\n";
} else if ($value === 5) {
    //５で割り切れる
    // buzzを出力
    echo "buzz\n
    ";
} else {
    // 入力値を出力
    echo "$value\n";
}
