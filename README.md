# mission7_PHP
チーム開発した管理者機能付きのWeb掲示板です。  
基本的にはフレームワークやライブラリを使わずPHPで直に書いてますが、メール送信部分はPHPMailerを用いています。  
PCのみに対応しています。
<div align="center">
<img src="https://raw.github.com/wiki/s-tsuiki/mission7_PHP/images/logo.jpg" alt="ロゴ画像">
</div>

## 概要
管理者機能付きのWeb掲示板です。  
自分の好きなものを画像や動画付きで投稿し、みんなで共有できるSNSです。  
メール認証付きの会員登録機能、コメント投稿・削除・編集機能に加え、  
画像・動画の投稿・削除・編集機能、管理者機能が追加されています。  
XSS, CSRFなど、基本的なセキュリティ対策は実装済み。

## 内容
### トップページ
<div align="center">
<img src="https://raw.github.com/wiki/s-tsuiki/mission7_PHP/images/toppage.png" alt="トップページ">
</div>

### 会員登録
<div align="center">
<img src="https://raw.github.com/wiki/s-tsuiki/mission7_PHP/images/registration.png" alt="登録ページ">
</div>

### ログイン
<div align="center">
<img src="https://raw.github.com/wiki/s-tsuiki/mission7_PHP/images/login.png" alt="ログインページ">
<img src="https://raw.github.com/wiki/s-tsuiki/mission7_PHP/images/mypage.png" alt="マイページ">
</div>

<div align="center">
<img src="https://raw.github.com/wiki/s-tsuiki/mission7_PHP/images/logout.png" alt="ログアウトページ">
</div>

### 画像・動画の投稿・削除・編集
<div align="center">
<img src="https://raw.github.com/wiki/s-tsuiki/mission7_PHP/images/post.png" alt="投稿ページ">
</div>

<div align="center">
<img src="https://raw.github.com/wiki/s-tsuiki/mission7_PHP/images/delete.png" alt="削除ページ">
</div>

<div align="center">
<img src="https://raw.github.com/wiki/s-tsuiki/mission7_PHP/images/edit.png" alt="編集ページ">
</div>

### 管理者機能
<div align="center">
<img src="https://raw.github.com/wiki/s-tsuiki/mission7_PHP/images/admin.png" alt="管理者ページ">
</div>

<div align="center">
<img src="https://raw.github.com/wiki/s-tsuiki/mission7_PHP/images/admin_edit.png" alt="管理者編集ページ">
</div>

## 構成
ウェブページの本体を各PHPファイル内のHTMLで記述し、デザインを「layout」フォルダ内のCSSで指定しています。  
サイトのサーバー内の動きを各PHPファイル内のphpの記述部分で指定しています。  
仮メンバーのリスト、メンバーのリスト、コメントなどはすべてサーバー内のデータベース上で管理しています。  
管理者メンバーテーブルが別にあります。画像・動画を置くテーブルもあります。

## 役割
@hazukyon・・・ハスラー。市場調査、要件定義の⼀部を担う。アイデア提案。トップページとマイページ担当。  
@rinnnnnnnn・・・デザイン統括。使いやすい画⾯の設計を担う。ユーザーページ担当。  
@s-tsuiki・・・ハッカーのメイン。機能の担当をメンバーに割り振り、開発の指⽰出し。技術⾯での統括。  メール認証付きユーザー登録機能と管理者機能担当。  
@ryoya2452・・・ハッカーのサブ。ログインページ担当。  
@hina4646・・・ハッカーのサブ。投稿・編集ページ、ログアウトページ担当。

## 開発言語
フロントエンド・・・HTML, CSS, Javascript  
バックエンド・・・PHP  
データベース・・・MySQL

## 開発環境
エディタ・・・TeraPad  
ブラウザ・・・Chrome  
サーバー環境・・・Linux, Apache, PHP
