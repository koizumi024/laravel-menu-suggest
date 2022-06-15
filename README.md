# メニュー提案アプリケーション

## 概要
自分が持っている食材や嫌いな食材を登録すると、それに合ったメニューを提案してくれるアプリケーションです。気に入ったメニューがあればレシピサイトで検索したり、不足している食材を買い物リストへ追加することが可能です。

## マッチ度について
提案結果は0~100%のマッチ度で返され、100%に近づくほど今ある食材で作りやすいことを示します。ただしレシピサイトをスクレイピングして作成したデータと比較してマッチ度を計算しているため、100%になっても今ある食材だけでは作れない可能性もあります。

## 本番環境
[https://menu.mizurest.link/](https://menu.mizurest.link/)

ユーザー登録が必要ですが、メール認証を実装していないため架空のメールアドレスで登録可能です。
`test@example.com`など

## 画面
<img width="220" alt="ss1" src="https://i.imgur.com/ald8I4f.png"> <img width="220" alt="ss2" src="https://i.imgur.com/bFsOruZ.png"> <img width="220" alt="ss3" src="https://i.imgur.com/hGPAd9B.png">

## 技術
![PHP](https://img.shields.io/badge/PHP-f2f2f2.svg?logo=php&style=for-the-badge)
![Laravel](https://img.shields.io/badge/Laravel-fff.svg?logo=laravel&style=for-the-badge)
![Bootstrap](https://img.shields.io/badge/Bootstrap-f7f5fb.svg?logo=bootstrap&style=for-the-badge)
![MySQL](https://img.shields.io/badge/MySQL-f29111.svg?logo=mysql&style=for-the-badge)
![Amazon AWS](https://img.shields.io/badge/Amazon&nbsp;AWS-232f3e.svg?logo=amazon-aws&style=for-the-badge)<br>

Laravel UI 3.3<br>
Goutte 2.1<br>

## ER図
[https://dbdiagram.io/d/6140e22b825b5b0146021232](https://dbdiagram.io/d/6140e22b825b5b0146021232)