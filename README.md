## 概要 
アクセスログを解析するためのスクリプトです。  
詳しくは以下を参照してください。  

- 紹介記事：https://tech.linkbal.co.jp/2567/

## ログフォーマット
Apacheの場合、httpd.conf内の以下の項目を確認してください。  
※今回は「combined」のフォーマットを使用しています。

```
LogFormat "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"" combined
LogFormat "%h %l %u %t \"%r\" %>s %b" common
```
  
ログのフォーマットが異なる場合、スクリプト内の正規表現を変更してください。
  
## 使い方
### 1. access-log-analytics.phpと同じディレクトリにログ・結果格納用ディレクトリを作成
- /logs　　・・・ログ格納用ディレクトリ
- /results　・・・解析結果CSV格納用ディレクトリ
  
### 2. /logsディレクトリに解析したいアクセスログを格納
- アクセスログは全てgzip形式にすること
- 解析対象は/logsに格納されたgzipファイル全て

### 3. スクリプトを実行

```
$ php access-log-analytics.php
```
