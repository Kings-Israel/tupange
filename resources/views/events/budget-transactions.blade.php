@extends('layouts.master')
@section('title', 'Transactions')
@section('css')
<style>
   @media only screen and (max-width: 768px) {
      .member-statistical {
         margin-top: 50px;
      }
   }
</style>
@endsection
@section('content')
   <livewire:budget-transactions :event="$event" :budget="$budget" />
@endsection
