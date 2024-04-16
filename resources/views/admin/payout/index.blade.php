@extends('admin.layout.app')
@push('css')
@endpush
@push('js')
@endpush
@section('title', 'pages')
@section('content')



<div class="main-content">
    <div class="page-content">
       
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Payout Details</h4>
                            {{-- </div>
                            <a class="btn btn-primary add-list btn-sm text-white" href="{{route('admin.setting.showpages')}}"><i
                                    class="las la-plus mr-3"></i>Add Pages</a>
                        </div> --}}


                        <div class="card-body">
                            <div class="table-responsive-lg">
                                <table class=" datatable table   table-striped table-bordered ">
                                    <tbody>
                                    <thead>
                                        <tr class="ligth">
                                            <th>sys id</th>
                                            <th>Customer Name</th>
                                            <th>Refference No</th>
                                            <th>Amount</th>
                                            <th>payment Type</th>
                                            <th>UPI ID</th>
                                            <th>Account No</th>
                                            <th>IFSC</th>
                                            <th>Phone</th>
                                         
                                             </td> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payout as $pay)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$pay->customer_name}}</td>
                                                <td>{{$pay->ref_no}}</td>
                                                <td>{{$pay->amount}}</td>
                                                <td>{{$pay->payment_type}}</td>
                                                <td>{{$pay->upi_id}}</td>
                                                <td>{{$pay->bank_ac}}</td>
                                                <td>{{$pay->ifsc}}</td>
                                                <td>{{$pay->phone}}</td>
                                               
                                                   
                                                 {{-- {{-- <td class="text-center"> --}}
                                                    <!-- Add action buttons here -->
                                                    {{-- <a href="{{route('admin.setting.edit',[$showdata->id])}}" class="btn btn-primary btn-sm"><i
                                                            class="fas fa-edit"></i> Edit</a>
                                                    <a href=""
                                                        class="btn btn-danger btn-sm delete-btn" id="deleteRoleBtn"><i
                                                            class="fas fa-ban"></i> Delete</a>  --}}
                                                          
                                                 </td> 
                                            </tr>
                                            @endforeach
                                    </tbody>
                                    <form  action="{{route('admin.button')}}" method="POST">
                                        @csrf
                                        <div class="row">
                                    <div class="form-group col-sm-6 text-right">
                                        <label class="">Status</label>
                                        <select class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" name="status">
                                            {{-- <option>Status</option> --}}
                                            <option value="approved">Success</option>
                                            <option value="pending">pending</option>
                                            <option value="reject">reject</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 text-end">
                                        <button type="submit" class="btn btn-primary add-list btn-sm text-white">Update
                                            Status</button>
                                    </div>
                                        </div>
                                    </form>
                                </table>
                            </div>
                        </div>
                        <!-- end card body -->
                        
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->



        </div> <!-- container-fluid -->
    </div>
</div>

@endsection