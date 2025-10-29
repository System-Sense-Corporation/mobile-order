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
        'settings' => '設定',
        'admin_users' => 'ユーザー管理',
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
    'admin' => [
        'users' => [
            'title' => 'ユーザー管理',
            'description' => 'Mobile Order を利用する管理者・担当者のログインアカウントを管理します。',
            'create_button' => '新規作成',
            'create_title' => 'ユーザー登録',
            'create_description' => '新しい管理者または担当者アカウントを登録します。必須項目には「*」が表示されます。',
            'validation_error_heading' => '入力内容をご確認ください。',
            'table' => [
                'name' => '氏名',
                'email' => 'メール / ログインID',
                'phone' => '携帯電話',
                'department' => '所属部署',
                'authority' => '権限',
            ],
            'roles' => [
                'admin' => '管理者',
                'manager' => 'マネージャー',
                'staff' => 'スタッフ',
            ],
            'role_descriptions' => [
                'admin' => '全ての設定とマスタを操作できます。',
                'manager' => '所属部署の注文やユーザーを管理できます。',
                'staff' => '担当する注文の閲覧・編集ができます。',
            ],
            'form' => [
                'name' => [
                    'label' => '氏名',
                ],
                'email' => [
                    'label' => 'メール / ログインID',
                ],
                'phone' => [
                    'label' => '携帯電話',
                    'placeholder' => '例）080-1234-5678',
                ],
                'department' => [
                    'label' => '所属部署',
                    'placeholder' => '部署を選択してください',
                    'options' => [
                        'sales' => '営業部',
                        'support' => 'サポート部',
                        'logistics' => '物流部',
                        'management' => '管理部',
                    ],
                ],
                'authority' => [
                    'label' => '権限区分',
                ],
                'notify_new_orders' => [
                    'label' => '新規受注の通知メールを送る',
                    'help' => '新しい受注が登録された際にメール通知します。',
                ],
                'require_password_change' => [
                    'label' => '初回ログイン時にパスワード変更を必須にする',
                    'help' => '初回ログイン後に本人がパスワードを設定できるよう促します。',
                ],
                'password' => [
                    'label' => '仮パスワード',
                    'placeholder' => '8文字以上で入力してください',
                ],
                'password_confirmation' => [
                    'label' => '仮パスワード（確認）',
                    'placeholder' => 'もう一度入力してください',
                ],
                'cancel_button' => 'キャンセル',
                'submit_button' => 'ユーザーを登録',
            ],
            'flash' => [
                'created' => 'ユーザー情報を登録しました（モック）。',
            ],
        ],
    ],
];
