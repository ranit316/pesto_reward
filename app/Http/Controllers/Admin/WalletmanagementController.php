<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Yajra\DataTables\DataTables;

class WalletmanagementController extends Controller
{
  public $page = 'wallet';
  public $page1 = 'transaction';

  public function walletlist(Request $request)
  {
    if ($request->ajax()) {
      $data = Wallet::with('walletlist')->latest();
      return datatables::of($data)
          ->addIndexColumn()
          ->addColumn('status', function ($row) {
              $checked = $row->status == 'active' ? 'checked' : '';

              return '<div class="form-check form-switch form-switch-md mb-2">
                          <input class="form-check-input" type="checkbox" id="toggleSwitch_' . $row->id . '" ' . $checked . ' onclick="changeStatus(\'' . route('admin.wallet.status', ['id' => $row->id]) . '\', \'status' . $row->id . '\')">
                          <label class="form-check-label" for="toggleSwitch_' . $row->id . '"></label>
                      </div>';
          })
          ->addColumn('action', function ($row) {
              $actionBtn = view('admin.walletmanagemeny.button', ['item' => $row, 'page' => $this->page]);
              return $actionBtn;
          })
          ->rawColumns(['status','action'])
          ->make(true);
  }
  return view('admin.walletmanagemeny.walletlist');
  }
  
  public function walletstatus($id)
  {
    $user_id = auth()->user()->id;
    $wallet = Wallet::where('customer_id', $id)->first();

    if ($wallet->status == "inactive") {
      $wallet->status = "active";
      $wallet->updated_by = $user_id;
      $wallet->save();
      if ($wallet->save()); {
        return redirect()->route('admin.walletmanagement.list');
      }
    } else {

      $wallet->status = "inactive";
      $wallet->updated_by = $user_id;
      $wallet->save();
      if ($wallet->save()); {
        return redirect()->route('admin.walletmanagement.list');
      }
    }
  }
  public function transactionview(Request $request,$id)
  {
    $cus_id = $id;
    $data = WalletTransaction::where('wallet_id', $id)->get();
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = view('admin.walletmanagemeny.button', ['item' => $row, 'page' => $this->page1]);
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    // $trans = WalletTransaction::where('wallet_id', $id)->get();
    return view('admin.walletmanagemeny.transaction', compact('cus_id'));
  }
  public function alltrancaction(Request $request)
  {
    $data = WalletTransaction::with('wallet.customer')->latest();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // $actionBtn = view('', ['item' => $row, 'page' => $this->page1]);
                    // return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
              }
    // $alltrans = WalletTransaction::all();
    return view('admin.walletmanagemeny.alltransaction',compact('data'));

  }
}

