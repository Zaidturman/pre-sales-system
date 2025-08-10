@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">المرحلة 1: اختيار الزبون</h4><br><br>

                        <form method="post" action="{{ route('invoice.processStep1') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>بحث عن زبون</label>
                                        <select   name="customer_id" id="customer_id" class="form-select select2" required>
                                            <option value="">اختر الزبون</option>
                                            @foreach($customers as $cust)
                                            <option value="{{ $cust->id }}">{{ $cust->name }} - {{ $cust->mobile_no }}</option>
                                            @endforeach
                                            <option value="0">زبون جديد</option>
                                        </select>
                                    
                                    </div>
                                </div>
                            </div>

                            <!-- Hide Add Customer Form -->
                            <div class="row new_customer mt-3" style="display:none">
                                <div class="form-group col-md-4">
                                    <input type="text" name="name" class="form-control" placeholder="اسم الزبون" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="text" name="mobile_no" class="form-control" placeholder="رقم الهاتف">
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="email" name="email" class="form-control" placeholder="البريد الإلكتروني">
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary">التالي <i class="fas fa-arrow-left"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize Select2 with search
    $('#customer_id').select2({
        placeholder: "ابحث عن زبون",
        allowClear: true
    });

    // Show/hide new customer form
    $('#customer_id').change(function() {
        if($(this).val() == '0') {
            $('.new_customer').show();
        } else {
            $('.new_customer').hide();
        }
    });
});
</script>

<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 5px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>
@endsection