@extends('layout')

@section('content')
    <a href="/">
        <h1 class="text-xl font-semibold text-white uppercase tracking-wider text-center">Donation Report</h1>
    </a>

    <div class="my-8 bg-white rounded p-8" >
        <table>
            <thead class="border-b">
            <tr>
                <th class="p-2 text-left">ID</th>
                <th class="p-2 text-left">Name</th>
                <th class="p-2 text-left">Email</th>
                <th class="p-2 text-right">Amount ($)</th>
            </tr>
            </thead>
            <tbody>
        @foreach($donations as $donation)
            <tr class="border-b">
                <td class="p-2 text-left">{{ $donation->id }}</td>
                <td class="p-2 text-left">{{ $donation->name }}</td>
                <td class="p-2 text-left">{{ substr($donation->email, 0, 1) }}****************</td>
                <td class="p-2 text-right">{{ $donation->amount }}</td>
            </tr>
        @endforeach
            </tbody>
        </table>
    </div>
@endsection
