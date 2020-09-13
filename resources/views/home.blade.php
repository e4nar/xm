@extends('layout.app')

@section('content')
    <basic-form :symbol-options="{{ $nasdaq_company_symbols }}"></basic-form>
@endsection