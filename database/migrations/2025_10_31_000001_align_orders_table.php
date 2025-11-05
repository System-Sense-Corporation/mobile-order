<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1) ยังไม่มีตาราง -> สร้างใหม่ตามสคีมาเป้าหมาย
        if (! Schema::hasTable('orders')) {
            $this->createOrdersTable();
            return;
        }

        // 2) มีตารางแล้ว -> เช็กคอลัมน์ที่ควรมี
        $required = [
            'customer_id',
            'product_id',
            'quantity',
            'status',
            'order_date',
            'delivery_date',
            'notes',
        ];

        $missing = array_values(array_filter(
            $required,
            static fn (string $col): bool => ! Schema::hasColumn('orders', $col)
        ));

        // สคีมาตรงแล้ว ไม่ต้องทำอะไร
        if ($missing === []) {
            return;
        }

        $missingLookup = array_flip($missing);

        // 2.1 ขาดเฉพาะคอลัมน์เสริม -> แก้สคีมาในที่เดิม
        if (! isset($missingLookup['customer_id']) && ! isset($missingLookup['product_id'])) {
            Schema::table('orders', function (Blueprint $table) use ($missingLookup): void {
                if (isset($missingLookup['quantity'])) {
                    $table->unsignedInteger('quantity')->default(1);
                }
                if (isset($missingLookup['status'])) {
                    $table->string('status')->default('pending');
                }
                if (isset($missingLookup['order_date'])) {
                    $table->date('order_date')->nullable();
                }
                if (isset($missingLookup['delivery_date'])) {
                    $table->date('delivery_date')->nullable();
                }
                if (isset($missingLookup['notes'])) {
                    $table->text('notes')->nullable();
                }
            });

            return;
        }

        // 2.2 โครงสร้างเก่ามาก (ไม่มี FK หลัก) -> ย้ายข้อมูล แล้วสร้างตารางใหม่
        DB::transaction(function (): void {
            if (Schema::hasTable('orders_legacy')) {
                Schema::drop('orders_legacy');
            }

            Schema::rename('orders', 'orders_legacy');

            $this->createOrdersTable();

            // ย้ายข้อมูลจากตารางเก่าเข้าแบบ best-effort mapping
            $legacy = DB::table('orders_legacy')->get();

            foreach ($legacy as $row) {
                $customerId = $this->matchCustomerId($row->customer_name ?? null);
                [$productId, $qty] = $this->matchProduct($row->items ?? null);

                DB::table('orders')->insert([
                    'customer_id'   => $customerId,
                    'product_id'    => $productId,
                    'quantity'      => $qty ?? 1,
                    'status'        => $row->status ?? 'pending',
                    'order_date'    => $this->normalizeDate($row->received_at ?? $row->created_at ?? null),
                    'delivery_date' => null,
                    'notes'         => null,
                    'created_at'    => $row->created_at ?? now(),
                    'updated_at'    => $row->updated_at ?? now(),
                ]);
            }

            Schema::drop('orders_legacy');
        });
    }

    /**
     * Reverse the migrations.
     * ทำแบบระมัดระวัง: ไม่ลบทั้งตารางในโปรดักชัน
     */
    public function down(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        // ถ้าเคย rename แล้วเหลือ orders_legacy อยู่ (เคส rollback กลางทาง)
        if (Schema::hasTable('orders_legacy')) {
            // กลับไปใช้ตารางเดิม
            Schema::drop('orders');
            Schema::rename('orders_legacy', 'orders');
            return;
        }

        // เคสที่เราแค่เพิ่มคอลัมน์ใหม่: เอาคอลัมน์ที่เพิ่มออกอย่างระมัดระวัง
        Schema::table('orders', function (Blueprint $table): void {
            if (Schema::hasColumn('orders', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('orders', 'delivery_date')) {
                $table->dropColumn('delivery_date');
            }
            if (Schema::hasColumn('orders', 'order_date')) {
                $table->dropColumn('order_date');
            }
            if (Schema::hasColumn('orders', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('orders', 'quantity')) {
                $table->dropColumn('quantity');
            }
        });
    }

    private function createOrdersTable(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->string('status')->default('pending');
            $table->date('order_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    private function matchCustomerId(?string $customerName): ?int
    {
        if ($customerName === null || $customerName === '') {
            return null;
        }

        return DB::table('customers')->where('name', $customerName)->value('id');
    }

    /**
     * @return array{0: int|null, 1: int|null}
     */
    private function matchProduct(?string $items): array
    {
        if ($items === null || $items === '') {
            return [null, null];
        }

        $name = $items;
        $qty  = null;

        // รองรับรูปแบบ: "Product × 3" / "Product x3"
        if (preg_match('/^(.+?)[\s\x{00D7}xX]+(\d+)$/u', trim($items), $m) === 1) {
            $name = trim($m[1]);
            $qty  = (int) $m[2];
        }

        $productId = DB::table('products')->where('name', $name)->value('id');

        return [$productId, $qty];
    }

    private function normalizeDate(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $ts = strtotime($value);
        if ($ts === false) {
            return null;
        }

        return date('Y-m-d', $ts);
    }
};
