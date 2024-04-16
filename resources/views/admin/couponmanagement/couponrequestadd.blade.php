@extends('admin.layout.app')
@push('css')
@endpush
@push('js')
@endpush
@section('title', 'Add Coupon Request')
@section('content')


    <div class="main-content">
        <div class="page-content">
            <script>
                @if (Session::has('failure'))
                    toastr.options = {
                        "closeButton": true,
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
                                    <h4 class="card-title text-capitalize">Generate Coupon</h4>
                                </div>
                                    <a href="{{route('admin.coupon.request.list')}}">
                                        <button type="submit" class="btn btn-primary add-list btn-md btn-rounded" ><i class="uil-arrow-left"></i>Back to Coupon Request List</button>
                                    </a>
                            </div>

                            <div class="card-body">
                                <form id="form_data" action="{{ route('admin.coupon.store') }}" method="POST">
                                    @csrf
                                   <div class="row">
                                         {{-- <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="type" class="required">No of Coupons</label>
                                                <input type="number" class="form-control" name="no_of_coupons"
                                                    placeholder="Enter no of coupoms">
                                                @error('no_of_coupons')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="required">No of Coupons</label>
                                                <input type="number" class="form-control" name="no_of_coupons" placeholder="No Of Coupons" max="1000">
                                                    {{-- <option value="">Select One</option> --}}
                                                    {{-- @for ($i=10; $i<=1000; $i+=10)
                                                        <option value="{{$i}}">{{$i}} </option>
                                                    @endfor --}}
                                                
                                                @error('no_of_coupons')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="required">Amount</label>
                                                <select id="" class="form-control selectpicker" data-live-search="true" name="amount">
                                                    <option value="">Select Amount</option>
                                                    <option value="1">1</option>
                                                    @for ($i = 0; $i <= 500; $i += 10)
                                                    @if ( $i > 0)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                        @endif
                                                    @endfor
                                                </select>
                                                @error('amount')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        

                                        {{-- <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="amount" class="required">Amount</label>
                                                <input type="number" class="form-control" name="amount"
                                                    placeholder="Enter amount">
                                                @error('amount')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>  --}}

                                        <div class="col-sm-4">
                                            <div class="form-group" >
                                                <label class="required">Brand</label>
                                                <select id="company_id" type="text" class="form-control selectpicker" data-live-search="true"
                                                    name="company_id" required>
                                                    <option selected disabled>Select Brand</option>
                                                    @foreach ($companies as $company)
                                                        <option value="{{ $company->id }}">{{ $company->company_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('company_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label  class="required">Product</label>
                                                <select id="product_id"  type="text" class="form-control selectpicker" data-live-search="true"
                                                    name="product_id" required>
                                                    <option value="">Select Product</option>
                                                    {{-- @foreach ($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->product_name }}
                                                        </option>
                                                    @endforeach --}}
                                                </select>
                                                @error('product_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="optional">Category</label>
                                                <select id="category_id"  type="text" class="form-control selectpicker" data-live-search="true"
                                                    name="category_id">
                                                    <option value="">Select One</option>
                                                    @foreach ($category as $cat)
                                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label  class="required">Coupon Expaire Date</label>
                                                <input  type="date" class="form-control"
                                                    placeholder="Enter description" name="expiry_date"  value="{{ old('expiry_date') != '' ? old('expiry_date') : now()->addYears(2)->format('Y-m-d') }}">
                                                @error('expiry_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label class="optional">Description</label>
                                                <textarea  type="text" class="form-control"
                                                    placeholder="Enter description" name="description"></textarea>
                                                @error('description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12  mt-4 mb-4 text-center">
                                        <button type="submit" class="btn btn-success btn-rounded"><i class="uil uil-check me-2"></i>Add
                                            Coupon</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker();
        });


        $(document).ready(function() {
            $('#company_id').change(function(e) {
                e.preventDefault();
                var company_id = $(this).val();
                //console.log(company_id);
                $('#product_id').html('<option value="">Select product</option>');
                if (company_id) {
                    $.ajax({
                        type: "GET",
                        url: '{{ route("select.product", ":company_id") }}'.replace(':company_id', company_id),
                        success: function (data) {
                            $.each(data, function (key, value) {
                            $('#product_id').append('<option value="' + value.id + '">' + value.product_name + '</option>');
                        });
                        $('.selectpicker').selectpicker('refresh')
                        }
                    });
                }
            });
        });



    </script>
@endsection
