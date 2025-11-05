<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // เพิ่มบรรทัดนี้เข้าไปค่ะ สำคัญมาก!
    protected $fillable = ['key', 'value'];
}