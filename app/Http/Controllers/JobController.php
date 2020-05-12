<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('jobs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('jobs.create');
    }

    public function create_payment_intent(Request $request)
    {
        // Create PaymentIntent
      // 1. Validate form data
      $this->validateJobData($request);

      // 2. Create payment intent
      \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

      $payment_intent = \Stripe\PaymentIntent::create([
        'amount' => 1000,
        'currency' => 'usd',
      ]);

      // 3. Return client secret and paymetn intent ID
      return response()->json([
          'clientSecret' => $payment_intent->client_secret,
          'id' => $payment_intent->id,
      ]);
    }

    function validateJobData(Request $request)
    {
      return $request->validate([
        'title' => 'required',
      ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validateJobData($request);

      $myJob = new \App\MyJob;
      $myJob->title = $request->title;
      $myJob->job_type = $request->job_type;
      $myJob->payment_intent = $request->payment_intent;
      $myJob->save();

      return $myJob->toJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
