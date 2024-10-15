# wpe-secure-updater
「WP Engine Secure Updater」プラグイン

## 一次配布元
- https://wpeng.in/secure-updater/

## API
- https://wpe-plugin-updates.wpengine.com/plugins.json

| No | plugin dir name | plugin name | Author | etc |
|----|-----------------|-------------|--------|-----|
| 1 | wpe-secure-updater | WPE Secure Updater | WP Engine | - |
| 2 | wpe-api-tester | wpe-api-tester | WP Engine | - |
| 3 | advanced-custom-fields | Advanced Custom Fields | WP Engine | - |
| 4 | amazon-s3-and-cloudfront | WP Offload Media Lite | Delicious Brains | - |
| 5 | atlas-content-modeler | Atlas Content Modeler | WP Engine | - |
| 6 | atlas-search | WP Engine Smart Search | WP Engine | - |
| 7 | better-search-replace | Better Search Replace | WP Engine | - |
| 8 | faustwp | Faust.js™ | WP Engine | - |
| 9 | genesis-beta-tester | Genesis Beta Tester | Nathan Rice | - |
| 10 | genesis-blocks | Genesis Blocks | StudioPress | - |
| 11 | genesis-custom-blocks | Genesis Custom Blocks | Genesis Custom Blocks | - |
| 12 | genesis-simple-hooks | Genesis Simple Hooks | StudioPress | - |
| 13 | genesis-simple-menus | Genesis Simple Menus | StudioPress | - |
| 14 | genesis-connect-woocommerce | Genesis Connect for WooCommerce | StudioPress | - |
| 15 | nitropack | NitroPack | NitroPack Inc. | - |
| 16 | pattern-manager | Pattern Manager | WP Engine | - |
| 17 | php-compatibility-checker | PHP Compatibility Checker | WP Engine | - |
| 18 | seo-data-transporter | SEO Data Transporter | StudioPress | - |
| 19 | wp-graphql | WPGraphQL | WPGraphQL | - |
| 20 | wp-migrate-db | WP Migrate Lite | WP Engine | - |
| 21 | wp-ses | WP Offload SES Lite | Delicious Brains | - |
| 22 | wpengine-geoip | WP Engine GeoTarget | WP Engine | - |
| 23 | wpgraphql-acf | WPGraphQL for ACF | WPGraphQL | - |
| 24 | wpgraphql-smart-cache | WPGraphQL Smart Cache | WPGraphQL | - |

## 動作概略
- `PluginUpdater` クラスは、WordPress プラグインの更新を新しい場所から取得するためのクラスです。このクラスの動作ロジックは以下の通りです。

1. **初期化**:
   - コンストラクタ `__construct` で、プラグインの名前、著者、スラッグを含むプロパティを受け取ります。
   - これらのプロパティが正しく提供されていない場合、エラーログを記録して終了します。
   - API URL とキャッシュ時間を設定し、プラグインの完全なプロパティを取得します。
   - プロパティが正しく設定されている場合、`register` メソッドを呼び出してフックを登録します。
2. **プロパティの取得**:
   - `get_full_plugin_properties` メソッドで、インストールされているプラグインをスキャンし、指定された名前と著者に一致するプラグインを探します。
   - 一致するプラグインが見つかった場合、そのプラグインのディレクトリ名、バージョン、ベースネーム、更新用のトランジェント名などをプロパティに追加します。
3. **フックの登録**:
   - `register` メソッドで、`plugins_api` フィルターと `pre_set_site_transient_update_plugins` フィルターを登録します。
4. **更新トランジェントのフィルタリング**:
   - `filter_plugin_update_transient` メソッドで、プラグインの更新通知を引き継ぎます。
   - プラグインの情報を取得し、現在のバージョンと比較して新しいバージョンがある場合、更新情報をトランジェントに追加します。
5. **プラグイン情報のフィルタリング**:
   - `filter_plugin_update_info` メソッドで、プラグイン情報を取得するアクションに対してフィルタリングを行います。
   - 指定されたプラグインに対する情報を取得し、正しいレスポンスが得られた場合、情報を解析して返します。
6. **プラグイン情報の取得と解析**:
   - `fetch_plugin_info` メソッドで、WP Product Info API からプラグインの更新オブジェクトを取得します。
   - キャッシュされたレスポンスが期限切れの場合、新しい情報を取得し、オプションとして保存します。
   - `parse_plugin_info` メソッドで、取得したレスポンスを WordPress が理解できる形式に解析します。

このクラスは、WordPress プラグインの更新を管理し、指定された API から最新のプラグイン情報を取得して、WordPress の更新システムに統合する役割を果たします。

