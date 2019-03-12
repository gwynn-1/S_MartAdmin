<?php

namespace App\Http\Controllers;

use App\Http\Model\StockingInput;
use Illuminate\Http\Request;

class StockingController extends Controller
{
    private $stocking;

    public function __construct()
    {
        $this->stocking = new StockingInput();
    }

    //
    public function indexAction(Request $request){
        $params = $request->all();

//        if (isset($params['export']) && $params['export']){
//            $this->exportExcel($params);
//        }

        $oResult = $this->stocking->getAllStocking($params);
        $stt = intval($request->get('page', 1));
        $stt = (($stt ? $stt : 1) - 1) * 25 +1;

        return view('admin.stocking.index', [
//            '_invite' => $arrTotalInvite,
            'object' => $oResult,
            'params' => $params,
            'stt' => $stt
        ]);
    }
}
