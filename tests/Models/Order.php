<?php

namespace R4nkt\Manageable\Test\Models;

use Illuminate\Database\Eloquent\Model;
use R4nkt\Manageable\Manageable;

class Order extends Model
{
    use Manageable;

    protected $fillable = [
        'title',
    ];
}
