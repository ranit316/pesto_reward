@extends('admin.layout.app')
@push('css')
@endpush
@push('js')
@endpush
@section('title', 'Add Customer')
@section('content')

<div class="main-content">
    <div class="page-content">
        <script>
            @if (Session::has('failure'))
                    toastr.options = {
                        "closeButton": true,
                        "progressBar" : true,
                        "positionClass": "toast-top-center",
                        "showDuration": "300",
                    }
                    toastr.error("{{ session('failure') }}");
                @endif
            </script>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title text-capitalize">Create Ticket by Admin</h4>
                            </div>
                            <a href="{{route('admin.tic.list')}}">
                                <button type="submit" class="btn btn-primary add-list btn-md btn-rounded" ><i class="uil-arrow-left"></i>Back to Ticket List</button>
                            </a>
                        </div>

                        <div class="card-body">
                            <form  action="{{route('admin.tic.cre')}}" method="POST" >
                                @csrf
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                                    <label class="required">Customer Name</label>
                                                    <select id="customer_id" type="text" class="form-control selectpicker" data-live-search="true"
                                                        name="customer_id" required>
                                                        <option selected disabled>Select One</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}">{{ $customer->first_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('customer_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="role_id" class="required">Subject</label>
                                            <input type="text" name="subject" class="form-control" placeholder="Enter Customer Last Name" required>
                                            @error('subject')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                        </div>
                                    </div>


                                    
                                   

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="required">Type</label>
                                            <input type="text" class="form-control"
                                                placeholder="Enter Type" name="type" required>
                                                @error('type')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="required">Ticket No</label>
                                            <input type="text" class="form-control"
                                                 name="ticket_no" readonly value="{{$tickitno}}">
                                                
                                        </div>
                                    </div>


                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="">Status</label>
                                                    <select id="type" required type="text" class="form-control"
                                                    name="status">
                                                        <option>select one</option>
                                                        <option value="active" selected>Active</option>
                                                        <option value="inactive">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>

                                   

                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-success btn-rounded"><i class="uil uil-check me-2"></i>Add Ticket</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#state').change(function() {
            var selectedState = $(this).val();

            // Make an AJAX request to get the districts for the selected state
            $.get('{{ route('get.districts', '') }}/' + selectedState, function(data) {
                // Clear the current options
                $('#district').empty();

                // Add the new options based on the received data
                $.each(data, function(index, district) {
                    $('#district').append('<option value="' + district + '">' + district + '</option>');
                });
            });
        });
    });
</script> --}}