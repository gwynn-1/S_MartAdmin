<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class StockingInput extends Model
{
    //
    protected $table = "stocking_input";
    protected $primaryKey = "stk_input_id";
    protected $fillable =['stk_input_id', 'warehouse_id', 'supplier_id', 'stocking_code', 'staff_receive', 'date_receive', 'status', 'type', 'updated_at', 'created_at', 'updated_by', 'created_by'];
    public $timestamps=true;

    public function StockingInputDetail(){
        return $this->hasMany("App\Http\Model\StockingInputDetail","stk_input_id","stk_input_id");
    }

    public function getAllStocking($filter, $isPaging = true){
        $oSelect = $this->with("StockingInputDetail")
                        ->leftJoin("warehouse","warehouse.warehouse_id","=","stocking_input.warehouse_id")
                        ->leftJoin("suppliers","suppliers.supplier_id","=","stocking_input.supplier_id")
                        ->leftJoin("staffs as st_receive","st_receive.staff_id","=","stocking_input.staff_receive")
                        ->leftJoin("staffs as st_updated","st_updated.staff_id","=","stocking_input.updated_by")
                        ->select(
                            "warehouse.warehouse_name",
                            "suppliers.supplier_name",
                            "stocking_input.stocking_code",
                            "stocking_input.date_receive",
                            "st_receive.full_name as staff_receive_name",
                            "st_updated.full_name as staff_updated_name",
                            "stocking_input.status",
                            "stocking_input.type",
                            "stocking_input.created_at",
                            "stocking_input.updated_at"
                        );
        if (isset($filter['keyword']) && !empty(trim($filter['keyword'])) && !empty($filter['type'])) {
            if($filter["type"]=="stocking_code"){
                $oSelect->where("stocking_input.{$filter['type']}", 'like', '%' . $filter['keyword'] . '%');
            }
            if($filter["type"]=="supplier_name"){
                $oSelect->where("suppliers.{$filter['type']}", 'like', '%' . $filter['keyword'] . '%');
            }
        }
        if (isset($filter['type-date']) && !empty($filter['start_date']) && !empty($filter['end_date'])) {
            $from = Carbon::createFromFormat('d/m/Y', $filter['start_date'])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $filter['end_date'])->format('Y-m-d');
            $oSelect->whereBetween(\DB::raw('date(product.'.$filter['type-date'].')'), [$from, $to]);
        }

        if ($isPaging) {
            return $oSelect->paginate(25);
        }
        else {
            return $oSelect->get();
        }
    }
}
