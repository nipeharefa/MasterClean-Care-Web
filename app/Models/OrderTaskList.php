<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTaskList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'task',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [ ];

    /**
     * Get the order record associated with the additionalInfo.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
