@extends('admin.layout.app')
@push('css')
@endpush
@push('js')
@endpush
@section('title', 'Add Customer')
@section('content')

    <div class="main-content" style="padding:0px 0 70px 0">
        <div class="page-content">

<div class= "card card_content mb-0">
   
        <form id="form_data" action="{{ route('admin.customer.update',[$cust->id])}}" method="POST"
            enctype="multipart/form-data">

            <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="type">First Name</label>
                <input type="text" name="first_name" class="form-control" value="{{ strtoupper($cust->first_name) }}">
            </div>
        </div>
    
        <div class="col-sm-4">
            <div class="form-group">
                <label for="type">Last Name</label>
                <input type="text" name="last_name" class="form-control" value="{{ strtoupper($cust->last_name) }}">
            </div>
        </div>
    
        <div class="col-sm-4">
            <div class="form-group">
                <label for="role_id">Phone Number</label>
                <input type="text" class="form-control" placeholder="Enter your Btrand name"
                    value=" {{ $cust->phone_number }} " readonly>
            </div>
        </div>
    
    
        <div class="col-sm-4">
            <div class="form-group">
                <label>Gender</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender"
                        id="male" value="male"
                        {{ $cust->gender == 'male' ? 'checked' : '' }}>
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender"
                        id="female" value="female"
                        {{ $cust->gender == 'female' ? 'checked' : '' }}>
                    <label class="form-check-label" for="female">Female</label>
                </div>
            </div>
        </div>
    
        <div class="col-sm-4">
            <div class="form-group">
                <label>DOB</label>
                <input type="date" class="form-control" name="dob"
                    value="{{ $cust->dob }}">
    
            </div>
        </div>
    
          {{-- <div class="col-sm-4">
            <div class="form-group">
                <label>District</label>
                <input required="" type="text" class="form-control"
                    value="{{ $cust->address->address_1 ?? ''}}" name="district" readonly>
                @error('district')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
         </div> --}}
    
        <div class="col-sm-4">
            <div class="form-group">
                <label>State</label>
                <input required="" type="text" class="form-control"
                    value= "{{ $cust->address->customerstate->state ?? ''}}" name="state" >
            </div>
        </div>
    
        <div class="col-sm-4">
            <div class="form-group">
                <label>Pin Code</label>
                <input required="" type="text" class="form-control"
                    value="{{ $cust->address->postal_code }}" name="postal_code" >
                @error('postal_code')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    
        <div class="col-sm-4">
            <div class="form-group">
                <label>District</label>
                <input required="" type="text" class="form-control"
                    value="{{ $cust->address->customerstate->district ?? ''}}" name="district">
                @error('district')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    
        <div class="col-sm-4">
            <div class="form-group">
                <label>Address 1</label>
                <input required="" type="text" class="form-control"
                    value="{{ $cust->address->address_1 }}" name="address_1" readonly>
                @error('address_1')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    
        <div class="col-sm-4">
            <div class="form-group">
                <label>Address 2</label>
                <input type="text" class="form-control"
                    value="{{ $cust->address->address_2 }}" name="address_2" readonly>
                @error('address_2')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-sm-12 text-center">
            <button type="submit" class="btn btn-primary add-list btn-sm text-white">Update
                Customer</button>
        </div>

    </div>
        </form>

  
</div>
        </div>
    </div>
    @endsection    