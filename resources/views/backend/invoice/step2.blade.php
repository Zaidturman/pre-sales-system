@extends('admin.admin_master')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">المرحلة 2: اختيار المنتجات</h4><br><br>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="product-search" placeholder="ابحث عن منتج...">
                                    <button class="btn btn-primary" type="button" id="search-btn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- الفئات -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5>الفئات</h5>
                                <div class="d-flex flex-wrap category-buttons">
                                    @foreach($category as $cat)
                                    <button class="btn btn-outline-primary m-1 category-btn" data-category-id="{{$cat->id}}">
                                        {{ $cat->name }}
                                    </button>
                                    @endforeach
                                    <button class="btn btn-primary m-1 category-btn" data-category-id="all">
                                        الكل
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- المنتجات -->
                        <div class="row" id="products-container">
                            @foreach($products as $product)
                            <div class="col-md-3 mb-4 product-item" data-category-id="{{ $product->category_id }}">
                                <div class="card product-card">
                                    <img src="{{ asset($product->product_image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 150px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="card-text text-muted">{{ $product->description }}</p>
                                        <p class="card-text text-primary fw-bold">{{ $product->price }} شيكل</p>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <button class="btn btn-sm btn-danger decrement-btn">-</button>
                                            <span class="quantity">0</span>
                                            <button class="btn btn-sm btn-success increment-btn">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 d-flex justify-content-between">
                                <a href="{{ route('invoice.step1') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-right"></i> السابق
                                </a>
                                <button id="proceed-to-invoice" class="btn btn-primary">
                                    التالي <i class="fas fa-arrow-left"></i>
                                </button>
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
    // Filter by category
    $('.category-btn').click(function() {
        const categoryId = $(this).data('category-id');
        $('.category-btn').removeClass('active');
        $(this).addClass('active');
        
        if(categoryId === 'all') {
            $('.product-item').show();
        } else {
            $('.product-item').hide();
            $(`.product-item[data-category-id="${categoryId}"]`).show();
        }
    });

    // Search functionality
    $('#product-search').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        
        $('.product-item').each(function() {
            const productName = $(this).find('.card-title').text().toLowerCase();
            if(productName.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Quantity controls
    $('.increment-btn').click(function() {
        const quantityElement = $(this).siblings('.quantity');
        let quantity = parseInt(quantityElement.text());
        quantityElement.text(quantity + 1);
    });

    $('.decrement-btn').click(function() {
        const quantityElement = $(this).siblings('.quantity');
        let quantity = parseInt(quantityElement.text());
        if(quantity > 0) {
            quantityElement.text(quantity - 1);
        }
    });

    // Proceed to invoice
    $('#proceed-to-invoice').click(function() {
        const selectedProducts = [];
        
        $('.product-item').each(function() {
            const quantity = parseInt($(this).find('.quantity').text());
            if(quantity > 0) {
                const productId = $(this).data('product-id');
                selectedProducts.push({
                    id: productId,
                    quantity: quantity
                });
            }
        });

        if(selectedProducts.length === 0) {
            alert('الرجاء اختيار منتج واحد على الأقل');
            return;
        }

        // Here you would typically send the data to the server
        // and redirect to the invoice page
        window.location.href = "{{ route('invoice.step3') }}";
    });
});
</script>

<style>
    .product-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .category-buttons {
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 10px;
    }
    .quantity {
        font-size: 1.2rem;
        font-weight: bold;
        min-width: 30px;
        text-align: center;
    }
</style>
@endsection