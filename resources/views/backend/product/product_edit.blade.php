@extends('admin.admin_master')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">صفحة تعديل منتج</h4><br>
                            <form method="post" action="{{ route('product.update') }}" id="myForm"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">

                                <!-- اسم المنتج -->
                                <div class="row mb-3 mt-3">
                                    <label for="name" class="col-sm-2 col-form-label">اسم المنتج</label>
                                    <div class="form-group col-sm-10">
                                        <input name="name" class="form-control" type="text"
                                            value="{{ old('name', $product->name) }}" required>
                                    </div>
                                </div>

                                <!-- الوحدة -->
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">الوحدة</label>
                                    <div class="col-sm-10">
                                        <select name="unit_id" class="form-select" required>
                                            <option value="">اختر الوحدة</option>
                                            @foreach ($unit as $uni)
                                                <option value="{{ $uni->id }}"
                                                    {{ $uni->id == $product->unit_id ? 'selected' : '' }}>
                                                    {{ $uni->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- الفئة -->
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">اسم الفئة</label>
                                    <div class="col-sm-10">
                                        <select name="category_id" class="form-select" required>
                                            <option value="">اختر الفئة</option>
                                            @foreach ($category as $cat)
                                                <option value="{{ $cat->id }}"
                                                    {{ $cat->id == $product->category_id ? 'selected' : '' }}>
                                                    {{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- السعر -->
                                <div class="row mb-3">
                                    <label for="price" class="col-sm-2 col-form-label">السعر</label>
                                    <div class="form-group col-sm-10">
                                        <input name="price" class="form-control" type="number" step="0.01"
                                            min="0" value="{{ old('price', $product->price) }}">
                                    </div>
                                </div>

                                <!-- الوصف -->
                                <div class="row mb-3">
                                    <label for="descr" class="col-sm-2 col-form-label">الوصف</label>
                                    <div class="form-group col-sm-10">
                                        <textarea name="descr" class="form-control" rows="3">{{ old('descr', $product->descr) }}</textarea>
                                    </div>
                                </div>

                                <!-- صورة المنتج -->
                                <div class="row mb-3">
                                    <label for="image" class="col-sm-2 col-form-label">صورة المنتج</label>
                                    <div class="form-group col-sm-10">
                                        <input name="image" class="form-control" type="file" id="image">
                                        <div class="mt-2">
                                            <!-- عرض الصورة الحالية -->
                                            <img id="showImage"
                                                src="{{ $product->image_url ? $product->image_url : url('upload/no_image.jpg') }}"
                                                alt="صورة المنتج"
                                                style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ddd; border-radius: 5px;">
                                        </div>
                                    </div>
                                </div>

                                <!-- زر الحفظ -->
                                <input type="submit" class="btn btn-info waves-effect waves-light" value="تحديث المنتج">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            // معاينة الصورة عند التغيير
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files[0]);
            });

            // التحقق من صحة النموذج
            $('#myForm').validate({
                rules: {
                    name: {
                        required: true
                    },
                    unit_id: {
                        required: true
                    },
                    category_id: {
                        required: true
                    },
                    price: {
                        number: true,
                        min: 0
                    }
                },
                messages: {
                    name: {
                        required: 'يرجى إدخال اسم المنتج'
                    },
                    unit_id: {
                        required: 'يرجى اختيار الوحدة'
                    },
                    category_id: {
                        required: 'يرجى اختيار الفئة'
                    },
                    price: {
                        number: 'يجب أن يكون السعر رقمًا',
                        min: 'يجب أن يكون السعر أكبر من أو يساوي الصفر'
                    }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection
