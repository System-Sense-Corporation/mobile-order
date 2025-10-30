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
        'admin-users' => 'การจัดการผู้ใช้',
        'settings' => 'ตั้งค่า',
        'profile' => 'โปรไฟล์',
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
    'admin' => [
        'users' => [
            'title' => 'การจัดการผู้ใช้',
            'description' => 'จัดการบัญชีพนักงานและสิทธิ์การใช้งาน',
            'create_button' => 'สร้างใหม่',
            'create_title' => 'สร้างผู้ใช้',
            'create_description' => 'ลงทะเบียนบัญชีพนักงานใหม่และกำหนดสิทธิ์เริ่มต้นให้เหมาะสม',
            'validation_error_heading' => 'โปรดตรวจสอบข้อมูลที่กรอก',
            'flash' => [
                'created' => 'สร้างผู้ใช้เรียบร้อยแล้ว',
                'permissions_updated' => 'บันทึกการตั้งค่าสิทธิ์เรียบร้อยแล้ว',
            ],
            'roles' => [
                'admin' => 'ผู้ดูแล',
                'manager' => 'ผู้จัดการ',
                'staff' => 'พนักงาน',
            ],
            'role_descriptions' => [
                'admin' => 'เข้าถึงทุกหน้าการจัดการได้ทั้งหมด',
                'manager' => 'ดูแลคำสั่งซื้อ สินค้า และลูกค้าได้',
                'staff' => 'เข้าถึงเฉพาะหน้าที่จำเป็นต่อการปฏิบัติงานประจำวัน',
            ],
            'form' => [
                'name' => [
                    'label' => 'ชื่อ-นามสกุล',
                ],
                'email' => [
                    'label' => 'อีเมล',
                ],
                'phone' => [
                    'label' => 'โทรศัพท์มือถือ',
                    'placeholder' => '080-1234-5678',
                ],
                'department' => [
                    'label' => 'แผนก',
                    'placeholder' => 'เลือกแผนก',
                    'options' => [
                        'sales' => 'ฝ่ายขาย',
                        'support' => 'ฝ่ายบริการลูกค้า',
                        'logistics' => 'ฝ่ายโลจิสติกส์',
                    ],
                ],
                'authority' => [
                    'label' => 'สิทธิ์',
                ],
                'notify_new_orders' => [
                    'label' => 'ส่งอีเมลเมื่อมีคำสั่งซื้อใหม่',
                    'help' => 'รับอีเมลแจ้งเตือนทันทีเมื่อมีการสร้างคำสั่งซื้อใหม่',
                ],
                'require_password_change' => [
                    'label' => 'บังคับให้เปลี่ยนรหัสผ่านเมื่อเข้าสู่ระบบครั้งแรก',
                    'help' => 'ระบบจะให้ผู้ใช้เปลี่ยนรหัสผ่านหลังจากเข้าสู่ระบบครั้งแรก',
                ],
                'password' => [
                    'label' => 'รหัสผ่าน',
                    'placeholder' => 'อย่างน้อย 8 ตัวอักษร',
                ],
                'password_confirmation' => [
                    'label' => 'ยืนยันรหัสผ่าน',
                    'placeholder' => 'กรอกรหัสผ่านอีกครั้ง',
                ],
                'cancel_button' => 'ยกเลิก',
                'submit_button' => 'บันทึกผู้ใช้',
            ],
            'permissions_matrix' => [
                'title' => 'ตารางสิทธิ์การใช้งาน',
                'description' => 'ปรับสิทธิ์ที่แต่ละบทบาทสามารถเข้าถึงได้ด้วยการเลือกช่องสี่เหลี่ยม',
                'table' => [
                    'permission' => 'หน้า',
                    'description' => 'รายละเอียด',
                ],
                'groups' => [
                    'general' => 'เมนูหลัก',
                    'orders' => 'งานคำสั่งซื้อ',
                    'catalog' => 'การจัดการสินค้า',
                    'customers' => 'การจัดการลูกค้า',
                    'settings' => 'การตั้งค่า',
                ],
                'permissions' => [
                    'dashboard_view' => 'หน้าแรก',
                    'mobile_orders_view' => 'บันทึกคำสั่งซื้อ',
                    'orders_view' => 'รายการคำสั่งซื้อ',
                    'products_view' => 'ข้อมูลสินค้า',
                    'customers_view' => 'ข้อมูลลูกค้า',
                    'settings_manage' => 'ตั้งค่าระบบ',
                    'admin_users_manage' => 'การจัดการผู้ใช้',
                ],
                'descriptions' => [
                    'dashboard_view' => 'เข้าถึงหน้าหลักของระบบ',
                    'mobile_orders_view' => 'สร้างหรือแก้ไขคำสั่งซื้อผ่านมือถือ',
                    'orders_view' => 'ตรวจสอบและติดตามความคืบหน้าคำสั่งซื้อ',
                    'products_view' => 'ดูรายละเอียดสินค้าในระบบ',
                    'customers_view' => 'ดูข้อมูลลูกค้า',
                    'settings_manage' => 'ปรับแต่งการแจ้งเตือนและการตั้งค่าระบบ',
                    'admin_users_manage' => 'จัดการบัญชีผู้ใช้และสิทธิ์',
                ],
                'checkbox_label' => ':role สามารถเข้าถึง :permission',
                'save_button' => 'บันทึกการเปลี่ยนแปลง',
            ],
            'errors' => [
                'invalid_role' => 'บทบาท ":role" ไม่ถูกต้อง',
                'invalid_permission' => 'สิทธิ์ ":permission" ไม่ถูกต้อง',
            ],
        ],
    ],
    'admin_users' => [
        'title' => 'การจัดการผู้ใช้',
        'description' => 'จัดการบัญชีพนักงานและสิทธิ์การใช้งาน',
        'actions' => [
            'create' => 'สร้างใหม่',
        ],
        'filters' => [
            'permission' => 'กรองตามสิทธิ์',
        ],
        'placeholders' => [
            'search' => 'ค้นหาจากชื่อ อีเมล หรือแผนก',
        ],
        'table' => [
            'no' => 'ลำดับ',
            'user_id' => 'รหัสผู้ใช้',
            'name' => 'ชื่อ',
            'department' => 'แผนก',
            'authority' => 'สิทธิ์',
            'email' => 'อีเมล',
            'phone' => 'โทรศัพท์มือถือ',
            'status' => 'สถานะ',
            'last_login' => 'เข้าสู่ระบบล่าสุด',
        ],
        'authorities' => [
            'admin' => 'ผู้ดูแล',
            'editor' => 'ผู้แก้ไข',
            'viewer' => 'ผู้ชม',
        ],
        'statuses' => [
            'active' => 'ใช้งานอยู่',
            'inactive' => 'ปิดใช้งาน',
            'suspended' => 'ระงับชั่วคราว',
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
    'profile' => [
        'title' => 'โปรไฟล์',
        'description' => 'จัดการวิธีการเข้าสู่ระบบและการดูแลบัญชีของคุณ.',
        'sections' => [
            'password' => [
                'title' => 'อัปเดตรหัสผ่าน',
                'description' => 'ตั้งรหัสผ่านให้รัดกุมเพื่อปกป้องบัญชีของคุณ.',
                'fields' => [
                    'current' => 'รหัสผ่านปัจจุบัน',
                    'new' => 'รหัสผ่านใหม่',
                    'confirmation' => 'ยืนยันรหัสผ่านใหม่',
                ],
                'helper' => 'ควรใช้ความยาวอย่างน้อย 12 ตัวอักษรผสมตัวอักษร ตัวเลข และสัญลักษณ์.',
                'button' => 'บันทึกรหัสผ่านใหม่',
            ],
            'account' => [
                'title' => 'การจัดการบัญชี',
                'description' => 'ควบคุมสิ่งที่จะเกิดขึ้นกับบัญชีเมื่อคุณเลิกใช้งาน.',
                'delete_warning' => 'การลบบัญชีจะลบข้อมูลที่เกี่ยวข้องทั้งหมด และไม่สามารถยกเลิกได้.',
                'support' => 'หากยังไม่แน่ใจ โปรดติดต่อทีมสนับสนุนก่อนดำเนินการ.',
                'button' => 'ลบบัญชี',
            ],
        ],
    ],

    // เพิ่มชุดนี้เข้าไปใน return [...]
'common' => [
    'name'      => 'ชื่อ',
    'email'     => 'อีเมล',
    'telephone' => 'เบอร์โทรศัพท์',
    'save'      => 'บันทึก',
],

'profile' => array_merge($GLOBALS['profile'] ?? [], [ // ถ้าไม่ใช้ $GLOBALS ให้รวมมือแทนได้
    'sections' => [
        // เพิ่ม block นี้
        'account_information' => [
            'title' => 'ข้อมูลบัญชีผู้ใช้',
            'description' => 'อัปเดตชื่อ อีเมล และเบอร์โทรของคุณได้ที่นี่',
        ],
    ],
]),

];
