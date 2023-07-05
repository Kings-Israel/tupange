@extends('layouts.master')
@section('title', 'Gifts Registry')
@section('content')
    <livewire:gift-registry :event="$event" />
@endsection
