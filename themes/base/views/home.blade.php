@extends('base::layouts.app')

@section('content')
    <router-view></router-view>
@endsection
@push('css-stack')
@endpush
@push('js-stack')
@endpush
