<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HupunTradesRawModel extends Model
{
    protected $table = 'hupun_trades_raw';
    protected $primaryKey = 'id';

    protected $fillable = array('timestamp', 'raw');
}
