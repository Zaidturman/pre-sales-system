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

                            <div class="row mb-4 mt-3">
                                <div class="col-12 text-center">
                                    <h2>{{ $customer->name }}</h2>
                                    <p class="text-muted">الرصيد الحالي: {{ number_format($customer->balance, 2) }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="amount" class="col-sm-2 col-form-label">المبلغ الإجمالي</label>
                                <div class="col-sm-10">
                                    <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="discount_amount" class="col-sm-2 col-form-label">قيمة الخصم</label>
                                <div class="col-sm-10">
                                    <input type="number" name="discount_amount" id="discount_amount" class="form-control" step="0.01" value="0">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="net_amount" class="col-sm-2 col-form-label">المبلغ الصافي</label>
                                <div class="col-sm-10">
                                    <input type="number" id="net_amount" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="payment_method" class="col-sm-2 col-form-label">طريقة الدفع</label>
                                <div class="col-sm-10">
                                   <select name="payment_method" id="payment_method" class="form-control" required>
    <option value="">اختر طريقة الدفع</option>
    <option value="cash_shekel">نقدي شيكل</option>
    <option value="cash_dinar">نقدي دينار</option>
    <option value="check">شيك</option>
</select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="payment_date" class="col-sm-2 col-form-label">تاريخ الدفع</label>
                                <div class="col-sm-10">
                                    <input type="date" name="payment_date" id="payment_date" class="form-control" required value="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">حفظ الدفعة</button>
                                    <a href="{{ route('customer.all') }}" class="btn btn-secondary">إلغاء</a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // حساب المبلغ الصافي تلقائياً
        function calculateNetAmount() {
            let amount = parseFloat($('#amount').val()) || 0;
            let discount = parseFloat($('#discount_amount').val()) || 0;
            let netAmount = amount - discount;
            $('#net_amount').val(netAmount.toFixed(2));
        }

        $('#amount, #discount_amount').on('input', calculateNetAmount);
        
        // تعيين تاريخ اليوم افتراضياً
        $('#payment_date').val(new Date().toISOString().split('T')[0]);
    });
</script>

@endsection