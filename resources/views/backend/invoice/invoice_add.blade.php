@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- CSS for Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery (إذا لم تكن مضمنة بالفعل) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<!-- JS for Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">اضافة فاتورة</h4><br><br>

                        <!-- الفئات -->
                        <div class="row">
                            <div class="col-md-7">
                                <h2>الفئات</h2>
                                <div class="row">
                                    @foreach($category as $cat)
                                    <div class="col-md-3 mb-4">
                                        <div class="card cat-card" style="cursor: pointer;" onclick="showCategoryProducts({{$cat->id}})">
                                            <div class="card-body text-center">
                                                <h5 class="card-title">{{ $cat->name }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- المنتجات المتاحة -->
                        <div class="row">
                            <h2>المنتجات</h2>
                            <div class="col-md-6">
                                <div class="row" id="products-list">
                                    @foreach($products as $product)
                                    <div class="col-md-3 mb-4 product-item" data-category-id="{{ $product->category_id }}">
                                        <div class="card product-card" style="cursor: pointer;" onclick="addToInvoice({{ $product->id }}, '{{ $product->name }}', '{{ $product->category_id }}')">
                                            <div class="card-body text-center">
                                                <h5 class="card-title">{{ $product->name }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- قسم الفاتورة -->
                            <div class="col-md-6">
                                <form method="post" action="{{ route('invoice.store') }}">
                                    @csrf
                                    <div class="invoice-details">
                                        <h5>فاتورة</h5>
                                        <!-- رقم الفاتورة والتاريخ -->
                                        <div class="row">
                                            <div class="col">
                                                <div class="md-3">
                                                    <label for="invoice_no" class="form-label">رقم الفاتورة</label>
                                                    <input class="form-control" name="invoice_no" type="text" value="{{ $invoice_no }}" id="invoice_no" readonly style="background-color:#ddd">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="md-3">
                                                    <label for="date" class="form-label">التاريخ</label>
                                                    <input class="form-control" value="{{ $date }}" name="date" type="date" id="date">
                                                </div>
                                            </div>
                                        </div>
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
                                            <tbody id="addRow">
                                                <!-- المنتجات المضافة للفاتورة ستظهر هنا -->
                                            </tbody>
                                        </table>

                                        <div class="">
                                            <div class="form-group">
                                                <label>الخصم</label>
                                                <input type="text" name="discount_amount" id="discount_amount" class="form-control" placeholder="مبلغ الخصم">
                                            </div>
                                        </div>

                                        <div class="">
                                            <div class="invoice-summary p-3 bg-light border border-primary rounded">
                                                <h4 class="text-primary mb-3">ملخص الفاتورة</h4>
                                                <h3 class="d-flex justify-content-between align-items-center">
                                                    <span>الإجمالي:</span>
                                                    <span name="estimated_amount" id="estimated_amount" class="badge bg-primary p-2 fs-4 ">0</span>
                                                    <span class="fs-5">ILS</span>
                                                </h3>
                                            </div>
                                        </div>


                                        <br>
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label>حالة الدفع</label>
                                                <select name="paid_status" id="paid_status" class="form-select">
                                                    <option value="">اختر الحالة</option>
                                                    <option value="full_paid">مدفوع كامل</option>
                                                    <option value="full_due">دين كامل</option>
                                                    <option value="partial_paid">دفع جزئي</option>
                                                </select>
                                                <input type="text" name="paid_amount" class="form-control paid_amount" placeholder="اكتب المبلغ المدفوع" style="display:none;">
                                            </div>

                                            <div class="form-group col-md-9">
                                                <label>اسم الزبون</label>
                                                <select name="customer_id" id="customer_id" class="form-select select2">
                                                    <option value="">اختر الزبون</option>
                                                    @foreach($customer as $cust)
                                                    <option value="{{ $cust->id }}">{{ $cust->name }} </option>
                                                    @endforeach
                                                    <option value="0">زبون جديد</option>
                                                </select>
                                            </div>
<div class="row mb-3">
    <div class="col-md-6">
        <label for="payment_method">طريقة الدفع</label>
        <select name="payment_method" id="payment_method" class="form-select" required>
            <option value="">اختر طريقة الدفع</option>
            <option value="cash_shekel">نقدي شيكل</option>
            <option value="cash_dinar">نقدي دينار</option>
            <option value="check">شيك</option>
            <option value="bank_transfer">حوالة بنكية</option>
        </select>
    </div>
  
</div>
                                        </div>

                                        <!-- Hide Add Customer Form -->
                                        <div class="row new_customer" style="display:none">
                                            <div class="form-group col-md-4">
                                                <input type="text" name="name" id="name" class="form-control" placeholder="اكتب اسم الزبون">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <input type="text" name="mobile_no" id="mobile_no" class="form-control" placeholder="رقم هاتف الزبون">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <input type="email" name="email" id="email" class="form-control" placeholder="ايميل الزبون">
                                            </div>
                                        </div>

                                        <br>
                                        <input type="hidden" name="estimated_amount" id="estimated_amount_input" value="0">
                                        <div class="text-end">

                                            <button type="submit" class="btn btn-primary" id="storeButton">دفع</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> <!-- End card-body -->
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        // Initialize Select2 on the specified select element
        $('#customer_id').select2({
            placeholder: "اختر الزبون", // Placeholder text
            allowClear: true // Allow the option to clear the selection
        });
    });
