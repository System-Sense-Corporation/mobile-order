<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>当日受注一覧</title>
<link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header class="appbar"><h1>当日受注一覧</h1></header>
<main class="container">
  <div class="card">
    <label>対象日<input type="date" id="target-date"></label>
    <div class="actions">
      <button class="btn">更新</button>
      <button class="btn">CSV出力</button>
    </div>
  </div>
  <div class="card">
    <table class="table">
      <thead><tr><th>店舗名</th><th>担当者</th><th>商品</th><th>数量</th><th>納期</th></tr></thead>
      <tbody>
        <tr><td>Thai Sushi Thonglor</td><td>Somchai</td><td>サーモン切り身</td><td>10kg</td><td>2025-11-30</td></tr>
      </tbody>
    </table>
  </div>
  <a class="btn link" href="index.html">← メニューへ戻る</a>
</main>
</body>
</html>
