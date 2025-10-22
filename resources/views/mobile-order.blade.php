<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>受注登録（Thai Sushi Thonglor）</title>
<link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header class="appbar"><h1>受注登録フォーム</h1></header>
<main class="container narrow">
  <form id="order-form" class="card">
    <label>店舗名<input required name="restaurant" value="Thai Sushi Thonglor"></label>
    <label>担当者<input required name="contact" value="Somchai"></label>
    <label>電話番号<input name="phone" value="0987654321"></label>
    <label>希望納期<input required type="date" name="delivery_date" value="2025-11-30"></label>
    <fieldset class="items">
      <legend>商品</legend>
      <div id="item-list"></div>
      <button type="button" id="add-item" class="btn small">＋ 行を追加</button>
    </fieldset>
    <label>備考<textarea name="notes" rows="3" placeholder="例：本マグロ フィレ 2kg希望"></textarea></label>
    <button class="btn primary" type="submit">📝 登録</button>
    <p id="result" class="success" style="display:none;">登録が完了しました。</p>
  </form>
  <a class="btn link" href="index.html">← メニューへ戻る</a>
</main>
<script>
document.getElementById('add-item').addEventListener('click', () => {
  const div = document.createElement('div');
  div.className = 'item-row';
  div.innerHTML = `
    <input placeholder="商品名">
    <input placeholder="数量">
    <input placeholder="単位">
    <button type="button" class="btn small del">削除</button>
  `;
  div.querySelector('.del').onclick = () => div.remove();
  document.getElementById('item-list').appendChild(div);
});
</script>
</body>
</html>
