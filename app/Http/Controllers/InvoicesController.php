<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Invoices;
use App\invoices_details;
use App\invoice_attachments;
use App\Sections;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InvoicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:قائمة الفواتير', ['only' => ['index']]);
        $this->middleware('permission:اضافة فاتورة', ['only' => ['store', 'create']]);
        $this->middleware('permission:تعديل الفاتورة', ['only' => ['update', 'edit']]);
        $this->middleware('permission:حذف الفاتورة', ['only' => ['destroy']]);
        $this->middleware('permission:تغير حالة الدفع', ['only' => ['Status_Update', 'show']]);
        $this->middleware('permission:الفواتير المدفوعة', ['only' => ['Invoice_Paid']]);
        $this->middleware('permission:الفواتير الغير مدفوعة', ['only' => ['Invoice_unPaid']]);
        $this->middleware('permission:الفواتير المدفوعة جزئيا', ['only' => ['Invoice_Partial']]);
        $this->middleware('permission:تصدير EXCEL', ['only' => ['export']]);
    }
    public function index()
    {
        $invoices = Invoices::all();
        return view('invoices.invoices')->with([
            'invoices' => $invoices,
        ]);
    }

    public function create()
    {
        $sections = Sections::all();

        return view('invoices.add_new_invoices')->with([
            'sections' => $sections,
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'invoice_number' => 'required',
            'invoice_Date' => 'required',
            'Due_date' => 'required',
            'Amount_collection' => 'required',
            'Amount_Commission' => 'required',
            'Discount' => 'required',
            'Rate_VAT' => 'required',
            'Value_VAT' => 'required',
            'Total' => 'required',
            'note' => 'required',
        ]);
        invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);
        $invoice_id = invoices::latest()->first()->id;
        invoices_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {
            $invoice_id = Invoices::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;
            $attachments = new invoice_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();
            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }

        $user = User::first();
        // $user->notify(new AddInvoices($invoice_id));
        Notification::send($user, new \App\Notifications\AddInvoices($invoice_id));
        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');

        $user = User::get();
        $invoices = invoices::latest()->first();
        Notification::send($user, new \App\Notifications\AddInvoice($invoices));
        return back();
    }

    public function show($id)
    {
        $invoices = invoices::where('id', $id)->first();
        return view('invoices.status_update', compact('invoices'));
    }

    public function Status_Update($id, Request $request)
    {
        $request->validate([
            "invoice_number" => "required",
            "invoice_Date" => "required",
            "Due_date" => "required",
            "Section" => "required",
            "product" => "required",
            "Amount_collection" => "required",
            "Discount" => "required",
            "Rate_VAT" => "required",
            "Value_VAT" => "required",
            "Total" => "required",
            "note" => "required",
            "Payment_Date" => "required",
            'Status' => "required",
        ]);
        $invoices = invoices::findOrFail($id);

        if ($request->Status === 'مدفوعة') {

            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        } else {
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/invoices');

    }

    public function Invoice_Paid()
    {
        // الفواتير المدفوعة
        $invoices = Invoices::where('Value_Status', 1)->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }

    public function Invoice_unPaid()
    { // الفواتير العير مدفوعة
        $invoices = Invoices::where('Value_Status', 2)->get();
        return view('invoices.invoices_unpaid', compact('invoices'));
    }

    public function Invoice_Partial()
    { // الفواتير المدفوعة جزئياً
        $invoices = Invoices::where('Value_Status', 3)->get();
        return view('invoices.invoices_Partial', compact('invoices'));
    }

    public function edit($id)
    {
        $invoices = Invoices::where('id', $id)->first();
        $sections = Sections::all();
        return view('invoices.edait_invoices')->with([
            'sections' => $sections,
            'invoices' => $invoices,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $invoices = invoices::findOrFail($request->invoice_id);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);
        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return back();
    }

    public function destroy(Request $request)
    {

        $invoices = invoices::where('id', $request->invoice_id)->first();
        $Details = invoice_attachments::where('invoice_id', $request->invoice_id)->first();

        if (!empty($Details->invoice_number)) {
            Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
        }

        $id_page = $request->id_page;

        if (!$id_page == 2) {

            if (!empty($Details->invoice_number)) {
                Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
            }

            $invoices->forceDelete();
            session()->flash('delete_invoice');
            return redirect('/invoices');} else {

            $invoices->delete();
            session()->flash('archive_invoice');
            return redirect('/Archive');
        }
    }

    public function getproducts($id)
    {
        // this method for edit in ajax jquery with method update and create
        $products = DB::table('products')->where('section_id', $id)->pluck('product_name', 'id');
        return json_encode($products);
    }

    public function export()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }

    public function notificationallclear(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back();
    }
}
