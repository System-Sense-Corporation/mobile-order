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
            'customer' => '顧客を選択してください',
            'product' => '商品を選択してください',
            'notes' => '特記事項があれば入力してください',
        ],
        'buttons' => [
            'reset' => 'クリア',
            'submit' => '登録する',
        ],
        'empty' => [
            'customers' => '登録済みの顧客がありません',
            'products' => '登録済みの商品がありません',
        ],
        'flash' => [
            'saved' => '受注を登録しました。',
        ],
        'demo_notice' => 'サンプルの取引先・商品データを表示しています。',
    ],
    'orders' => [
        'title' => '当日受注一覧',
        'table' => [
            'time' => '受付時刻',
            'customer' => '顧客',
            'items' => '注文内容',
            'status' => 'ステータス',
        ],
        'labels' => [
            'delivery' => '納品',
            'notes' => 'メモ',
        ],
        'statuses' => [
            'pending' => '確認中',
            'preparing' => '出荷準備中',
            'shipped' => '出荷済',
        ],
        'empty' => 'まだ受注はありません。',
        'samples' => [
            'tuna' => '本マグロ 柵 500g × 2',
            'salmon' => 'サーモンフィレ 1kg × 5',
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
        'description' => '取引先の連絡先情報と社内メモを管理します。',
        'contact_label' => '連絡先',
        'contact_person' => '担当',
        'notes_label' => 'メモ',
        'notes' => [
            'wave' => '毎朝8時納品',
            'shiosai' => '高級白身魚を希望',
            'blue_sands' => '大量注文あり',
        ],
        'demo_notice' => 'プレビュー用にサンプルの顧客データを表示しています。',
        'actions' => [
            'create' => '顧客を追加',
        ],
        'empty' => [
            'title' => '登録されている顧客がありません',
            'description' => '右上のボタンから顧客を登録してください。',
        ],
        'flash' => [
            'saved' => '顧客「:name」を保存しました。',
        ],
        'form' => [
            'title' => '顧客情報の編集',
            'description' => 'この顧客の登録内容を確認し、必要に応じて更新します。',
            'status' => [
                'editing' => '編集中',
                'creating' => '新規登録',
            ],
            'fields' => [
                'name' => '顧客名',
                'contact' => '連絡先',
                'person' => '担当者名',
                'note' => '社内メモ',
            ],
            'placeholders' => [
                'name' => '顧客名を入力してください',
                'contact' => '電話番号やメールアドレスを入力してください',
                'person' => '担当者名を入力してください',
                'note' => '配送時間や注意事項などを記入してください',
            ],
            'buttons' => [
                'cancel' => 'キャンセル',
                'save' => '顧客情報を保存',
            ],
            'sidebar' => [
                'title' => '登録サマリー',
                'labels' => [
                    'customer_code' => '顧客コード',
                    'created_at' => '登録日',
                    'last_updated' => '最終更新',
                ],
                'note' => '顧客一覧と同じ内容になるよう、このフォームで連絡先情報を整備してください。',
            ],
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
