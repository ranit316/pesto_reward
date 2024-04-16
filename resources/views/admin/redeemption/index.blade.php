@extends('admin.layout.app')
@push('css')
@endpush
@push('js')
@endpush
@section('title', 'Redeemption')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">

                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                    <h4 class="card-title">Approval List</h4>
                                </div>
                            </div>



                            <div class="card-body">
                                <div class="table-responsive-lg">
                                    <table class=" datatable table   table-striped table-bordered " style="width: 100%">
                                        <thead>
                                            <tr class="ligth">
                                                <th class="text-center">
                                                    <input type="checkbox" value="1" id="check_all" />
                                                    <a href="#" onclick="allRedeemptionSend()">Approve All</a>
                                                </th>
                                                {{-- <th>sys_id</th> --}}
                                                <th>Coupon No </th>
                                                <th>Customer Name</th>
                                                <th>Request Date</th>
                                                <th>Approve Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <script type="text/javascript">
                                            $(function() {
                                                var table = $('.datatable').DataTable({


                                                    // processing: true,
                                                    // serverSide: true,
                                                    columnDefs: [{ // used if any blank value for any field
                                                        "defaultContent": "-", // to prevent error alert 
                                                        "targets": "_all"
                                                    }],
                                                    ajax: "{{ route('redeemption') }}",

                                                    buttons: [
                                                        {
                                                             extend: 'collection',
                                                             text:    'Export',
                                                             buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
                                                             className: 'custom-exp-btn',
                                                        },
                                                    ],

                                                    columns: [{
                                                            data: 'check',
                                                            name: 'check',
                                                            orderable: false,
                                                            searchable: false
                                                        },
                                                        // {
                                                        //     data: 'id',
                                                        //     name: 'id',
                                                        // },
                                                        {
                                                            data: 'coupon.coupon_code',
                                                            name: 'coupon.coupon_code',
                                                        },
                                                        {
                                                            data: 'customer.first_name',
                                                            name: 'customer.first_name',
                                                        },
                                                        {
                                                            data: 'request_date_time',
                                                            name: 'request_date_time',
                                                        },
                                                        {
                                                            defaultContent: 'null'
                                                        },
                                                        // {
                                                        //     data: 'coupon.couponRequest.company.brand_title',
                                                        //     name: 'coupon.couponRequest.company.brand_title',
                                                        //     render: function(data, type, full, meta) {
                                                        //         return data ? data : '-';
                                                        //     }
                                                        // },
                                                        {
                                                            data: 'amount',
                                                            name: 'amount',
                                                        },
                                                        {
                                                            data: 'status',
                                                            name: 'status',
                                                            "render": function(data, type, full, meta) {
                                                                if (data.toLowerCase() == 'pending') {
                                                                    return "<span class='badge bg-warning'>Pending</span>";
                                                                } 
                                                                if(data.toLowerCase() == 'approved') {
                                                                      return "<span class='badge bg-success'>Approved</span>";
                                                                }
                                                                else{
                                                                    return "<span class='badge bg-danger'>Rejected</span>";
                                                                } 
                                                            }
                                                        },
                                                        {
                                                            data: 'action',
                                                            name: 'action',
                                                        },
                                                        // Add more columns as needed
                                                    ],
                                                        colReorder: true,
                                                        dom: 'lBfrtip',
                                                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 100]],
                                                        select: true,
                                                });
                                            });

                                            /* custom script  */
                                            $("#check_all").click(function() {
                                                if ($(this).prop("checked")) {
                                                    $("input[type=checkbox]").prop("checked", true);
                                                }
                                                if (!$(this).prop("checked")) {
                                                    $("input[type=checkbox]").prop("checked", false);
                                                }
                                            });

                                            function allRedeemptionSend() {
                                                var redeemptionValue = [];
                                                var csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                                                $("input:checkbox[name=redeemption]:checked").each(function() {
                                                    redeemptionValue.push($(this).val());
                                                });
                                                if (redeemptionValue.length <= 0) {
                                                    alert("Please select atleast one checkbox");

                                                } else {
                                                    redeemptionValueStr = JSON.stringify(redeemptionValue);

                                                    $.ajax({
                                                        url: "{{ route('request.all_approved') }}",
                                                        type: 'POST',
                                                        dataType: 'json',
                                                        data: {
                                                            _token: csrfToken,
                                                            redeemptionReq: redeemptionValueStr

                                                        },
                                                        success: function(data) {
                                                            alert(data.message);
                                                            window.location.reload();
                                                        }
                                                    });
                                                }
                                            }
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


    {{-- modal on reject button --}}
    <div class="modal fade bs-example-modal-center show" tabindex="-1" aria-labelledby="rejectModalLabel" aria-modal="true" role="dialog" id="reject">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reason For Rejection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <label for="rejectionReason">Please provide a reason for rejection:</label>
                    <textarea class="form-control" id="rejectionReason" rows="4" name="reason"></textarea>
                    <div class="mt-3"> <!-- Add margin-top for spacing -->
                        <button type="button" class="btn btn-primary"  id="submitreject">Submit</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>


@endsection
