<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title data-i18n="title">Fish Order Mock</title>
<link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header class="appbar">
  <h1 data-i18n="main_title">魚卸 受注モック</h1>
  <select id="lang-select" class="lang-select">
    <option value="ja">🇯🇵</option>
    <option value="en">🇬🇧</option>
    <option value="th">🇹🇭</option>
  </select>
</header>
<main class="container">
  <div class="card grid">
    <a class="btn primary" href="/fish-order-mock/mobile-order.html" data-i18n="order_register">📱 受注登録</a>
    <a class="btn" href="/fish-order-mock/orders.html" data-i18n="order_list">📄 当日受注一覧</a>
    <a class="btn" href="/fish-order-mock/products.html" data-i18n="product_master">🐟 商品マスタ</a>
    <a class="btn" href="/fish-order-mock/customers.html" data-i18n="customer_master">🍽 顧客マスタ</a>
    <a class="btn" href="/fish-order-mock/settings.html" data-i18n="settings">⚙️ 設定</a>
  </div>
</main>
<footer class="footer">© <span data-i18n="footer_text">Fish Order Mock</span></footer>
<script src="js/lang.js"></script>
<script>setupLangSelector();</script>
</body>
</html>
