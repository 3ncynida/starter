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

    public function activateMembership()
    {
        $this->member_start = now();
        $this->member_expired = now()->addMonth();
        $this->is_member = true;
        $this->save();
    }

    public function deactivateMembership()
    {
        $this->is_member = false;
        $this->save();
    }

    public function checkMembershipStatus()
    {
        if (!$this->is_member || !$this->member_expired) {
            return false;
        }

        if ($this->member_expired->isPast()) {
            $this->deactivateMembership();
            return false;
        }

        return true;
    }

    public function getRemainingDaysAttribute()
    {
        if (!$this->is_member || !$this->member_expired) {
            return 0;
        }

        return max(0, now()->diffInDays($this->member_expired, false));
    }
}
