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
            'update' => '更新する',
        ],
        'empty' => [
            'customers' => '登録済みの顧客がありません',
            'products' => '登録済みの商品がありません',
        ],
        'flash' => [
            'saved' => '受注を登録しました。',
            'updated' => '受注内容を更新しました。',
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
            'actions' => '操作',
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
        'flash' => [
            'status_updated' => 'ステータスを更新しました。',
            'updated' => '受注内容を更新しました。',
            'deleted' => '受注を削除しました。',
            'emailed' => '受注レポートをメールで送信しました。',
            'email_failed' => 'メールの送信に失敗しました。メール設定を確認してもう一度お試しください。',
        ],
        'actions' => [
            'edit' => '編集',
            'delete' => '削除',
            'download' => 'Excelをダウンロード',
            'send' => '送信',
            'email_label' => '送信先メールアドレス',
            'email_placeholder' => 'example@example.com',
            'confirm_delete' => 'この受注を削除しますか？元に戻せません。',
        ],
        'mail' => [
            'subject' => '当日受注一覧のエクスポート',
            'body' => '最新の受注レポートをExcelファイルで添付しました。',
            'greeting' => 'こんにちは。',
            'signoff' => 'モバイルオーダーをご利用いただきありがとうございます。',
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
        'description' => '取り扱い海産物の標準単位や単価を確認できます。',
        'actions' => [
            'create' => '商品を追加',
            'edit' => '編集',
            'delete' => '削除',
            'confirm_delete' => '商品「:name」（:code）を削除しますか？この操作は元に戻せません。',
        ],
        'empty' => [
            'title' => '登録されている商品がありません',
            'description' => '右上のボタンから商品を追加してください。',
        ],
        'flash' => [
            'saved' => '商品「:code」を保存しました。',
            'updated' => '商品「:code」を更新しました。',
            'deleted' => '商品「:code」を削除しました。',
        ],
        'table' => [
            'code' => '商品コード',
            'name' => '商品名',
            'unit' => '単位',
            'price' => '単価 (円)',
            'actions' => '操作',
        ],
        'form' => [
            'edit_title' => '商品を編集',
            'title' => '商品情報',
            'description' => '商品マスタを最新の状態に保つための登録・編集内容を入力します。',
            'validation_error' => '入力内容をご確認のうえ、再度お試しください。',
            'fields' => [
                'code' => '商品コード',
                'name' => '商品名',
                'unit' => '単位',
                'price' => '単価 (円)',
            ],
            'placeholders' => [
                'code' => '例: P-1001',
                'name' => '商品名を入力してください',
                'unit' => '単位を入力してください（パックなど）',
                'price' => '単価を入力してください',
            ],
            'buttons' => [
                'cancel' => '一覧に戻る',
                'save' => '商品を保存',
                'update' => '商品を更新',
            ],
            'sidebar' => [
                'title' => '入力内容のプレビュー',
                'description' => '保存前に入力した商品コード・単位・単価を確認できます。',
                'note' => '上部のフォーム入力に合わせてプレビューが更新されます。',
            ],
        ],
        'validation' => [
            'code' => [
                'required' => '商品コードを入力してください。',
                'unique' => 'この商品コードは既に使用されています。',
            ],
            'name' => [
                'required' => '商品名を入力してください。',
            ],
            'price' => [
                'required' => '単価を入力してください。',
                'integer' => '単価は整数で入力してください。',
                'min' => '単価は0以上で入力してください。',
            ],
        ],
    ],
'customers' => [
    'title' => '顧客マスター',
    'description' => '顧客の連絡先情報と社内メモを管理します。',
    'contact_label' => '連絡先',
    'contact_person' => '担当者',
    'internal_note_label' => '社内メモ',
    'notes_label' => 'メモ',
    'notes' => [
        'wave' => '毎朝8時に配達',
        'shiosai' => '高品質の白身魚を希望',
        'blue_sands' => '定期的に大量注文あり',
    ],
    'demo_notice' => 'このデータはサンプルです。',

    'table' => [
        'customer' => '顧客名',
        'contact_person' => '担当者',
        'contact' => '連絡先',
        'internal_note' => '社内メモ',
        'actions' => '操作',
    ],

    'actions' => [
        'create' => '顧客を追加',
        'create_short' => '追加する',
        'edit' => '編集',
        'delete' => '削除',
        'update' => '変更を保存',
        'confirm_delete' => '顧客「:name」を削除しますか？ この操作は元に戻せません。',
    ],

    'empty' => [
        'title' => '顧客が登録されていません',
        'description' => '右上のボタンから顧客を追加できます。',
    ],

    'flash' => [
        'saved' => '顧客「:name」を保存しました。',
        'updated' => '顧客「:name」を更新しました。',
        'deleted' => '顧客「:name」を削除しました。',
    ],

    'form' => [
        'title' => '顧客情報',
        'description' => '顧客の登録内容を確認・更新します。',
        'status' => [
            'editing' => '編集中',
            'creating' => '新規顧客',
        ],
        'fields' => [
            'name' => '顧客名',
            'contact' => '連絡先',
            'person' => '担当者',
            'note' => '社内メモ',
        ],
        'placeholders' => [
            'name' => '顧客名を入力してください',
            'contact' => '電話番号またはメールアドレスを入力してください',
            'person' => '担当者名を入力してください',
            'note' => '納品・希望事項などを入力してください',
        ],
        'buttons' => [
            'cancel' => 'キャンセル',
            'save' => '顧客を保存',
            'update' => '顧客を更新',
        ],
        'sidebar' => [
            'title' => '登録概要',
            'labels' => [
                'customer_code' => '顧客コード',
                'created_at' => '作成日',
                'last_updated' => '最終更新日',
            ],
            'note' => 'このフォームを使用して、顧客情報をマスターリストと一致させます。',
        ],
    ],

    'validation' => [
        'name' => [
            'required' => '顧客名を入力してください。',
            'string' => '顧客名は文字列である必要があります。',
            'max' => '顧客名は:max文字以内で入力してください。',
        ],
        'contact' => [
            'string' => '連絡先は文字列である必要があります。',
            'max' => '連絡先は:max文字以内で入力してください。',
        ],
        'contact_person' => [
            'string' => '担当者名は文字列である必要があります。',
            'max' => '担当者名は:max文字以内で入力してください。',
        ],
        'notes' => [
            'string' => 'メモは文字列である必要があります。',
            'max' => 'メモは:max文字以内で入力してください。',
        ],
    ],
],

    'admin_users' => [
        'title' => 'ユーザー管理',
        'description' => 'スタッフアカウントと権限を管理します。',
        'actions' => [
            'create' => '新規作成',
            'edit' => '編集',
            'delete' => '削除',
        ],
        'filters' => [
            'permission' => '権限で絞り込む',
            // VVVV ✨ 1. พี่โดนัทเพิ่ม "Key" ที่ขาด... ให้ตรงนี้ VVVV
            'all_permissions' => 'すべての権限', // (Key ที่ dropdown ใช้อยู่)
            'apply' => '絞り込む', // (อันนี้ปุ่มเก่า... แต่เติมเผื่อไว้)
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
            'actions' => '操作',
        ],
        'authorities' => [
            'admin' => '管理者',
            'editor' => '編集者',
            'viewer' => '閲覧者',
        ],
        'roles' => [
            'admin' => '管理者',
            'editor' => '編集者',
            'viewer' => '閲覧者',
        ],
        'role_descriptions' => [
            'admin' => 'システム設定やユーザー管理を含むすべての操作が可能です。',
            'editor' => '注文・商品・顧客の管理が可能です。',
            'viewer' => 'データの閲覧のみ可能です。',
        ],
        'statuses' => [
            'active' => '有効',
            'inactive' => '無効',
            'suspended' => '利用停止',
        ],
        'create' => [
            'title' => 'ユーザー登録',
            'description' => 'スタッフアカウントを作成し、適切な権限を割り当てます。',
        ],
        'edit' => [
            'title' => 'ユーザー編集',
            'description' => 'スタッフ情報や権限を更新します。',
        ],
        'form' => [
            'validation_error_heading' => '入力内容をご確認ください。',
            'name' => [
                'label' => '氏名',
                'placeholder' => '例）山田 太郎',
            ],
            'email' => [
                'label' => 'メールアドレス',
                'placeholder' => 'user@example.com',
            ],
            'phone' => [
                'label' => '携帯電話',
                'placeholder' => '例）090-1234-5678',
            ],
            'department' => [
                'label' => '部署',
                'placeholder' => '部署を選択',
                'options' => [
                    // VVVV ✨ 2. พี่โดนัท "เพิ่ม" (Add) ... 'กุญแจ' (Key) 'admin' ... ที่มัน "ขาด" (Missing) ... ให้ตรงนี้น้า! VVVVV
                    'admin' => '管理者', // (หรือ 'システム管理' ก็ได้ค่ะ)
                    // VVVVV ^^^^ VVVVV
                    'sales' => '営業部',
                    'support' => 'カスタマーサポート部',
                    'logistics' => '物流部',
                    'management' => '管理部',
                ],
            ],
            'authority' => [
                'label' => '権限',
            ],
            'notify_new_orders' => [
                'label' => '新規注文を通知する',
                'help' => '新しい注文が登録された際にメール通知します。',
            ],
            'require_password_change' => [
                'label' => '初回ログイン時にパスワード変更を必須にする',
                'help' => '初回ログイン後に新しいパスワードの設定を促します。',
            ],
            'password' => [
                'label' => '仮パスワード',
                'placeholder' => '仮パスワードを入力',
            ],
            'password_confirmation' => [
                'label' => 'パスワード（確認）',
                'placeholder' => 'もう一度入力してください',
            ],
            'cancel_button' => 'キャンセル',
            'submit_button' => 'ユーザーを登録',
            'submit_button_update' => '変更を保存',
        ],
        'flash' => [
            'created' => 'ユーザーを登録しました。',
            'updated' => 'ユーザー情報を更新しました。',
            'deleted' => 'ユーザーを削除しました。',
        ],
        'confirm_delete' => ':name を削除してもよろしいですか？',
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
        'alerts' => [
        'saved_success' => '設定が保存されました。',
    ],
        'buttons' => [
            'save' => '保存',
            'draft' => '保存',
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
            'account_information' => [
                'title' => 'アカウント情報',
                'description' => '表示名・部署・連絡先を更新します。',
                'fields' => [
                    'name' => '氏名',
                    'email' => 'メールアドレス',
                    'department' => '部署',
                    'department_placeholder' => '部署を選択してください',
                    'telephone' => '携帯電話番号',
                ],
                'button' => '変更を保存',
            ],
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
        'flash' => [
            'profile_updated' => 'プロフィール情報を更新しました。',
            'password_updated' => 'パスワードを更新しました。',
        ],
    ],
];