<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class StockingInputDetail extends Model
{
    //
    protected $table = "stocking_input_details";
    protected $primaryKey = "stk_input_detail_id";
    protected $fillable =['stk_input_detail_id', 'stk_input_id', 'product_id', 'unit_id', 'barcode', 'total_quantity', 'receive_quantity', 'total_price', 'updated_by', 'created_by', 'updated_at', 'created_at', 'note'];


}
