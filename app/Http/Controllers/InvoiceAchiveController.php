<?php

namespace App\Http\Controllers;

use App\Invoices;
use Illuminate\Http\Request;

class InvoiceAchiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ارشيف الفواتير', ['only' => ['index']]);
    }
    public function index()
    {
        $invoices = invoices::onlyTrashed()->get();
        return view('invoices.Archive_Invoices', compact('invoices'));
    }
    public function update(Request $request)
    {
        Invoices::withTrashed()->where('id', $request->invoice_id)->restore();
        session()->flash('restore_invoice');
        return redirect('/invoices');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoices = invoices::withTrashed()->where('id', $request->invoice_id)->first();
        $invoices->forceDelete();
        session()->flash('delete_invoice');
        return redirect('/Archive');

    }
}
