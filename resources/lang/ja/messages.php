<?php

return [
    'app' => [
        'name' => 'Mobile Order',
        'language' => [
            'label' => '言語',
            'options' => [
                'ja' => '日本語',
                'th' => 'タイ語',
                'en' => '英語',
            ],
        ],
        'footer' => '© :year Mobile Order Mock',
    ],
    'navigation' => [
        'home' => 'トップメニュー',
        'menu_title' => 'ナビゲーションメニュー',
        'mobile-order' => '受注登録',
        'orders' => '当日受注一覧',
        'products' => '商品マスタ',
        'customers' => '顧客マスタ',
        'settings' => '設定',
    ],
    'index' => [
        'title' => 'トップメニュー',
        'cards' => [
            'mobile-order' => [
                'title' => '受注登録',
                'description' => '現場から素早く注文を登録します。',
            ],
            'orders' => [
                'title' => '当日受注一覧',
                'description' => '本日の受注状況を一覧で確認できます。',
            ],
            'products' => [
                'title' => '商品マスタ',
                'description' => '取り扱い商品の情報を管理します。',
            ],
            'customers' => [
                'title' => '顧客マスタ',
                'description' => '得意先の連絡先や属性を把握します。',
            ],
            'settings' => [
                'title' => '設定',
                'description' => '通知先やタイムゾーンなどを調整します。',
            ],
        ],
    ],
    'mobile_order' => [
        'title' => '受注登録',
        'fields' => [
            'order_date' => '受注日',
            'delivery_date' => '納品希望日',
            'customer' => '顧客',
            'product' => '商品',
            'quantity' => '数量',
            'notes' => '備考',
        ],
        'placeholders' => [
            'notes' => '特記事項があれば入力してください',
        ],
        'buttons' => [
            'reset' => 'クリア',
            'submit' => '仮登録',
        ],
        'options' => [
            'customers' => [
                '鮮魚酒場 波しぶき',
                'レストラン 潮彩',
                'ホテル ブルーサンズ',
            ],
            'products' => [
                '本マグロ 柵 500g',
                'サーモン フィレ 1kg',
                'ボタンエビ 20尾',
                '真鯛 1尾',
            ],
        ],
    ],
    'orders' => [
        'title' => '当日受注一覧',
        'table' => [
            'time' => '受付時刻',
            'customer' => '顧客',
            'items' => '注文内容',
            'status' => 'ステータス',
        ],
        'statuses' => [
            'pending' => '確認中',
            'preparing' => '出荷準備中',
            'shipped' => '出荷済',
        ],
        'samples' => [
            'tuna' => '本マグロ 柵 500g × 2',
            'salmon' => 'サーモン フィレ 1kg × 5',
            'shrimp' => 'ボタンエビ 20尾 × 3',
            'seabream' => '真鯛 1尾 × 4',
        ],
    ],
    'products' => [
        'title' => '商品マスタ',
        'table' => [
            'code' => '商品コード',
            'name' => '商品名',
            'unit' => '単位',
            'price' => '単価 (円)',
        ],
    ],
    'customers' => [
        'title' => '顧客マスタ',
        'contact_label' => '連絡先',
        'contact_person' => '担当',
        'notes_label' => 'メモ',
        'notes' => [
            'wave' => '毎朝8時納品',
            'shiosai' => '高級白身魚を希望',
            'blue_sands' => '大量注文あり',
            'koharu' => '土曜は臨時休業あり',
        ],
    ],
    'settings' => [
        'title' => '設定',
        'notifications' => [
            'title' => '通知設定',
            'order_mail' => '注文通知メール',
            'alert_mail' => '緊急連絡メール',
            'slack' => 'Slack Webhook URL',
        ],
        'system' => [
            'title' => 'システム設定',
            'timezone' => 'タイムゾーン',
            'open_time' => '営業開始時間',
            'close_time' => '営業終了時間',
        ],
        'buttons' => [
            'save' => '保存',
            'draft' => '下書き保存',
        ],
        'placeholders' => [
            'order_mail' => 'orders@example.com',
            'alert_mail' => 'alert@example.com',
            'slack' => 'https://hooks.slack.com/services/...',
        ],
    ],
];
