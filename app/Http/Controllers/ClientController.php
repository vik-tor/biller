<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ClientController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $clients = Client::all();

    return Inertia::render('Clients', ['clients' => $clients]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return Inertia::render('Clients/New', [
      'clients' => Client::all(),
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      "name" => 'required|string',
    ]);

    DB::beginTransaction();

    $client = Client::create($request->all());

    DB::commit();

    return redirect('/clients');
  }

  /**
   * Display the specified resource.
   */
  public function show(Client $client)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Client $client)
  {
    return Inertia::render('Clients/Edit', [
      'client' => $client,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Client $client)
  {
    $request->validate([
      "name" => 'required|string',
    ]);

    $client->update($request->all());

    return redirect('/clients');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Client $client)
  {
    //
  }
}
