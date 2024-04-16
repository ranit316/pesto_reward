@extends('admin.layout.app')
@push('css')
@endpush
@push('js')
@endpush
@section('title', 'wallet list')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <script>
                @if (Session::has('success'))
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-center",
                        "showDuration": "300",
                    }
                    toastr.success("{{ session('success') }}");
                @endif

                @if (Session::has('update_success'))
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-center",
                        "showDuration": "300",
                    }
                    toastr.success("{{ session('update_success') }}");
                @endif
            </script>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">

                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                    <h4 class="card-title">All Wallet Transaction History</h4>
                                </div>


                                <!-- <a class="btn btn-primary add-list btn-sm text-white" href="{{ route('admin.catalog.add') }}"><i class="las la-plus mr-3"></i>Add Catalog</a> -->
                            </div>


                            <div class="card-body">
                                <div class="table-responsive-lg">
                                    <table class=" datatable table align-middle table-nowrap table-check" id="datat">
                                        <thead>
                                            <tr class="ligth">
                                                <th>sys_id</th>
                                                <th>Customer Name</th>
                                                <th>Customer Phone no</th>
                                                <th>Transaction Type</th>
                                                <th>Transaction Date</th>
                                                <th>Transaction Amount</th>
                                                <th>Action</th>
                                                <!-- <th class="text-center">Action</th> -->
                                            </tr>
                                        </thead>
                                        <script type="text/javascript">
                                            $(function() {

                                                var table = $('#datat').DataTable({
                                                    processing: true,
                                                    serverSide: true,
                                                    ajax: "{{ route('admin.all.trans') }}",
                                                    columns: [{
                                                            data: 'id',
                                                            name: 'id',
                                                        },
                                                        {
                                                            data: null,
                                                            render: function(data, type, full, meta) {
                                                                return full.wallet.customer.first_name + ' ' + full.wallet.customer
                                                                    .last_name;
                                                            }
                                                        },
                                                        {
                                                            data: 'wallet.customer.phone_number',
                                                            name: 'wallet.customer.phone_number'
                                                        },
                                                        {
                                                            data: 'transactiontype',
                                                            name: 'transactiontype',
                                                            "render": function(data, type, full, meta) {
                                                                if (data.toLowerCase() == 'cr') {
                                                                    return "<span class='badge bg-success'>Credited</span>";
                                                                } else {
                                                                    return "<span class='badge bg-danger'>Debited</span>";
                                                                }
                                                            }
                                                        },
                                                        {
                                                            data: 'date',
                                                            name: 'date'
                                                        },
                                                        {
                                                            data: 'amount',
                                                            name: 'amount',
                                                        },
                                                       {
                                                        defaultContent: 'null'
                                                       }
                                                      
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

{{-- 
<tbody>

    @foreach ($alltrans as $trans)
        <tr>
            <td>{{$trans->id}}</td>
            <td>{{$trans->transactiontype}} </td>
            <td>{{$trans->amount}}</td>
            <td>{{$trans->description}}</td>
            <td>{{$trans->date}}</td>

            <script>
                $(document).ready(function() {
                    $('.delete-btn').on('click', function(e) {
                        e.preventDefault();

                        // Show a custom confirmation dialog
                        var confirmation = window.confirm('Are you sure you want to deactivate this company?');

                        // If user confirms, navigate to the delete URL
                        if (confirmation) {
                            var url = $(this).attr('href');
                            window.location.href = url;
                        }
                    });
                });
            </script>

            
</tr>
@endforeach
</tbody> --}}
