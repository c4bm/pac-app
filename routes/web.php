<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/donate', function () {
    return view('donate');
});

/*
Route::get('/report',function() {
    $donations = \DB::table('donations')->get();
    $donations = $donations->map(function($donation) {
        $d = json_decode($donation->donation_data);
        $d->id = $donation->id;
        return $d;
    });

    return view('report')->with(['donations' => $donations]);
});*/

Route::post('/donate', function () {
    $data = request()->all();

    // TODO save to database and get donation ID
    $data['name'] = $data['firstName'] . ' ' . $data['lastName'];

    $data['donationId'] = \DB::table('donations')->insertGetId(
        ['donation_data' => json_encode($data)]
    );

    \Mail::send('receipt', ['data' => $data], function ($m) use ($data) {
        $m->from('contact@bettermontgomery.us', 'Coalition for Better Montgomery');
        $m->to($data['email'], $data['name'])->subject('Receipt for Donation #' . $data['donationId']);
    });

    return view('donated')->with(['data' => $data]);
});
