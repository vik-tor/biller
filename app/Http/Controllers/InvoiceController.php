<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InvoiceController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return Inertia::render('Invoices/New', [
      'clients' => Client::all(),
      'companies' => Company::all(),
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      "date" => 'required|date',
      "due_date" => 'required|date',
      "client_id" => 'required|integer',
      "company_id" => 'required|integer',
      "invoice_no" => 'nullable|string',
      "details" => 'nullable|string',
      "terms" => 'nullable|string',
      "payment_info" => 'nullable|string',
    ]);

    if (!$request->items) {
      return new Exception('Add items to save order');
    }

    DB::beginTransaction();

    $invoice = Invoice::create($request->all());

    foreach ($request->items as $item) {
      $item['invoice_id'] = $invoice->id;
      $item['company_id'] = $request->company_id;
      InvoiceLine::create($item);
    }

    DB::commit();

    return redirect('/');
  }

  /**
   * Display the specified resource.
   */
  public function show(Invoice $invoice)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Invoice $invoice)
  {
    return Inertia::render('Invoices/Edit', [
      'clients' => Client::all(),
      'companies' => Company::all(),
      'invoice' => $invoice,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Invoice $invoice)
  {
    $request->validate([
      "date" => 'required|date',
      "due_date" => 'required|date',
      "client_id" => 'required|integer',
      "company_id" => 'required|integer',
      "invoice_no" => 'nullable|string',
      "details" => 'nullable|string',
      "terms" => 'nullable|string',
      "payment_info" => 'nullable|string',
    ]);

    $invoice->update($request->all());

    return redirect('/');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Invoice $invoice)
  {
    //
  }
}
