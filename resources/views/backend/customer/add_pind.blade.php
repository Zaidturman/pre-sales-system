@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">إضافة دفعة</h4><br>

                        <form action="{{ route('partialpayments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="customer_id" value="{{ $customer->id }}">


                            <div class="row mb-4 mt-3 ">
                                <div class="form-group col-sm-10 d-flex justify-content-center">

                                    <h2>{{ $customer->name }}</h2>
                                </div>

                            </div>
                            <!-- end row -->

                            <div class="row mb-3 mt-3">
                                <label for="amount" class="col-sm-2 col-form-label">المبلغ</label>
                                <div class="form-group col-sm-10">
                                    <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
                                </div>
                            </div>
                            <!-- end row -->

                            <div class="row mb-3 mt-3">
                                <label for="payment_date" class="col-sm-2 col-form-label">تاريخ الدفع</label>
                                <div class="form-group col-sm-10">
                                    <input type="date" name="payment_date" id="payment_date" class="form-control" required>
                                </div>
                            </div>
                            <!-- end row -->

                            <div class="row mb-3 mt-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">إضافة دفعة</button>
                                </div>
                            </div>
                            <!-- end row -->

                        </form>

                    </div>
                </div>
            </div> <!-- end col -->
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // يمكنك إضافة أي جافا سكريبت هنا إذا لزم الأمر
    });
</script>

@endsection