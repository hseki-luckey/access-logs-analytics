<?php
echo "アクセスログの解析を開始します\n";

$dates = array();
$device_type = array(
	'mobile' => 0,
	'tablet' => 0,
	'pc' => 0
);

foreach(glob('logs/*.gz') as $file){
	if(is_file($file)){
		echo "「{$file}」の読み込みを開始します...\n";

		$lines = gzfile($file);
		foreach($lines as $line){
			// アクセスログの形式を指定
			$pattern = '/^(\S+)\s(\S+)\s(\S+)\s\[([\S|\s]+)\]\s"([\S|\s]+)"\s(\d+)\s(\d+)\s"(\S+)"\s"([\S|\s]+)"/';
			$result = preg_match($pattern, $line, $elements);

			if($result){
				list($log, $ip, $r_log, $r_user, $date, $header, $status, $byte, $referer, $useragent) = $elements;
				// 日時集計
				$target_date = date('Y年m月d日 H時', strtotime($date));
				if(array_key_exists($target_date, $dates)){
					$dates[$target_date] += 1;
				}else{
					$dates[$target_date] = 1;
				}
				// UA集計
				$useragent = mb_strtolower($useragent);
				if(preg_match('#\b(ip(hone|od);|android.*mobile)|windows.*phone|blackberry.*|psp|3ds|vita#', $useragent)){
					$device_type['mobile'] += 1;
				}elseif(preg_match('#\ipad|android|kindle|silk|playbook|rim\stablet#', $useragent)){
					$device_type['tablet'] += 1;
				}else{
					$device_type['pc'] += 1;
				}
			}
		}

		echo "「{$file}」の読み込みが完了しました\n";
	}
}

// CSVに結果を出力
$export_csv = array(
	'results/date-results.csv' => $dates,
	'results/device-results.csv' => $device_type
);

echo "CSVへの書き出しを開始します...\n";

foreach($export_csv as $csv => $data){
	$file = fopen($csv, 'w');
	foreach($data as $column => $result){
		fputcsv($file, array($column, $result));
	}
	fclose($file);
}

echo "CSVへの書き出しが完了しました\n";

echo "アクセスログの解析が完了しました\n";
?>