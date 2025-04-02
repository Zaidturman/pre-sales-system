@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">صفحة اضافة منتج الى المخزن</h4><br>

                        <form method="post" action="{{ route('stock.store') }}" id="myForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label for="example-text-input" class="form-label">اسم الفئة</label>
                                        <select name="category_id" id="category_id" class="form-select select2" aria-label="Default select example">
                                            <option selected="">اختر الفئة</option>
                                            @foreach($category as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label for="example-text-input" class="form-label">اسم المنتج </label>
                                        <select name="product_id" id="product_id" class="form-select select2" aria-label="Default select example">
                                            <option selected="">اختر المنتج</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="md-3">

                                        <label for="example-text-input" class="form-label">الكمية</label>
                                        <div class="form-group col-sm-10">
                                            <input type="number" step="0.01" class="form-control" name="quantity" value="0">
                                        </div>
                                    </div>
                                </div>


                                <input type="submit" class="btn btn-info waves-effect waves-light mt-5" value="Add Category">

                        </form>

                    </div>
                </div>
            </div> <!-- end col -->
        </div>

    </div>
</div>
<script type="text/javascript">
    $(function() {
        $(document).on('change', '#category_id', function() {
            var category_id = $(this).val();
            $.ajax({
                url: "{{ route('get-product') }}",
                type: "GET",
                data: {
                    category_id: category_id
                },
                success: function(data) {
                    var html = '<option value="">Select Category</option>';
                    $.each(data, function(key, v) {
                        html += '<option value=" ' + v.id + ' "> ' + v.name + '</option>';
                    });
                    $('#product_id').html(html);
                }
            })
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#myForm').validate({
            rules: {
                name: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: 'Please Enter Category Name',
                },
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
            },
        })
    })
</script>

@endsection