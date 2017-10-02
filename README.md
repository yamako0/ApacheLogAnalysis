# 株式会社フィックスポイント プログラミング試験問題

## 概要
Apacheのアクセスログをリモートホスト別・時間帯別に集計及び表示、  
また、指定した期間内でのリモートホスト別・時間帯別に集計及び表示を行う  

## 使用方法
index.htmlにアクセスすることで勝手に集計が始まります。  
また、ページ下部のフォームで期間を入力し"絞り込み"ボタンを押すことで  
指定した期間内での集計を行います。

## 使用言語等
- HTML  
- CSS  
- PHP  
- Bootstrap  

## 動作確認
windows 10  
Firefox 56.0  
PHP 5.6.8  
Apache 2.4.12  

## 構成
root\  
 ├ logs\  
 ├ index.html  
 ├ function.php  
 ├ refine.php  
 └ stylesheet.css  

## 各問について

### 問1
index.htmlにアクセスすることで勝手に集計が始まります。  
""各時間帯毎"という表現が曖昧でしたが、とりあえず"0-6/6-12/12-18/18-24"で分けました。  

### 問2
"root\logs"に入っている.log形式のファイルを全部読み込み集計を行うようになっています。  

### 問3
サイト下部のフォームに入力した期間での集計を行います。

### 問4
問3まで終了した段階で、すでに大規模データには対応していました。  
fgets()は一行ずつ読み込むため、Apacheのログの形式で  
メモリが足らなくなるほど読み込むことはほぼ無いと思われます。  
ただ、環境・データの容量によって処理に時間がかかるため  
```
Fatal error: Maximum execution time
```
が出る場合があると思います。  
その場合はphp.iniの  
```
max_execution_time=30
```
の"30"を多めに設定しXAMPP等を再起動してからindex.htmlにアクセスしてください。  
ちなみに私の環境  
- windows 10 x64
- CPU: intel(R) Core(TM) i7-4770 @ 3.4GHz
- メモリ: 8GB
で、12GBほどのダミーのログを用意して動作確認しました。  
処理を終えるまで15~20分ほどかかりましたが、メモリ不足・クラッシュ等は起こりませんでした。  
