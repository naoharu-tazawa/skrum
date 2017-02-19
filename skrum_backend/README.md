skrum_symfony
=============

A Symfony project created on February 7, 2017, 3:01 am.


## skrum_symfony ディレクトリ構成
* [超入門_symfonyより抜粋](http://symnote.kwalk.jp/blog/2015-12-08/%E8%B6%85%E5%85%A5%E9%96%80_symfony3_%E3%83%87%E3%82%A3%E3%83%AC%E3%82%AF%E3%83%88%E3%83%AA%E6%A7%8B%E6%88%90)
* 運用して行くうちに使いやすいように変化して行くと思いますがとりあえず基本的には以下のような感じです

skrum_symfony
├── app                    # 設定や画面テンプレートを格納し、基本的にPHPのコードは格納しない
│   ├── Resources
│   │   └── views      (1) # 画面テンプレートを格納
│   └── config             # 設定ファイルを格納
│
├── bin                    # コマンドラインツールを格納
│
├── src                    # 自分が作成するソースコードを格納
│   ├── AppBundle      (a) # アプリケーションのソースコードを格納
│   │  ├── Controller      # コントローラを格納
│   │  ├── Entity          # エンティティを格納
│   │  ├── Repository      # レポジトリを格納
│   │  ├── Form            # フォーム
│   │  └── Resources
│   │       └── views  (2) # 画面テンプレートを格納 
│   │                      
│   │
│   └── その他のBundle  (b) # 汎用的なライブラリのソースコードを格納
│
│
├── web                    # ドキュメントルート（css, javascript, image等 ←今回いらなそう）
│
└── vendor             (c) # composerでインストールしたパッケージを格納

## PHP
* 7.0使用


## dockerによる開発環境の構築

* docker のインストール

```
$ wget -qO- https://get.docker.com/ | sh
...
If you would like to use Docker as a non-root user, you should now consider
adding your user to the "docker" group with something like:
sudo usermod -aG docker tiffany
Remember that you will have to log out and back in for this to take effect!
$ eval "$(docker-machine env default)"
cd skrum_symfony
composer install
docker login
docker-compose up -d
※次回以降は、docker-compose up -d のみでOK
```


