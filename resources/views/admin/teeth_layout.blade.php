@extends('layouts.admin')

@section('title', 'Teeth Layout Management')

@section('content')

@include('admin.teeth_layout.styles')

<div class="page-header">
    <h2 class="page-title">Teeth Layout Management</h2>
</div>

@include('admin.teeth_layout.search-section')

@include('admin.teeth_layout.patient-section')

@include('admin.teeth_layout.modal')

@include('admin.teeth_layout.scripts')

@endsection
