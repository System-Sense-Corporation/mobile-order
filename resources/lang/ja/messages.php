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
        'mobile-order' => '受注登録',
        'orders' => '当日受注一覧',
        'products' => '商品マスタ',
        'customers' => '顧客マスタ',
        'admin-users' => 'ユーザー管理',
        'settings' => '設定',
        'profile' => 'プロフィール',
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
            'submit' => '保存',
        ],
        'flash' => [
            'success' => '受注を登録しました。',
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
        'empty' => 'まだ受注はありません。',
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
    'admin_users' => [
        'title' => 'ユーザー管理',
        'description' => 'スタッフアカウントと権限を管理します。',
        'actions' => [
            'create' => '新規作成',
        ],
        'filters' => [
            'permission' => '権限で絞り込む',
        ],
        'placeholders' => [
            'search' => '氏名・メール・部署で検索',
        ],
        'table' => [
            'no' => 'No.',
            'user_id' => 'ユーザーID',
            'name' => '氏名',
            'department' => '部署',
            'authority' => '権限',
            'email' => 'メールアドレス',
            'phone' => '携帯電話',
            'status' => 'ステータス',
            'last_login' => '最終ログイン',
        ],
        'authorities' => [
            'admin' => '管理者',
            'editor' => '編集者',
            'viewer' => '閲覧者',
        ],
        'statuses' => [
            'active' => '有効',
            'inactive' => '無効',
            'suspended' => '利用停止',
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
    'auth' => [
        'login_title' => 'ログイン',
        'login_heading' => 'Mobile Order にサインイン',
        'login_subheading' => '登録済みのメールアドレスとパスワードを入力してください。',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'remember_me' => 'ログイン状態を保持する',
        'login_button' => 'ログイン',
        'logout_button' => 'ログアウト',
        'failed' => '入力された認証情報が正しくありません。',
        'logged_in_as' => ':name としてログイン中',
    ],
    'profile' => [
        'title' => 'プロフィール',
        'description' => 'サインイン方法とアカウントの扱いを管理します。',
        'sections' => [
            'password' => [
                'title' => 'パスワードの更新',
                'description' => '強固なパスワードでアカウントを保護しましょう。',
                'fields' => [
                    'current' => '現在のパスワード',
                    'new' => '新しいパスワード',
                    'confirmation' => '新しいパスワード（確認）',
                ],
                'helper' => '英数字や記号を組み合わせて12文字以上で設定してください。',
                'button' => '新しいパスワードを保存',
            ],
            'account' => [
                'title' => 'アカウント管理',
                'description' => '利用しなくなった際のアカウントの扱いを設定できます。',
                'delete_warning' => 'アカウントを削除すると関連する情報がすべて失われます。この操作は元に戻せません。',
                'support' => '迷った場合は、実行する前にサポートへご相談ください。',
                'button' => 'アカウントを削除',
            ],
        ],
    ],
];
