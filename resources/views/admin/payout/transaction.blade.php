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
                                <h4 class="card-title">Transaction Details</h4>
                            {{-- </div>
                            <a class="btn btn-primary add-list btn-sm text-white" href="{{route('admin.setting.showpages')}}"><i
                                    class="las la-plus mr-3"></i>Add Pages</a>
                        </div> --}}


                        <div class="card-body">
                            <div class="table-responsive-lg">
                                <table class=" datatable table align-middle table-nowrap table-check" id="datatab">
                                    <tbody>
                                    <thead>
                                        <tr class="ligth">
                                            <th>Sys id</th>
                                            <th>Customer Name</th>
                                            <th>Customer Phone No:</th>
                                            <th>Payout Request No</th>
                                            <th>Bank Refference_no</th>
                                            <th>Amount</th>
                                            <th>Transaction No</th>
                                            <th>Status</th>
                                            <th>message</th>
                                            {{-- <th>Action</th> --}}
                                             </td> 
                                        </tr>
                                    </thead>
                                    <script type="text/javascript">
                                        $(function() {
                                            var table = $('#datatab').DataTable({
                                                processing: true,
                                                serverSide: true,
                                                ajax: "{{ route('admin.trans') }}",

                                                columns: [{
                                                        data: 'id',
                                                        name: 'id',
                                                    },
                                                    {
                                                        data: 'payout.customer.first_name',
                                                        name: 'payout.customer.first_name',
                                                    },
                                                    {
                                                        data: 'payout.customer.phone_number',
                                                        name: 'payout.customer.phone_number',
                                                    },
                                                    {
                                                        data: 'ref_no',
                                                        name: 'ref_no',
                                                    },
                                                    {
                                                        data: 'bank_ref',
                                                        name: 'bank_ref',
                                                    },
                                                    {
                                                        data: 'amount',
                                                        name: 'amount'
                                                    },
                                                    {
                                                        data: 'bankrrn',
                                                        name: 'bankrrn'
                                                    },
                                                    {
                                                        data: 'status',
                                                        name: 'status'
                                                    },
                                                    {
                                                        data: 'message',
                                                        name: 'message'
                                                    },
                                                    // {
                                                    //         data: 'action',
                                                    //         name: 'action',
                                                    //         orderable: false,
                                                    //         searchable: false,
                                                    //  },
                                                ]
                                            });
                                        });
                                    </script>
                                    
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

{{-- <tbody>
    @foreach($transaction as $trans)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$trans->ref_no}}</td>
                <td>{{$trans->bank_ref}}</td>
                <td>{{$trans->amount}}</td>
                <td>{{$trans->status}}</td>
                <td>{{$trans->transaction_no}}</td>
                <td>{{$trans->message}}</td>
                
                          
                 </td> 
            </tr>
            @endforeach
    </tbody> --}}