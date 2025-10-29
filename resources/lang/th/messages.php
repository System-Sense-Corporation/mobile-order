<?php

return [
    'app' => [
        'name' => 'Mobile Order',
        'language' => [
            'label' => 'ภาษา',
            'options' => [
                'ja' => 'ภาษาญี่ปุ่น',
                'th' => 'ภาษาไทย',
                'en' => 'ภาษาอังกฤษ',
            ],
        ],
        'footer' => '© :year Mobile Order Mock',
    ],
    'navigation' => [
        'home' => 'เมนูหลัก',
        'mobile-order' => 'ลงทะเบียนคำสั่งซื้อ',
        'orders' => 'รายการสั่งซื้อวันนี้',
        'products' => 'ข้อมูลสินค้า',
        'customers' => 'ข้อมูลลูกค้า',
        'settings' => 'ตั้งค่า',
    ],
    'index' => [
        'title' => 'เมนูหลัก',
        'cards' => [
            'mobile-order' => [
                'title' => 'ลงทะเบียนคำสั่งซื้อ',
                'description' => 'บันทึกคำสั่งซื้อจากหน้างานได้อย่างรวดเร็ว',
            ],
            'orders' => [
                'title' => 'รายการสั่งซื้อวันนี้',
                'description' => 'ติดตามสถานะคำสั่งซื้อของวันนี้ได้ทันที',
            ],
            'products' => [
                'title' => 'ข้อมูลสินค้า',
                'description' => 'จัดการข้อมูลสินค้าที่มีทั้งหมด',
            ],
            'customers' => [
                'title' => 'ข้อมูลลูกค้า',
                'description' => 'ดูข้อมูลการติดต่อและรายละเอียดของลูกค้า',
            ],
            'settings' => [
                'title' => 'ตั้งค่า',
                'description' => 'ปรับแต่งผู้รับการแจ้งเตือนและเขตเวลา',
            ],
        ],
    ],
    'mobile_order' => [
        'title' => 'ลงทะเบียนคำสั่งซื้อ',
        'fields' => [
            'order_date' => 'วันที่สั่งซื้อ',
            'delivery_date' => 'วันที่ต้องการรับสินค้า',
            'customer' => 'ลูกค้า',
            'product' => 'สินค้า',
            'quantity' => 'จำนวน',
            'notes' => 'หมายเหตุ',
        ],
        'placeholders' => [
            'notes' => 'กรุณากรอกข้อมูลเพิ่มเติมหากมี',
        ],
        'buttons' => [
            'reset' => 'ล้างข้อมูล',
            'submit' => 'บันทึกชั่วคราว',
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
        'title' => 'รายการสั่งซื้อวันนี้',
        'table' => [
            'time' => 'เวลาที่รับรายการ',
            'customer' => 'ลูกค้า',
            'items' => 'รายละเอียดสินค้า',
            'status' => 'สถานะ',
        ],
        'statuses' => [
            'pending' => 'รอตรวจสอบ',
            'preparing' => 'กำลังเตรียมจัดส่ง',
            'shipped' => 'จัดส่งแล้ว',
        ],
        'samples' => [
            'tuna' => 'ทูน่าบล็อก 500 กรัม × 2',
            'salmon' => 'แซลมอนฟิเลต์ 1 กก. × 5',
            'shrimp' => 'กุ้งโบตัน 20 ตัว × 3',
            'seabream' => 'ปลากะพงแดงทั้งตัว × 4',
        ],
    ],
    'products' => [
        'title' => 'ข้อมูลสินค้า',
        'table' => [
            'code' => 'รหัสสินค้า',
            'name' => 'ชื่อสินค้า',
            'unit' => 'หน่วย',
            'price' => 'ราคาต่อหน่วย (เยน)',
        ],
    ],
    'customers' => [
        'title' => 'ข้อมูลลูกค้า',
        'contact_label' => 'ข้อมูลติดต่อ',
        'contact_person' => 'ผู้ติดต่อ',
        'notes_label' => 'หมายเหตุ',
        'notes' => [
            'wave' => 'จัดส่งทุกเช้าเวลา 8:00 น.',
            'shiosai' => 'ต้องการปลาขาวระดับพรีเมียม',
            'blue_sands' => 'มักสั่งซื้อจำนวนมากเป็นประจำ',
            'koharu' => 'มักหยุดทำการในวันเสาร์เป็นครั้งคราว',
        ],
    ],
    'settings' => [
        'title' => 'ตั้งค่า',
        'notifications' => [
            'title' => 'การตั้งค่าการแจ้งเตือน',
            'order_mail' => 'อีเมลแจ้งเตือนคำสั่งซื้อ',
            'alert_mail' => 'อีเมลติดต่อฉุกเฉิน',
            'slack' => 'Slack Webhook URL',
        ],
        'system' => [
            'title' => 'การตั้งค่าระบบ',
            'timezone' => 'เขตเวลา',
            'open_time' => 'เวลาเปิดทำการ',
            'close_time' => 'เวลาปิดทำการ',
        ],
        'buttons' => [
            'save' => 'บันทึก',
            'draft' => 'บันทึกร่าง',
        ],
        'placeholders' => [
            'order_mail' => 'orders@example.com',
            'alert_mail' => 'alert@example.com',
            'slack' => 'https://hooks.slack.com/services/...',
        ],
    ],
    'admin' => [
        'users' => [
            'title' => 'ผู้ใช้ผู้ดูแล',
            'page_title' => 'จัดการผู้ใช้',
            'navigation' => 'ผู้ใช้ผู้ดูแล',
            'search_label' => 'ค้นหาผู้ใช้',
            'search_placeholder' => 'ค้นหาด้วยชื่อหรืออีเมล',
            'filters' => [
                'all' => 'ทุกสิทธิ์',
                'admin' => 'ผู้ดูแลระบบ',
                'manager' => 'ผู้จัดการ',
                'staff' => 'พนักงาน',
            ],
            'table' => [
                'name' => 'ชื่อ-นามสกุล',
                'email' => 'อีเมล',
                'role' => 'สิทธิ์',
                'status' => 'สถานะ',
                'last_active' => 'เข้าใช้งานล่าสุด',
            ],
            'roles' => [
                'admin' => 'ผู้ดูแลระบบ',
                'manager' => 'ผู้จัดการ',
                'staff' => 'พนักงาน',
            ],
            'statuses' => [
                'active' => 'ใช้งานอยู่',
                'invited' => 'ส่งคำเชิญแล้ว',
                'suspended' => 'ระงับการใช้งาน',
            ],
            'buttons' => [
                'create' => 'สร้างใหม่',
            ],
        ],
    ],
    'auth' => [
        'login_title' => 'เข้าสู่ระบบ',
        'login_heading' => 'ลงชื่อเข้าใช้ Mobile Order',
        'login_subheading' => 'กรอกอีเมลและรหัสผ่านที่ลงทะเบียนไว้เพื่อดำเนินการต่อ',
        'email' => 'อีเมล',
        'password' => 'รหัสผ่าน',
        'remember_me' => 'จดจำการเข้าสู่ระบบ',
        'login_button' => 'เข้าสู่ระบบ',
        'logout_button' => 'ออกจากระบบ',
        'failed' => 'ไม่พบข้อมูลสำหรับอีเมลหรือรหัสผ่านนี้',
        'logged_in_as' => 'เข้าสู่ระบบเป็น :name',
    ],
];