</script>

<script id="document-template" type="text/x-handlebars-template">
    <tr class="delete_add_more_item" id="delete_add_more_item">
        <input type="hidden" name="date" value="@{{date}}">
        <input type="hidden" name="invoice_no" value="@{{invoice_no}}">
            <input type="hidden" name="category_id[]" value="@{{category_id}}">
        <td>
            <input type="hidden" name="product_id[]" value="@{{product_id}}">
            @{{ product_name }}
        </td>
        <td>
            <input type="number" min="1" step="0.01" class="form-control selling_qty text-right" name="selling_qty[]" value="1" required>
        </td>
        <td>
            <input type="number" step="0.01" class="form-control unit_price text-right" name="unit_price[]" value="" required>
        </td>
        <td>
            <input type="number" step="0.01" class="form-control selling_price text-right" name="selling_price[]" value="0" readonly>
        </td>
        <td>
            <i class="btn btn-danger btn-sm fas fa-window-close removeeventmore"></i>
        </td>
    </tr>
</script>
<script>
$(document).ready(function() {
    // حساب الإجمالي مع الخصم
    function calculateTotalWithDiscount() {
        let total = 0;
        $(".selling_price").each(function() {
            let value = parseFloat($(this).val()) || 0;
            total += value;
        });
        
        let discount = parseFloat($("#discount_amount").val()) || 0;
        let netTotal = total - discount;
        
        $("#estimated_amount").text(netTotal.toFixed(2));
        $("#estimated_amount_input").val(netTotal.toFixed(2));
    }

    // تحديث عند تغيير الكمية أو السعر
    $(document).on('keyup change', '.unit_price, .selling_qty', function() {
        let row = $(this).closest("tr");
        let qty = parseFloat(row.find(".selling_qty").val()) || 0;
        let price = parseFloat(row.find(".unit_price").val()) || 0;
        row.find(".selling_price").val((qty * price).toFixed(2));
        calculateTotalWithDiscount();
    });

    // تحديث عند تغيير الخصم
    $("#discount_amount").on('input', calculateTotalWithDiscount);
});
</script>
<script>
    var selectedCategoryName = '';

    function showCategoryProducts(categoryId) {
        var products = document.querySelectorAll('.product-item');
        products.forEach(function(product) {
            if (product.getAttribute('data-category-id') == categoryId) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });

        // Store the selected category name
        selectedCategoryName = document.querySelector('.cat-card[onclick*="' + categoryId + '"]').getAttribute('data-category-name');
    }

    // Initially, show products for the first category (if needed)
    document.addEventListener('DOMContentLoaded', function() {
        var firstCategory = document.querySelector('.cat-card');
        if (firstCategory) {
            firstCategory.click();
        }
    });

    function addToInvoice(productId, productName, categoryId) {
        var date = $('#date').val();
        var invoice_no = $('#invoice_no').val();

        if (date == '') {
            $.notify("Date is Required", {
                globalPosition: 'top right',
                className: 'error'
            });
            return false;
        }

        var productExists = false;

        // Check if the product already exists in the invoice
        $('#addRow tr').each(function() {
            var existingProductId = $(this).find('input[name="product_id[]"]').val();
            if (existingProductId == productId) {
                productExists = true;

                // Increase the quantity if the product is already in the invoice
                var qtyField = $(this).find('input[name="selling_qty[]"]');
                var currentQty = parseInt(qtyField.val());
                qtyField.val(currentQty + 1);

                // Update the total for this product
                var unitPriceField = $(this).find('input[name="unit_price[]"]');
                var sellingPriceField = $(this).find('input[name="selling_price[]"]');
                var unitPrice = parseFloat(unitPriceField.val());
                sellingPriceField.val((currentQty + 1) * unitPrice);

                // Recalculate the total invoice amount
                calculateTotal();
            }
        });

        // If the product doesn't exist, add it as a new item in the invoice
        if (!productExists) {
            var source = $("#document-template").html();
            var template = Handlebars.compile(source);
            var data = {
                date: date,
                invoice_no: invoice_no,
                category_id: categoryId,
                category_name: selectedCategoryName, // Use the stored category name
                product_id: productId,
                product_name: productName,
                product_qty: 1, // Start with a quantity of 1
                product_price: 0 // Assuming you handle price elsewhere, set a default value
            };
            var html = template(data);
            $("#addRow").append(html);
            calculateTotal();
        }
    }


    $(document).on("click", ".removeeventmore", function(event) {
        $(this).closest(".delete_add_more_item").remove();
        calculateTotal();
    });

    $(document).on('keyup change', '.unit_price, .selling_qty', function() {
        var unit_price = $(this).closest("tr").find("input.unit_price").val();
        var qty = $(this).closest("tr").find("input.selling_qty").val();
        var total = unit_price * qty;
        $(this).closest("tr").find("input.selling_price").val(total);
        calculateTotal();
    });

    $(document).on('keyup', '#discount_amount', function() {
        calculateTotal();
    });

    function calculateTotal() {
        var sum = 0;
        $(".selling_price").each(function() {
            var value = $(this).val();
            if (!isNaN(value) && value.length != 0) {
                sum += parseFloat(value);
            }
        });

        var discount_amount = parseFloat($('#discount_amount').val()) || 0;
        sum -= discount_amount;

        $('#estimated_amount').text(sum.toFixed(2));
        $('#estimated_amount_input').val(sum.toFixed(2)); // Update hidden input

    }

    // Paid amount For Partial Pay
    $(document).on('change', '#paid_status', function() {
        var paid_status = $(this).val();
        if (paid_status == 'partial_paid') {
            $('.paid_amount').show();
        } else {
            $('.paid_amount').hide();
        }
    });

    // New Customer View js
    $(document).on('change', '#customer_id', function() {
        var customer_id = $(this).val();
        if (customer_id == '0') {
            $('.new_customer').show();
        } else {
            $('.new_customer').hide();
        }
    });
</script>

<style>
    .product-card {
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
        transition: box-shadow 0.3s;
    }

    .cat-card {
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
        transition: box-shadow 0.3s;
    }

    .product-card:hover {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .invoice-details {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    .invoice-summary {
        margin-top: 15px;
        font-size: 18px;
        font-weight: bold;
    }
</style>

@endsection