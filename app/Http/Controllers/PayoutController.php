<?php

namespace App\Http\Controllers;


use App\Models\Payout;
use Illuminate\Http\Request;
use App\Models\PayoutTransaction;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Support\HtmlString;


class PayoutController extends Controller
{
    public $page = 'transaction';
    public function payout()
    {
        $payout = Payout::where('status','pending')->get();
        return view('admin.payout.index',compact('payout'));
    }
    public function update(Request $request)
    {
        $payout =Payout::where('status',$request->status)->get();
        // $payout=Payout::where('status','reject')->where('id',$id)->get();
        return view('admin.payout.index',compact('payout'));
    }
    public function transaction(Request $request)
    {
        if ($request->ajax()) {
            $data = PayoutTransaction::with('payout.customer')->get();
            return datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    if ($model->status == 'approved') {
                        $formatData = '<button class="btn btn-sm btn-success">Success</button>';
                    } else if ($model->status == 'pending') {
                        $formatData = '<button class="btn btn-sm btn-warning">Failed</button>';
                    } else if ($model->status == 'rejected') {
                        $formatData = '<button class="btn btn-sm btn-danger">Rejected</button>';
                    } else {
                        $formatData = '<button class="btn btn-sm btn-danger">' . ucfirst($model->status) . '</button>';
                    }
                
                    return new HtmlString($formatData);
                })

                ->rawColumns([])
                ->make(true);
        }
        // $transaction= PayoutTransaction::all();
        return view('admin.payout.transaction');
    }
}
