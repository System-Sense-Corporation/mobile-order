<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>タイ日系・魚卸向け 受注〜出荷 業務フロー（モック）</title>
<style>
  :root{
    --bg:#fff; --text:#111; --muted:#666; --red:#b30000; --line:#e5e7eb; --card:#fff;
  }
  *{box-sizing:border-box}
  body{margin:0; font-family: "Noto Sans JP", system-ui, -apple-system, Segoe UI, Roboto, sans-serif; background:var(--bg); color:var(--text)}
  header{padding:16px 20px; border-bottom: 4px solid var(--red); background:#fff; position:sticky; top:0}
  h1{margin:0; font-size:22px; letter-spacing:.02em}
  .container{max-width:1100px; margin:24px auto; padding:0 16px}
  .grid{display:grid; grid-template-columns: 1fr; gap:16px}
  .swimlane{background:var(--card); border:1px solid var(--line); border-left:6px solid var(--red); border-radius:12px; padding:14px}
  .lane-title{font-weight:700; color:var(--red); margin-bottom:8px}
  .steps{display:grid; grid-template-columns: repeat(12, 1fr); gap:10px; align-items:stretch}
  .step{grid-column: span 4; border:1px solid var(--line); border-radius:10px; padding:12px; background:#fff; position:relative}
  .step h3{margin:0 0 6px; font-size:16px}
  .step p{margin:0; color:var(--muted); font-size:14px; line-height:1.55}
  .tag{display:inline-block; font-size:12px; border:1px solid var(--red); color:var(--red); padding:2px 6px; border-radius:999px; margin-right:6px}
  .arrow{position:relative; height:0; border-top:2px dashed var(--red); margin:6px 0 16px}
  .arrow:after{content:""; position:absolute; right:-6px; top:-5px; border:6px solid transparent; border-left-color:var(--red)}
  /* responsive */
  @media (max-width:960px){ .step{grid-column: span 6} }
  @media (max-width:640px){ .step{grid-column: span 12} }
  /* legend & footer */
  .legend, .notes{border:1px solid var(--line); border-radius:12px; padding:12px; background:#fff}
  .legend b{color:var(--red)}
  footer{color:#999; text-align:center; padding:28px 12px}
  /* print */
  @media print{
    header{position:static}
    a[href]:after{content:""}
  }
</style>
</head>
<body>
<header>
  <h1>タイ日系・魚卸向け SaaS（モック）｜受注→登録→エクスポート→本社連絡→出荷／連絡フロー</h1>
</header>

<main class="container">
  <!-- レジェンド -->
  <section class="legend" style="margin-bottom:16px">
    <div class="tag">目的</div>リテラシーが高くない現場でも<strong>スマホ（LINE WebView）</strong>で簡単登録し、<strong>12時までにExcelエクスポート→本社へメール送付</strong>。本社は在庫有無で分岐し、在庫無し時は営業が顧客へ連絡。
  </section>

  <!-- 泳ぐ線（担当毎） -->
  <section class="grid">
    <!-- 1: 営業（タイ側） -->
    <div class="swimlane">
      <div class="lane-title">① 営業（タイ側）</div>
      <div class="steps">
        <div class="step">
          <span class="tag">現状</span><span class="tag">LINE</span>
          <h3>飲食店からの発注依頼をLINEで受領</h3>
          <p>メニュー／数量／希望納期などをヒアリング。</p>
        </div>
        <div class="step">
          <span class="tag">新フロー</span><span class="tag">スマホ</span>
          <h3>LINEからWebViewで受注フォームを開く</h3>
          <p>最小入力（店舗・担当・電話・納期・商品行）。<br>UIは大きなボタンと候補入力で誤入力を削減。</p>
        </div>
        <div class="step">
          <span class="tag">保存</span><span class="tag">JSON/DB</span>
          <h3>受注データをシステムに登録</h3>
          <p>登録後は当日リストに反映。12:00まで追加登録OK。</p>
        </div>
      </div>
      <div class="arrow"></div>
      <div class="steps">
        <div class="step" style="grid-column: span 12;">
          <span class="tag">12:00 締め</span><span class="tag">エクスポート</span>
          <h3>当日分をExcel（CSV）にエクスポート</h3>
          <p>形式：登録時刻 / 店舗 / 担当 / 納品日 / 商品コード・名 / 数量 / 単位 / カット / 備考。</p>
        </div>
      </div>
    </div>

    <!-- 2: システム（SaaS） -->
    <div class="swimlane">
      <div class="lane-title">② システム（SaaS）</div>
      <div class="steps">
        <div class="step">
          <span class="tag">UI</span><span class="tag">Web</span>
          <h3>受注登録フォーム（スマホ最適）</h3>
          <p>候補選択（商品マスタ・サイズ・単位）/ 顧客マスタ検索 / 多言語UI（JP/EN/TH）。</p>
        </div>
        <div class="step">
          <span class="tag">集計</span>
          <h3>当日受注一覧画面</h3>
          <p>日付フィルタ＝本日 / 12:00時点のスナップショットをCSVに。</p>
        </div>
        <div class="step">
          <span class="tag">メール</span>
          <h3>本社宛てにCSVを添付送信</h3>
          <p>件名：「【当日受注】YYYY-MM-DD タイ→日本本社」。<br>一旦はSMTP/レンタルサーバのmail()で送信。</p>
        </div>
      </div>
    </div>

    <!-- 3: 日本本社（在庫確認→分岐） -->
    <div class="swimlane">
      <div class="lane-title">③ 日本本社（在庫確認）</div>
      <div class="steps">
        <div class="step">
          <span class="tag">在庫有</span><span class="tag">出荷手配</span>
          <h3>在庫あり → 営業日◯日で出荷</h3>
          <p>梱包・通関・輸送手配。出荷予定日をタイ側へ共有。</p>
        </div>
        <div class="step">
          <span class="tag">在庫無</span><span class="tag">要連絡</span>
          <h3>在庫なし → 営業から顧客へ調整連絡</h3>
          <p>代替品 / 納期変更 / 数量変更などを電話・LINEで相談。</p>
        </div>
        <div class="step">
          <span class="tag">対象外</span>
          <h3>請求書（Tax Invoice）は別システム</h3>
          <p>本モックの対象外。将来は連携を検討（API/ファイル連携）。</p>
        </div>
      </div>
    </div>
  </section>

  <!-- 付録：データと拡張ポイント -->
  <section class="notes" style="margin-top:16px">
    <h3 style="margin:0 0 8px; color:var(--red)">データ構成（最小）</h3>
    <ul style="margin:0 0 8px 18px; line-height:1.7">
      <li><b>受注</b>：id, 受付時刻, 店舗, 担当, 電話, 納品日, 明細[{商品コード, 商品名, サイズ, 数量, 単位, カット}], 備考</li>
      <li><b>商品マスタ</b>：コード, 名称（日/英/泰）, 単位, サイズ候補, カット候補</li>
      <li><b>顧客マスタ</b>：店舗名, 担当者, 電話, メール, 住所（任意）</li>
    </ul>
    <h3 style="margin:12px 0 8px; color:var(--red)">拡張ポイント</h3>
    <ul style="margin:0 0 0 18px; line-height:1.7">
      <li>LINE Messaging APIログイン／注文ハッシュ化、自動フォーム起動</li>
      <li>メール→API化（SES/SendGrid）／S3へのCSV保管／承認ワークフロー</li>
      <li>在庫照会（日本側WMS/ERP連携）と在庫連動の自動分岐</li>
      <li>多言語UI（JP/EN/TH）と店舗別の初期値プリセット</li>
    </ul>
  </section>
</main>

<footer>© Fish Order Mock — 白背景 × 赤黒テーマ（提案用）</footer>
</body>
</html>
