@extends('admin.admin_new_invoice')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* التصميم العام */
        .step-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 30px;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 600;
            padding: 12px 20px;
            position: relative;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            background: transparent;
        }

        .nav-tabs .nav-link.active:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: #0d6efd;
        }

        /* بطاقات الزبائن */
        .customer-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s;
            cursor: pointer;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
        }

        .customer-card:hover,
        .customer-card.selected {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-color: #0d6efd;
            background: #f0f7ff;
        }

        .customer-card.selected {
            border: 2px solid #0d6efd;
        }

        /* بطاقات الفئات */
        .category-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s;
            cursor: pointer;
            margin-bottom: 15px;
            padding: 15px;
            text-align: center;
            background: #f1f8ff;
        }

        .category-card:hover,
        .category-card.active {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            background: #d4e6ff;
        }

        .category-card.active {
            border: 2px solid #0d6efd;
        }

        /* بطاقات المنتجات */
        .product-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s;
            cursor: pointer;
            margin-bottom: 15px;
            padding: 15px;
            text-align: center;
            position: relative;
            background: #fff;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            width: 100%;
            height: 120px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .product-actions {
            margin-top: 10px;
        }

        .product-quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
            gap: 5px;
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #dee2e6;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .quantity-btn:hover {
            background: #e9ecef;
            border-color: #0d6efd;
        }

        .quantity-display {
            min-width: 40px;
            text-align: center;
            font-weight: bold;
            color: #0d6efd;
        }

        /* عناصر الفاتورة */
        .invoice-item {
            border-bottom: 1px solid #eee;
            padding: 12px 0;
            vertical-align: middle;
        }

        .input-group-quantity {
            width: 120px;
        }

        .input-group-quantity .btn {
            width: 35px;
            background: #f8f9fa;
        }

        .quantity-input {
            text-align: center;
            background: #fff;
        }

        /* ملخص الفاتورة */
        .invoice-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            border: 1px solid #e0e0e0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .summary-total {
            font-weight: bold;
            font-size: 1.2rem;
            color: #0d6efd;
        }

        /* الأزرار */
        .btn-next,
        .btn-prev {
            min-width: 150px;
            padding: 10px 25px;
            font-weight: 600;
        }

        .btn-next i,
        .btn-prev i {
            margin-right: 8px;
        }

        /* البحث والفلترة */
        .search-box {
            position: relative;
            margin-bottom: 20px;
        }

        .search-box i {
            position: absolute;
            top: 12px;
            right: 15px;
            color: #6c757d;
        }

        /* الزر العائم */
        .floating-invoice-btn {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #28a745;
            border: none;
            box-shadow: 0 4px 20px rgba(40, 167, 69, 0.4);
            display: none;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            transition: all 0.3s;
        }

        .floating-invoice-btn:hover {
            transform: scale(1.1);
            background: #218838;
        }

        .floating-invoice-btn.show {
            display: flex;
        }

        .invoice-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        .highlight {
            animation: pulse 1s;
        }

        @keyframes pulse {
            0% {
                background-color: #fff3cd;
            }

            50% {
                background-color: #ffeaa7;
            }

            100% {
                background-color: #fff3cd;
            }
        }

        /* التجاوب */
        @media (max-width: 768px) {
            .nav-tabs .nav-link {
                padding: 10px 15px;
                font-size: 14px;
            }

            .input-group-quantity {
                width: 100px;
            }

            .floating-invoice-btn {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }

        .customer-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .border-hover-primary:hover {
            border-color: var(--bs-primary) !important;
        }

        .bg-primary-light {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .bg-primary-light {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .bg-success-light {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .product-image-container {
            border: 1px dashed #dee2e6;
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="step-container">
                        <h4 class="card-title mb-4">إنشاء فاتورة جديدة</h4>

                        <!-- خطوات الفاتورة -->
                        <ul class="nav nav-tabs mb-4">
                            <li class="nav-item">
                                <a class="nav-link active" id="step1-tab" data-bs-toggle="tab" href="#step1">
                                    <i class="fas fa-user"></i> الزبون
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="step2-tab" data-bs-toggle="tab" href="#step2">
                                    <i class="fas fa-boxes"></i> المنتجات
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="step3-tab" data-bs-toggle="tab" href="#step3">
                                    <i class="fas fa-file-invoice"></i> الفاتورة
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- الخطوة 1: اختيار الزبون -->
                            <div class="tab-pane fade show active" id="step1">
                                <div class="row">
                                    <div class="col-md-12 offset-md-1">
                                        <!-- نموذج البحث -->
                                        <form method="GET" action="{{ route('invoice.create') }}" class="mb-4">
                                            <div class="input-group shadow-sm">
                                                <input type="text" name="customer_query"
                                                    class="form-control form-control-lg border-primary"
                                                    placeholder="ابحث عن زبون بالاسم أو رقم الهاتف..."
                                                    value="{{ request('customer_query') }}">
                                                <button class="btn btn-primary px-4" type="submit">
                                                    <i class="fas fa-search me-2"></i> بحث
                                                </button>
                                                @if (request('customer_query'))
                                                    <a href="{{ route('invoice.create') }}"
                                                        class="btn btn-outline-secondary">
                                                        <i class="fas fa-times me-2"></i>إعادة تعيين
                                                    </a>
                                                @endif
                                            </div>
                                        </form>

                                        <!-- عرض الزبائن -->
                                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4" id="customers-list">
                                            @forelse($customer as $cust)
                                                <div class="col">
                                                    <div class="customer-card card h-100 shadow-sm border-hover-primary"
                                                        onclick="selectCustomer({{ $cust->id }}, '{{ $cust->name }}', '{{ $cust->mobile_no }}', this)"
                                                        style="cursor: pointer; transition: all 0.3s ease;">
                                                        <div class="card-body text-center py-4">
                                                            <div class="customer-avatar mb-3 mx-auto bg-primary-light rounded-circle d-flex align-items-center justify-content-center"
                                                                style="width: 60px; height: 60px;">
                                                                <i class="fas fa-user text-primary"
                                                                    style="font-size: 24px;"></i>
                                                            </div>
                                                            <h5 class="card-title text-dark mb-1">{{ $cust->name }}</h5>
                                                            <p class="card-text text-muted mb-2">
                                                                <i class="fas fa-phone-alt me-2"></i>{{ $cust->mobile_no }}
                                                            </p>
                                                            <span class="badge bg-primary-light text-primary small">اختر هذا
                                                                الزبون</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12">
                                                    <div class="text-center p-5 border rounded bg-light">
                                                        <i class="fas fa-user-slash fa-2x text-muted mb-3"></i>
                                                        <h5 class="text-muted">لا يوجد زبائن مطابقة للبحث</h5>
                                                        <p class="text-muted small">حاول استخدام كلمات بحث مختلفة</p>
                                                    </div>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- الخطوة 2: اختيار المنتجات -->
                            <div class="tab-pane fade" id="step2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="mb-3">اختر الفئة</h5>
                                        <div class="row mb-4">
                                            @foreach ($category as $cat)
                                                <div class="col-md-2">
                                                    <div class="category-card"
                                                        onclick="showCategoryProducts({{ $cat->id }}, this)">
                                                        <h6>{{ $cat->name }}</h6>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="search-box">
                                            <input type="text" id="product_search" class="form-control"
                                                placeholder="ابحث عن منتج...">
                                            <i class="fas fa-search"></i>
                                        </div>

                                        <h5 class="mb-3">المنتجات</h5>
                                        <div class="row" id="products-list">
                                            @foreach ($products as $product)
                                                <div class="col-md-2 product-item"
                                                    data-category-id="{{ $product->category_id }}">
                                                    <div class="product-card">
                                                        @if ($product->image_url)
                                                            <img src="{{ asset($product->image_url) }}"
                                                                class="product-image" style="cursor: pointer;"
                                                                data-bs-toggle="modal" data-bs-target="#productModal"
                                                                onclick="showProductDetails(
         '{{ $product->name }}',
         '{{ $product->category->name ?? 'غير مصنف' }}',
         '{{ $product->unit->name ?? 'غير محدد' }}',
         '{{ $product->selling_price }}',
         '{{ $product->quantity ?? 0 }}',
         `{!! addslashes($product->descr ?? 'لا يوجد وصف') !!}`,
         '{{ $product->image_url ? asset($product->image_url) : asset('upload/no_image.jpg') }}'
     )">
                                                        @else
                                                            <img src="{{ asset('upload/no_image.jpg') }}"
                                                                class="product-image" style="cursor: pointer;"
                                                                data-bs-toggle="modal" data-bs-target="#productModal"
                                                                onclick="showProductDetails(
         '{{ $product->name }}',
         '{{ $product->category->name ?? 'غير مصنف' }}',
         '{{ $product->unit->name ?? 'غير محدد' }}',
         '{{ $product->selling_price }}',
         '{{ $product->quantity ?? 0 }}',
         `{!! addslashes($product->descr ?? 'لا يوجد وصف') !!}`,
         '{{ $product->image_url ? asset($product->image_url) : asset('upload/no_image.jpg') }}'
     )">
                                                        @endif
                                                        <h6>{{ $product->name }}</h6>
                                                        <p class="text-primary">{{ $product->selling_price }} شيكل</p>
                                                        <div class="product-quantity-control">
                                                            <div class="quantity-btn"
                                                                onclick="changeProductQuantity({{ $product->id }}, -1)">
                                                                <i class="fas fa-minus"></i>
                                                            </div>
                                                            <div class="quantity-display"
                                                                id="product-qty-{{ $product->id }}">0</div>
                                                            <div class="quantity-btn"
                                                                onclick="changeProductQuantity({{ $product->id }}, 1)">
                                                                <i class="fas fa-plus"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <!-- Modal عرض تفاصيل المنتج -->
                                        <div class="modal fade" id="productModal" tabindex="-1"
                                            aria-labelledby="productModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-light">
                                                        <h5 class="modal-title fw-bold" id="productModalLabel">تفاصيل
                                                            المنتج</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <!-- قسم الصورة -->
                                                            <div class="col-md-5 mb-3 mb-md-0">
                                                                <div class="col-md-5 mb-3 mb-md-0">
                                                                    <div class="product-image-container bg-light rounded-3 p-3 text-center"
                                                                        style="height: 300px;">
                                                                        <img id="modal-product-image" src=""
                                                                            alt="صورة المنتج"
                                                                            class="img-fluid h-100 object-fit-contain">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- قسم التفاصيل -->
                                                            <div class="col-md-7">
                                                                <ul class="list-group list-group-flush">
                                                                    <li
                                                                        class="list-group-item d-flex justify-content-between align-items-center py-3">
                                                                        <strong class="text-muted">الاسم:</strong>
                                                                        <span id="modal-product-name"
                                                                            class="fw-bold text-dark"></span>
                                                                    </li>
                                                                    <li
                                                                        class="list-group-item d-flex justify-content-between align-items-center py-3">
                                                                        <strong class="text-muted">الفئة:</strong>
                                                                        <span id="modal-category"
                                                                            class="badge bg-primary-light text-primary"></span>
                                                                    </li>
                                                                    <li
                                                                        class="list-group-item d-flex justify-content-between align-items-center py-3">
                                                                        <strong class="text-muted">الوحدة:</strong>
                                                                        <span id="modal-unit" class="fw-bold"></span>
                                                                    </li>
                                                                    <li
                                                                        class="list-group-item d-flex justify-content-between align-items-center py-3">
                                                                        <strong class="text-muted">الكمية المتاحة:</strong>
                                                                        <span id="modal-quantity"
                                                                            class="badge bg-success-light text-success"></span>
                                                                    </li>
                                                                    <li class="list-group-item py-3">
                                                                        <strong
                                                                            class="text-muted d-block mb-2">الوصف:</strong>
                                                                        <div id="modal-descr"
                                                                            class="p-3 bg-light rounded-2 text-dark"></div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top-0">
                                                        <button type="button" class="btn btn-outline-secondary px-4"
                                                            data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-2"></i>إغلاق
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <button class="btn btn-secondary btn-prev me-2" onclick="prevStep(1)">
                                                <i class="fas fa-arrow-right"></i> السابق
                                            </button>
                                            <button class="btn btn-primary btn-next" onclick="nextStep(3)">
                                                <i class="fas fa-arrow-left"></i> التالي إلى الفاتورة
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- الخطوة 3: تفاصيل الفاتورة -->
                            <div class="tab-pane fade" id="step3">
                                <form method="post" action="{{ route('invoice.store') }}" id="invoice-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body">
                                                    <h5 class="card-title mb-4">تفاصيل الفاتورة</h5>

                                                    <input type="hidden" name="customer_id" id="selected_customer_id">
                                                    <input type="hidden" name="date" value="{{ $date }}">
                                                    <input type="hidden" name="invoice_no" value="{{ $invoice_no }}">

                                                    <div class="mb-4">
                                                        <label class="form-label">الزبون</label>
                                                        <input type="text" class="form-control bg-light"
                                                            id="selected_customer_name" readonly>
                                                    </div>

                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th width="35%">المنتج</th>
                                                                    <th width="20%">الكمية</th>
                                                                    <th width="20%">السعر</th>
                                                                    <th width="20%">الإجمالي</th>
                                                                    <th width="5%"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="invoice-items">
                                                                <!-- سيتم إضافة العناصر هنا -->
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">الخصم</label>
                                                            <div class="input-group">
                                                                <input type="number" name="discount_amount"
                                                                    id="discount_amount" class="form-control"
                                                                    placeholder="مبلغ الخصم" value="0"
                                                                    min="0">
                                                                <span class="input-group-text">شيكل</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">طريقة الدفع</label>
                                                            <select name="payment_method" class="form-select" required>
                                                                <option value="cash_shekel">نقدي شيكل</option>
                                                                <option value="cash_dinar">نقدي دينار</option>
                                                                <option value="check">شيك</option>
                                                                <option value="bank_transfer">حوالة بنكية</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="invoice-summary mt-4">
                                                        <div class="summary-row">
                                                            <span>المجموع:</span>
                                                            <span id="subtotal-amount">0.00 شيكل</span>
                                                        </div>
                                                        <div class="summary-row">
                                                            <span>الخصم:</span>
                                                            <span id="discount-display">0.00 شيكل</span>
                                                        </div>
                                                        <hr>
                                                        <div class="summary-row summary-total">
                                                            <span>الإجمالي النهائي:</span>
                                                            <span id="total-amount">0.00 شيكل</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body">
                                                    <h5 class="card-title mb-4">دفع الفاتورة</h5>

                                                    <div class="mb-3">
                                                        <label class="form-label">حالة الدفع</label>
                                                        <select name="paid_status" id="paid_status" class="form-select">
                                                            <option value="full_paid">مدفوع كامل</option>
                                                            <option value="partial_paid">دفع جزئي</option>
                                                            <option value="full_due">دين كامل</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3" id="paid-amount-container" style="display:none;">
                                                        <label class="form-label">المبلغ المدفوع</label>
                                                        <div class="input-group">
                                                            <input type="number" name="paid_amount" class="form-control"
                                                                placeholder="المبلغ المدفوع" min="0">
                                                            <span class="input-group-text">شيكل</span>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">ملاحظات</label>
                                                        <textarea name="notes" class="form-control" rows="3"></textarea>
                                                    </div>

                                                    <input type="hidden" name="estimated_amount" id="estimated_amount"
                                                        value="0">

                                                    <div class="d-grid gap-2 mt-4">
                                                        <button type="submit" class="btn btn-success btn-lg">
                                                            <i class="fas fa-check-circle"></i> حفظ الفاتورة
                                                        </button>
                                                        <button type="button" class="btn btn-secondary"
                                                            onclick="prevStep(2)">
                                                            <i class="fas fa-arrow-right"></i> رجوع
                                                        </button>
                                                    </div>
                                                </div>
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

    <!-- الزر العائم للفاتورة -->
    <button class="floating-invoice-btn" id="floating-invoice-btn" onclick="goToInvoice()">
        <i class="fas fa-receipt"></i>
        <span class="invoice-badge" id="invoice-badge">0</span>
    </button>

    <script>
        // تخزين كميات المنتجات
        let productQuantities = {};
        let invoiceItems = {};

        // عرض تفاصيل المنتج في المودال
        function showProductDetails(name, category, unit, price, quantity, descr, imageUrl) {
            document.getElementById('modal-product-name').textContent = name;
            document.getElementById('modal-category').textContent = category;
            document.getElementById('modal-unit').textContent = unit;
            document.getElementById('modal-price').textContent = parseFloat(price).toFixed(2);
            document.getElementById('modal-quantity').textContent = quantity;
            document.getElementById('modal-descr').innerHTML = descr;

            // عرض الصورة
            const modalImage = document.getElementById('modal-product-image');
            modalImage.src = imageUrl;

        }

        $(document).ready(function() {
            // تهيئة Select2 للبحث عن الزبائن
            $('#customer_search').select2({
                placeholder: "ابحث عن زبون...",
                allowClear: true,
                language: {
                    noResults: function() {
                        return "لا توجد نتائج";
                    }
                }
            });

            // البحث عن الزبائن
            $('#customer_search').on('change', function() {
                var customerId = $(this).val();
                if (customerId) {
                    var customer = $(this).find('option:selected').text().split(' - ');
                    selectCustomer(customerId, customer[0], customer[1]);
                }
            });

            // البحث عن المنتجات
            $('#product_search').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('.product-item').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // تغيير حالة الدفع
            $('#paid_status').change(function() {
                if ($(this).val() == 'partial_paid') {
                    $('#paid-amount-container').show();
                } else {
                    $('#paid-amount-container').hide();
                }
            });

            // تهيئة كميات المنتجات
            @foreach ($products as $product)
                productQuantities[{{ $product->id }}] = 0;
            @endforeach

            updateFloatingButton();
        });

        // اختيار الزبون
        function selectCustomer(id, name, phone, element) {
            $('#selected_customer_id').val(id);
            $('#selected_customer_name').val(name + ' - ' + phone);
            $('#customer_search').val(id).trigger('change');

            // إزالة التحديد من جميع البطاقات
            $('.customer-card').removeClass('selected');

            // إضافة التحديد للبطاقة المختارة
            if (element) {
                $(element).addClass('selected');
            }

            // إظهار رسالة تأكيد
            Toastify({
                text: "تم اختيار الزبون: " + name,
                duration: 3000,
                gravity: "top",
                position: "left",
                backgroundColor: "#4fbe87",
            }).showToast();
        }

        // التنقل بين الخطوات
        function nextStep(step) {
            // التحقق من اختيار زبون قبل الانتقال للخطوة 2
            if (step === 2 && !$('#selected_customer_id').val()) {
                Swal.fire({
                    title: 'تحذير',
                    text: 'يجب اختيار زبون قبل المتابعة',
                    icon: 'warning',
                    confirmButtonText: 'حسناً'
                });
                return;
            }

            // التحقق من وجود منتجات قبل الانتقال للخطوة 3
            if (step === 3 && Object.keys(invoiceItems).length === 0) {
                Swal.fire({
                    title: 'تحذير',
                    text: 'يجب إضافة منتجات على الأقل قبل المتابعة',
                    icon: 'warning',
                    confirmButtonText: 'حسناً'
                });
                return;
            }

            $('.nav-tabs .nav-link').removeClass('active');
            $('.tab-pane').removeClass('show active');

            $('#step' + step + '-tab').addClass('active');
            $('#step' + step).addClass('show active');

            // التمرير لأعلى الصفحة
            $('html, body').animate({
                scrollTop: 0
            }, 'slow');
        }

        function prevStep(step) {
            $('.nav-tabs .nav-link').removeClass('active');
            $('.tab-pane').removeClass('show active');

            $('#step' + step + '-tab').addClass('active');
            $('#step' + step).addClass('show active');

            // التمرير لأعلى الصفحة
            $('html, body').animate({
                scrollTop: 0
            }, 'slow');
        }

        // عرض منتجات الفئة المحددة
        function showCategoryProducts(categoryId, element) {
            $('.product-item').hide();
            $('.product-item[data-category-id="' + categoryId + '"]').show();

            // إزالة التحديد من جميع فئات البطاقات
            $('.category-card').removeClass('active');

            // إضافة التحديد للفئة المختارة
            if (element) {
                $(element).addClass('active');
            }
        }

        // تغيير كمية المنتج
        function changeProductQuantity(productId, change) {
            const currentQty = productQuantities[productId] || 0;
            const newQty = Math.max(0, currentQty + change);

            productQuantities[productId] = newQty;
            document.getElementById('product-qty-' + productId).textContent = newQty;

            // تحديث الفاتورة
            if (newQty > 0) {
                addToInvoice(productId, newQty);
            } else {
                removeFromInvoice(productId);
            }

            updateFloatingButton();
        }

        // إضافة منتج للفاتورة
        function addToInvoice(productId, quantity) {
            // الحصول على معلومات المنتج
            let product = null;
            @foreach ($products as $product)
                if ({{ $product->id }} == productId) {
                    product = {
                        id: {{ $product->id }},
                        name: '{{ $product->name }}',
                        category_id: {{ $product->category_id }},
                        price: parseFloat({{ $product->selling_price }}),
                        quantity: quantity
                    };
                }
            @endforeach

            if (!product) return;

            // التحقق من وجود المنتج في الفاتورة
            if (invoiceItems[productId]) {
                invoiceItems[productId].quantity = quantity;
            } else {
                invoiceItems[productId] = product;
            }

            rebuildInvoiceTable();
            updateInvoiceTotal();
        }

        // إزالة منتج من الفاتورة
        function removeFromInvoice(productId) {
            delete invoiceItems[productId];
            rebuildInvoiceTable();
            updateInvoiceTotal();
            updateFloatingButton();
        }

        // إعادة بناء جدول الفاتورة
        function rebuildInvoiceTable() {
            const tbody = document.getElementById('invoice-items');
            tbody.innerHTML = '';

            Object.values(invoiceItems).forEach(function(item) {
                const tr = document.createElement('tr');
                tr.className = 'invoice-item';
                tr.setAttribute('data-product-id', item.id);

                const total = (item.quantity * item.price).toFixed(2);

                tr.innerHTML = `
                <input type="hidden" name="product_id[]" value="${item.id}">
                <input type="hidden" name="category_id[]" value="${item.category_id}">
                <td>${item.name}</td>
                <td>
                    <div class="input-group input-group-quantity">
                        <button type="button" class="btn quantity-minus" onclick="updateInvoiceQuantity(${item.id}, -1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" min="1" class="form-control quantity-input quantity" 
                               name="selling_qty[]" value="${item.quantity}" readonly>
                        <button type="button" class="btn quantity-plus" onclick="updateInvoiceQuantity(${item.id}, 1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control form-control-sm price" 
                           name="unit_price[]" value="${item.price}" onchange="updateItemPrice(${item.id}, this.value)">
                </td>
                <td class="item-total">${total}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-item" onclick="removeItemFromInvoice(${item.id})">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            `;

                tbody.appendChild(tr);
            });
        }

        // تحديث كمية المنتج في الفاتورة
        function updateInvoiceQuantity(productId, change) {
            if (invoiceItems[productId]) {
                const newQty = Math.max(1, invoiceItems[productId].quantity + change);
                invoiceItems[productId].quantity = newQty;
                productQuantities[productId] = newQty;

                // تحديث العرض في صفحة المنتجات
                document.getElementById('product-qty-' + productId).textContent = newQty;

                // إعادة بناء الجدول
                rebuildInvoiceTable();
                updateInvoiceTotal();
                updateFloatingButton();
            }
        }

        // تحديث سعر المنتج
        function updateItemPrice(productId, newPrice) {
            if (invoiceItems[productId]) {
                invoiceItems[productId].price = parseFloat(newPrice) || 0;
                rebuildInvoiceTable();
                updateInvoiceTotal();
            }
        }

        // إزالة عنصر من الفاتورة
        function removeItemFromInvoice(productId) {
            delete invoiceItems[productId];
            productQuantities[productId] = 0;

            // تحديث العرض في صفحة المنتجات
            document.getElementById('product-qty-' + productId).textContent = 0;

            rebuildInvoiceTable();
            updateInvoiceTotal();
            updateFloatingButton();

            Toastify({
                text: "تم حذف المنتج من الفاتورة",
                duration: 2000,
                gravity: "top",
                position: "left",
                backgroundColor: "#dc3545",
            }).showToast();
        }

        // تحديث الفاتورة الكلية
        function updateInvoiceTotal() {
            let subtotal = 0;

            Object.values(invoiceItems).forEach(function(item) {
                subtotal += item.quantity * item.price;
            });

            const discount = parseFloat($('#discount_amount').val()) || 0;
            const total = subtotal - discount;

            $('#subtotal-amount').text(subtotal.toFixed(2) + ' شيكل');
            $('#discount-display').text(discount.toFixed(2) + ' شيكل');
            $('#total-amount').text(total.toFixed(2) + ' شيكل');
            $('#estimated_amount').val(total.toFixed(2));
        }

        // تحديث الزر العائم
        function updateFloatingButton() {
            const itemCount = Object.keys(invoiceItems).length;
            const badge = document.getElementById('invoice-badge');
            const button = document.getElementById('floating-invoice-btn');

            badge.textContent = itemCount;

            if (itemCount > 0) {
                button.classList.add('show');
            } else {
                button.classList.remove('show');
            }
        }

        // الانتقال للفاتورة
        function goToInvoice() {
            nextStep(3);
        }

        // أحداث إضافية
        $(document).on('input', '#discount_amount', function() {
            updateInvoiceTotal();
        });

        // التحقق قبل الإرسال
        $('#invoice-form').on('submit', function(e) {
            e.preventDefault();

            // التحقق من وجود منتجات في الفاتورة
            if (Object.keys(invoiceItems).length === 0) {
                Swal.fire({
                    title: 'خطأ',
                    text: 'يجب إضافة منتجات على الأقل',
                    icon: 'error',
                    confirmButtonText: 'حسناً'
                });
                return;
            }

            // التحقق من اختيار زبون
            if (!$('#selected_customer_id').val()) {
                Swal.fire({
                    title: 'خطأ',
                    text: 'يجب اختيار زبون',
                    icon: 'error',
                    confirmButtonText: 'حسناً'
                });
                return;
            }

            // التحقق من صحة الأسعار والكميات
            let isValid = true;
            $('.price').each(function() {
                if ($(this).val() === '' || parseFloat($(this).val()) <= 0) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!isValid) {
                Swal.fire({
                    title: 'خطأ',
                    text: 'يجب إدخال أسعار صحيحة لجميع المنتجات',
                    icon: 'error',
                    confirmButtonText: 'حسناً'
                });
                return;
            }

            // إذا كان كل شيء صحيحاً، قم بإرسال النموذج
            this.submit();
        });

        // إعادة تعيين النموذج
        function resetForm() {
            // إعادة تعيين البيانات
            invoiceItems = {};
            productQuantities = {};

            // إعادة تعيين كميات المنتجات
            @foreach ($products as $product)
                productQuantities[{{ $product->id }}] = 0;
                document.getElementById('product-qty-{{ $product->id }}').textContent = 0;
            @endforeach

            // مسح الفاتورة
            document.getElementById('invoice-items').innerHTML = '';

            // إعادة تعيين النموذج
            document.getElementById('invoice-form').reset();
            $('#selected_customer_id').val('');
            $('#selected_customer_name').val('');
            $('#customer_search').val(null).trigger('change');
            $('.customer-card').removeClass('selected');
            $('.category-card').removeClass('active');
            $('.product-item').show();

            // تحديث المجاميع
            updateInvoiceTotal();
            updateFloatingButton();

            // الانتقال للخطوة الأولى
            nextStep(1);
        }
    </script>
@endsection
