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
                            <div class="col-md-6">
                            <form method="POST" action="{{ route('invoices.update', $invoice->id) }}">
    @csrf
    @method('PUT')

    <div class="invoice-details">
        <!-- رقم الفاتورة والتاريخ -->
        <div class="row">
            <div class="col">
                <label for="invoice_no" class="form-label">رقم الفاتورة</label>
                <input class="form-control" name="invoice_no" type="text" value="{{ $invoice->invoice_no }}" readonly>
            </div>
            <div class="col">
                <label for="date" class="form-label">التاريخ</label>
                <input class="form-control" name="date" type="date" value="{{ $invoice->date }}">
            </div>
        </div>

        <!-- تفاصيل الفاتورة (المنتج، الكمية، السعر) -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>الكمية</th>
                    <th>السعر</th>
                    <th>إجمالي</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->invoice_details as $item)
                    <tr>
                        <!-- قائمة المنتجات -->
                        <td>
                            <select name="product_id[]" class="form-select">
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <!-- الكمية -->
                        <td><input type="number" name="quantity[]" class="form-control" value="{{ $item->selling_qty }}"></td>

                        <!-- السعر -->
                        <td><input type="number" name="unit_price[]" class="form-control" value="{{ $item->unit_price }}"></td>

                        <!-- إجمالي السعر (قيمة قراءة فقط) -->
                        <td><input type="number" class="form-control" value="{{ $item->selling_price }}" readonly></td>

                        <!-- حذف السطر -->
                        <td><button type="button" class="btn btn-danger remove-row">X</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- زر التحديث -->
        <button type="submit" class="btn btn-success">تحديث الفاتورة</button>
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
        $('.select2').select2();
    });

    $(document).on("click", ".remove-row", function() {
        $(this).closest("tr").remove();
    });

    $(document).ready(function() {
    // عند تحميل الصفحة، تحديث إجمالي كل منتج والإجمالي الكلي
    updateTotals();

    // تحديث الإجمالي عند تغيير الكمية أو السعر
    $(document).on('keyup change', '.unit_price, .selling_qty', function() {
        var row = $(this).closest("tr");
        var qty = parseFloat(row.find(".selling_qty").val()) || 0;
        var price = parseFloat(row.find(".unit_price").val()) || 0;
        var total = qty * price;
        row.find(".selling_price").val(total.toFixed(2));

        updateTotals();
    });

    // تحديث الإجمالي عند إدخال الخصم
    $(document).on('keyup', '#discount_amount', function() {
        updateTotals();
    });

    // دالة لحساب الإجمالي الكلي
    function updateTotals() {
        var sum = 0;
        $(".selling_price").each(function() {
            var value = parseFloat($(this).val()) || 0;
            sum += value;
        });

        var discount = parseFloat($('#discount_amount').val()) || 0;
        sum -= discount;

        $('#estimated_amount').text(sum.toFixed(2));
        $('#estimated_amount_input').val(sum.toFixed(2));
    }

    // عند الضغط على زر التحديث
    $("#updateInvoiceButton").click(function(e) {
        e.preventDefault(); // منع تحديث الصفحة

        // جمع البيانات من الجدول
        var invoiceData = [];
        $("#invoiceTable tbody tr").each(function() {
            var row = $(this);
            var productId = row.find(".product_id").val();
            var quantity = row.find(".selling_qty").val();
            var unitPrice = row.find(".unit_price").val();
            var total = row.find(".selling_price").val();

            invoiceData.push({
                product_id: productId,
                quantity: quantity,
                unit_price: unitPrice,
                total: total
            });
        });

        var invoiceDetails = {
            invoice_no: $("#invoice_no").val(),
            date: $("#date").val(),
            customer_id: $("#customer_id").val(),
            discount: $("#discount_amount").val(),
            total_amount: $("#estimated_amount_input").val(),
            products: invoiceData
        };

        // إرسال الطلب إلى السيرفر
        $.ajax({
            url: "{{ route('invoices.update', $invoice->id) }}", // استبدل بـ المسار الصحيح
            method: "PUT",
            data: invoiceDetails,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert("تم تحديث الفاتورة بنجاح!");
                location.reload();
            },
            error: function(error) {
                console.log(error);
                alert("حدث خطأ أثناء التحديث.");
            }
        });
    });
});

</script>
@endsection
