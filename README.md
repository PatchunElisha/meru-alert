# メルカリアラート
検索条件を設定することでcronで設定した時間ごとに検索結果を取得して、新規出品があった場合メールを送信します。

現状はMacのSail環境を動かしたままのみ使用可能。

## テスト環境
- MacBookAir2023 Apple M2
- Sail

## 環境構築
```bash
composer install
php artisan migrate --seed
npm install
npm run build
```

```bash
crontab -e
```

```bash
*/1 * * * * cd /var/www/meru-alert; sail artisan auth:clear-resets
*/3 * * * * cd /var/www/meru-alert; sail artisan batch:selenium
```
※ cronは動作未検証

.envファイルにDB、MailのFROM・TOそれぞれのADDRESSとNAME、SELENIUM_HOSTの設定を行ってください。

## 今後の追加予定機能
- Userの新規登録
- 検索条件の追加
