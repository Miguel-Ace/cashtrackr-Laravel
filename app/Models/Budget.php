<?php

namespace App\Models;

use App\BudgetType;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $casts = [
        'type' => BudgetType::class
    ];

    public $fillable = [
        'name',
        'amount',
        'type',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isGeneral(): bool
    {
        return $this->type === BudgetType::General;
    }

    public function isGoal(): bool
    {
        return $this->type === BudgetType::Goal;
    }
}
