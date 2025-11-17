<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';

    protected $primaryKey = 'PelangganID';

    protected $fillable = [
        'NamaPelanggan',
        'Alamat',
        'NomorTelepon',
        'member_start',
        'member_expired',
        'is_member'
    ];

    protected $casts = [
        'member_start' => 'datetime',
        'member_expired' => 'datetime',
        'is_member' => 'boolean'
    ];
    
    // Method untuk mengecek status membership
    public function checkMembershipStatus()
    {
        if ($this->is_member && $this->member_expired && $this->member_expired->isPast()) {
            $this->is_member = false;
            $this->save();
        }
        return $this->is_member;
    }

    // Method untuk mengaktifkan membership (1 BULAN)
    public function activateMembership()
    {
        $this->is_member = true;
        $this->member_expired = now()->addMonth(); // 1 bulan
        $this->save();
    }

    // Method untuk menonaktifkan membership
    public function deactivateMembership()
    {
        $this->is_member = false;
        $this->member_expired = null;
        $this->save();
    }

    // Method untuk mengecek apakah member aktif
    public function isMemberActive()
    {
        return $this->is_member && $this->member_expired && $this->member_expired->isFuture();
    }

    public function getRemainingDaysAttribute()
    {
        if (!$this->is_member || !$this->member_expired) {
            return 0;
        }

        return max(0, now()->diffInDays($this->member_expired, false));
    }
}
