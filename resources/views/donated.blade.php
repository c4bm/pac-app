@extends('layout')

@section('content')
    <a href="/">
        <h1 class="text-xl font-semibold text-white uppercase tracking-wider text-center">Coalition for Better Montgomery</h1>
    </a>

    <div id="donation-app" class="border-white bg-white rounded-lg p-8 mt-8" style="max-width: 650px;">
        <p class="mb-2">Hi {{ $data['name'] }},</p>
        <p>Thank you for your donation of ${{ $data['amount'] }}!  A receipt will be sent to the following email address: </p>
        <p class="font-bold my-2">{{ $data['email'] }}</p>
        <p>In case you lose access to the email receipt but need to communicate with us regarding this donation in the future, please also save the following reference number for this donation:</p>
        <p class="font-bold my-2"># {{ $data['donationId'] }}</p>
        <p class="font-bold opacity-25">(END OF CONTENT)</p>
    </div>
    <div class="my-8 text-white" style="max-width: 650px;">

    </div>
@endsection
