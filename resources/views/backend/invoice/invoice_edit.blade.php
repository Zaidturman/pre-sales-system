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
                        <h4 class="card-title">تعديل الفاتورة</h4><br><br>
                        
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <form method="POST" action="{{ route('invoices.update', $invoice->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="invoice-details">
                                        <!-- معلومات الفاتورة الأساسية -->
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="invoice_no" class="form-label">رقم الفاتورة</label>
                                                <input class="form-control" name="invoice_no" type="text" value="{{ $invoice->invoice_no }}" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="date" class="form-label">التاريخ</label>
                                                <input class="form-control" name="date" type="date" value="{{ $invoice->date }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="payment_method" class="form-label">طريقة الدفع</label>
                                                <select name="payment_method" id="payment_method" class="form-select">
                                                    <option value="نقدي شيكل" {{ $invoice->payment_method == 'نقدي شيكل' ? 'selected' : '' }}>نقدي شيكل</option>
                                                    <option value="نقدي دينار" {{ $invoice->payment_method == 'نقدي دينار' ? 'selected' : '' }}>نقدي دينار</option>
                                                    <option value="شيك" {{ $invoice->payment_method == 'شيك' ? 'selected' : '' }}>شيك</option>
                                                    <option value="حوالة بنكية" {{ $invoice->payment_method == 'حوالة بنكية' ? 'selected' : '' }}>حوالة بنكية</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="discount_amount" class="form-label">الخصم</label>
                                                <input type="number" name="discount_amount" id="discount_amount" 
                                                    class="form-control" value="{{ $invoice->discount_amount }}" min="0" step="0.01">
                                            </div>
                                        </div>

                                        <!-- تفاصيل الفاتورة -->
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>المنتج</th>
                                                    <th>الكمية</th>
                                                    <th>السعر</th>
                                                    <th>الإجمالي</th>
                                                    <th>حذف</th>
                                                </tr>
                                            </thead>
                                            <tbody id="invoiceItems">
                                              @foreach($invoice->invoice_details as $item)
    <tr>
        <input type="hidden" name="existing_details[]" value="{{ $item->id }}">
        <td>
            <select name="product_id[]" class="form-select product-select">
                                                                @foreach($products as $product)
                                                                    <option value="{{ $product->id }}" 
                                                                        data-price="{{ $product->selling_price }}"
                                                                        {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                                                        {{ $product->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="selling_qty[]" class="form-control selling_qty" 
                                                                value="{{ $item->selling_qty }}" min="1">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="unit_price[]" class="form-control unit_price" 
                                                                value="{{ $item->unit_price }}" min="0" step="0.01">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control selling_price" 
                                                                value="{{ $item->selling_price }}" readonly>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger remove-row">X</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <!-- ملخص الفاتورة -->
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-primary" id="addRow">إضافة منتج</button>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <h4>الإجمالي: <span id="estimated_amount">{{ $invoice->payment->total_amount + $invoice->discount_amount }}</span> ₪</h4>
                                                <input type="hidden" name="estimated_amount" id="estimated_amount_input" 
                                                    value="{{ $invoice->payment->total_amount + $invoice->discount_amount }}">
                                            </div>
                                        </div>

                                        <!-- معلومات الزبون -->
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <label for="customer_id" class="form-label">الزبون</label>
                                                <select name="customer_id" id="customer_id" class="form-select select2">
                                                    @foreach($customers as $cust)
                                                        <option value="{{ $cust->id }}" 
                                                            {{ $cust->id == $invoice->payment->customer_id ? 'selected' : '' }}>
                                                            {{ $cust->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="paid_status" class="form-label">حالة الدفع</label>
                                                <select name="paid_status" id="paid_status" class="form-select">
                                                    <option value="full_paid" {{ $invoice->payment->paid_status == 'full_paid' ? 'selected' : '' }}>مدفوع بالكامل</option>
                                                    <option value="partial_paid" {{ $invoice->payment->paid_status == 'partial_paid' ? 'selected' : '' }}>دفع جزئي</option>
                                                    <option value="full_due" {{ $invoice->payment->paid_status == 'full_due' ? 'selected' : '' }}>دين كامل</option>
                                                </select>
                                                <input type="number" name="paid_amount" class="form-control paid_amount mt-2" 
                                                    value="{{ $invoice->payment->paid_amount }}" 
                                                    placeholder="المبلغ المدفوع" 
                                                    style="{{ $invoice->payment->paid_status != 'partial_paid' ? 'display:none;' : '' }}">
                                            </div>
                                        </div>

                                        <!-- زر التحديث -->
                                        <div class="row mt-4">
                                            <div class="col-md-12 text-end">
                                                <button type="submit" class="btn btn-success">تحديث الفاتورة</button>
                                                <a href="{{ route('invoice.all') }}" class="btn btn-secondary">إلغاء</a>
                                            </div>
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
</div>

<script>
$(document).ready(function() {
    // تهيئة Select2
    $('.select2').select2();

    // إضافة سطر جديد
    $('#addRow').click(function() {
        var newRow = `
            <tr>
                <td>
                    <select name="product_id[]" class="form-select product-select">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="selling_qty[]" class="form-control selling_qty" value="1" min="1"></td>
                <td><input type="number" name="unit_price[]" class="form-control unit_price" value="0" min="0" step="0.01"></td>
                <td><input type="number" class="form-control selling_price" value="0" readonly></td>
                <td><button type="button" class="btn btn-danger remove-row">X</button></td>
            </tr>
        `;
        $('#invoiceItems').append(newRow);
    });

    // حذف سطر
    $(document).on("click", ".remove-row", function() {
        $(this).closest("tr").remove();
        calculateTotal();
    });

    // تحديث السعر عند تغيير المنتج
    $(document).on('change', '.product-select', function() {
        var selectedOption = $(this).find('option:selected');
        var price = selectedOption.data('price');
        $(this).closest('tr').find('.unit_price').val(price).trigger('change');
    });

    // حساب الإجمالي عند تغيير الكمية أو السعر
    $(document).on('keyup change', '.unit_price, .selling_qty', function() {
        var row = $(this).closest("tr");
        var qty = parseFloat(row.find(".selling_qty").val()) || 0;
        var price = parseFloat(row.find(".unit_price").val()) || 0;
        var total = qty * price;
        row.find(".selling_price").val(total.toFixed(2));
        calculateTotal();
    });

    // إظهار/إخفاء حقل المبلغ المدفوع حسب حالة الدفع
    $(document).on('change', '#paid_status', function() {
        if ($(this).val() == 'partial_paid') {
            $('.paid_amount').show();
        } else {
            $('.paid_amount').hide();
        }
    });

    // حساب الإجمالي الكلي مع الخصم
   function calculateTotal() {
    var sum = 0;
    $(".selling_price").each(function() {
        var value = parseFloat($(this).val()) || 0;
        sum += value;
    });

    var discount = parseFloat($('#discount_amount').val()) || 0;
    var total = sum - discount;

    $('#estimated_amount').text(total.toFixed(2));
    $('#estimated_amount_input').val(total.toFixed(2));
}

// استدعاء الدالة عند تغيير أي قيمة
$(document).on('keyup change', '.unit_price, .selling_qty, #discount_amount', calculateTotal);
    // حساب الإجمالي عند تحميل الصفحة
    calculateTotal();

    // حساب الإجمالي عند تغيير الخصم
    $('#discount_amount').on('input', calculateTotal);
});
</script>

<style>
    .product-select, .customer-select {
        width: 100%;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    #estimated_amount {
        font-weight: bold;
        color: #0d6efd;
    }
</style>

@endsection