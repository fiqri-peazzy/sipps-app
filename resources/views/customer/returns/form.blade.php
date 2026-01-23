@extends('layouts.customer')

@section('customer-title', 'Pengajuan Return')
@section('customer-subtitle', 'Sampaikan kendala produk Anda. Kami berkomitmen memberikan kualitas terbaik untuk setiap
    pesanan.')

@section('customer-content')
    <div class="mt-8">
        @livewire('customer.customer-return-form', ['order' => $order])
    </div>
@endsection
