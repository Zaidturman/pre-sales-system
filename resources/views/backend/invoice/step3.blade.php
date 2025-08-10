@extends('admin.admin_master')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">المرحلة 3: تفاصيل الفاتورة</h4><br><br>

                        <form method="post" action="{{ route('invoice.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">الزبون</label>
                                        <input type="text" class="form-control" value="{{ $customer->name }}" readonly>
                                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">رقم الفاتورة</label>
                                        <input type="text" class="form-control" name="invoice_no" value="{{ $invoice_no }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">التاريخ</label>
                                        <input type="date" class="form-control" name="date" value="{{ $date }}">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>المنتج</th>
                                            <th>السعر</th>
                                            <th>الكمية</th>
                                            <th>الإجمالي</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoice-items">
                                        @foreach($selectedProducts as $item)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="product_id[]" value="{{ $item['product']->id }}">
                                                {{ $item['product']->name }}
                                            </td>
                                            <td>
                                                <input type="number" class="form-control price" name="unit_price[]" 
                                                    value="{{ $item['product']->price }}" step="0.01" min="0">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control quantity" name="selling_qty[]" 
                                                    value="{{ $item['quantity'] }}" min="1">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control total" name="selling_price[]" 
                                                    value="{{ $item['product']->price * $item['quantity'] }}" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-item">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">الخصم</label>
                                        <input type="number" class="form-control" name="discount_amount" 
                                            id="discount" value="0" min="0" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">طريقة الدفع</label>
                                        <select class="form-select" name="payment_method" required>
                                            <option value="cash">نقدي</option>
                                            <option value="credit">آجل</option>
                                            <option value="card">بطاقة</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">حالة الدفع</label>
                                        <select class="form-select" name="paid_status" id="paid-status">
                                            <option value="full_paid">مدفوع بالكامل</option>
                                            <option value="partial_paid">دفع جزئي</option>
                                            <option value="full_due">دين كامل</option>
                                        </select>
                                        <input type="number" class="form-control mt-2 paid-amount" 
                                            name="paid_amount" placeholder="المبلغ المدفوع" 
                                            style="display: none;" min="0" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <a href="{{ route('invoice.step2') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-right"></i> السابق
                                    </a>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="invoice-summary bg-light p-3 rounded">
                                        <h5 class="d-flex justify-content-between">
                                            <span>المجموع:</span>
                                            <span id="subtotal">0.00</span>
                                        </h5>
                                        <h5 class="d-flex justify-content-between">
                                            <span>الخصم:</span>
                                            <span id="discount-amount">0.00</span>
                                        </h5>
                                        <h4 class="d-flex justify-content-between">
                                            <span>الإجمالي النهائي:</span>
                                            <span id="grand-total">0.00</span>
                                        </h4>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">
                                        <i class="fas fa-check"></i> تأكيد البيع
                                    </button>
                                </div>
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
    // Calculate totals
    function calculateTotals() {
        let subtotal = 0;
        
        $('#invoice-items tr').each(function() {
            const price = parseFloat($(this).find('.price').val()) || 0;
            const quantity = parseInt($(this).find('.quantity').val()) || 0;
            const total = price * quantity;
            
            $(this).find('.total').val(total.toFixed(2));
            subtotal += total;
        });
        
        const discount = parseFloat($('#discount').val()) || 0;
        const grandTotal = subtotal - discount;
        
        $('#subtotal').text(subtotal.toFixed(2));
        $('#discount-amount').text(discount.toFixed(2));
        $('#grand-total').text(grandTotal.toFixed(2));
    }
    
    // Initial calculation
    calculateTotals();
    
    // Recalculate when prices or quantities change
    $(document).on('input', '.price, .quantity, #discount', calculateTotals);
    
    // Remove item
    $(document).on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
        calculateTotals();
    });
    
    // Show/hide paid amount field based on payment status
    $('#paid-status').change(function() {
        if($(this).val() === 'partial_paid') {
            $('.paid-amount').show();
        } else {
            $('.paid-amount').hide();
        }
    });
});
</script>

<style>
    .invoice-summary {
        border: 1px solid #dee2e6;
    }
    .invoice-summary h4 {
        color: #0d6efd;
        font-weight: bold;
    }
    .table th {
        background-color: #f8f9fa;
    }
</style>
@endsection