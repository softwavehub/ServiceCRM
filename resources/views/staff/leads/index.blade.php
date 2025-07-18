@extends('backend.layouts.app')
@section('title')
    Leads
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            @if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger">{{Session::get('error')}}</div>
            @endif
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">Leads</h5>
                    </div>

                </div>
                <div class="card-body">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}

@endpush
