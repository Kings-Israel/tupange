@extends('layouts.app')

@section('content')
<div class="container">
    <table class="table table-bordered">
        <thead>
            <td>Company Logo</td>
            <td>Company Name</td>
            <td>Company Location</td>
            <td>Company Email</td>
            <td>Company Phone Number</td>
            <td>Delete</td>
        </thead>
        <tbody>
            @foreach ($vendors as $vendor)
                <tr>
                    <td>
                        <img src="{{ $vendor->getCompanyLogo($vendor->company_logo) }}" alt="{{ $vendor->company_name }}" width="100px">
                        {{-- <img src="{{ Storage::disk('vendor')->url('logo/'.$vendor->company_logo) }}" alt="" width="100px"> --}}
                    </td>
                    <td>{{ $vendor->company_name }}</td>
                    <td>{{ $vendor->location }}</td>
                    <td>{{ $vendor->company_email }}</td>
                    <td>{{ $vendor->company_phone_number }}</td>
                    <td><a href="{{ route('vendor.delete', $vendor->id) }}" class="btn btn-sm btn-danger">Delete</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
