@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">اضافة فاتورة مشتريات</h4><br>

                        <div class="row">

                            <div class="col-md-4">
                                <div class="md-3">

                                    <label for="example-text-input" class="form-label" style="padding-top: 10px;">التاريخ</label>

                                    <input class="form-control" name="date" type="date" id="date">

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="md-3">

                                    <label for="example-text-input" class="form-label" style="padding-top: 10px;">رقم فاتورة المشتريات</label>

                                    <input class="form-control" name="purchase_no" type="text" id="purchase_no">

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="md-3">

                                    <label for="example-text-input" class="form-label" style="padding-top: 10px;">اسم المورد</label>

                                    <select name="supplier_id" id="supplier_id" class="form-select select2" aria-label="Default select example">
                                        <option selected="">اختر المورد</option>
                                        @foreach($supplier as $supp)
                                        <option value="{{$supp->id}}">{{$supp->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="md-3">

                                    <label for="example-text-input" class="form-label" style="padding-top: 10px;">اسم الفئة</label>

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

                                    <label for="example-text-input" class="form-label" style="padding-top: 10px;">اسم المنتج</label>

                                    <select name="product_id" id="product_id" class="form-select select2" aria-label="Default select example">
                                        <option selected="">اختر المنتج</option>


                                    </select>

                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="md-3">

                                    <label for="example-text-input" class="form-label" style="margin-top: 50px;"> </label>

                                    <i class="btn btn-secondary waves-effect waves-light fas fa-plus-circle addeventmore" style="margin-top: 10px;"> <span style="font-family: sans-serif; font-weight: normal;"> Add More</span></i>

                                </div>
                            </div>

                        </div><!-- end row -->

                    </div> <!--  end card body -->

                    <div class="card-body">
                        <form method="post" action="{{ route('purchase.store') }}">
                            @csrf

                            <table class="table-sm table-bordered" width="100%" style="border-color: #ddd;">
                                <thead>
                                    <tr>
                                        <th>الفئة</th>
                                        <th>اسم المنتج</th>
                                        <th>الكمية</th>
                                        <th>سعر الوحدة</th>
                                        <th>ملاحظات</th>
                                        <th>المبلغ الاجمالي</th>
                                        <th>العمليات</th>
                                    </tr>
                                </thead>

                                <tbody id="addRow" class="addRow">

                                </tbody>

                                <tbody>
                                    <tr>
                                        <td colspan="5"></td>
                                        <td>
                                            <input type="text" name="estimated_amount" value="0" id="estimated_amount" class="form-control estimated_amount" readonly style="background-color:#ddd;">
                                        </td>

                                        <td>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <br>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label> حالة الدفع</label>
                                    <select name="paid_status" id="paid_status" class="form-select">
                                        <option value="">اختر الحالة </option>
                                        <option value="full_paid">مدفوع كامل </option>
                                        <option value="full_due">دين كامل </option>
                                        <option value="partial_paid">دفع جزئي</option>
                                    </select>
                                    <input type="text" name="paid_amount" class="form-control paid_amount" placeholder="اكتب المبلغ المدفوع" style="display:none;">
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-info  mt-3" id="storeButton">اضافة مشتريات</button>
                            </div>
                        </form>
                        <!--  end card body -->


                    </div>
                </div> <!-- end col -->
            </div>

        </div>
    </div>


    <!-- For interactive table -->
    <script id="document-template" type="text/x-handlebars-template">

        <tr class="delete_add_more_item" id="delete_add_more_item">
        <input type="hidden" name="date[]" value="@{{date}}">
        <input type="hidden" name="purchase_no[]" value="@{{purchase_no}}">
        <input type="hidden" name="supplier_id[]" value="@{{supplier_id}}">

        <td>
            <input type="hidden" name="category_id[]" value="@{{category_id}}">
            @{{ category_name }}
        </td>

        <td>
            <input type="hidden" name="product_id[]" value="@{{product_id}}">
            @{{ product_name }}
        </td>

        <td>
            <input type="number" min="1" step="0.01" class="from-control buying_qty text-right" name="buying_qty[]" value="" required>
        </td>

        <td>
            <input type="number" step="0.01" class="from-control unit_price text-right" name="unit_price[]" value="" required>
        </td>

        <td>
            <input type="text" class="from-control" name="description[]">
        </td>

        <td>
            <input type="number" step="0.01" class="from-control buying_price text-right" name="buying_price[]" value="0" readonly>
        </td>

        <td>
            <i class="btn btn-danger btn-sm fas fa-window-close removeeventmore"></i>
        </td>
    </tr>

</script>
    <script type="text/javascript">
        // Paid amount For Partial Pay
        $(document).on('change', '#paid_status', function() {
        var paid_status = $(this).val();
        if (paid_status == 'partial_paid') {
            $('.paid_amount').show();
        } else {
            $('.paid_amount').hide();
        }
        });


       
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on("click", ".addeventmore", function() {
                var date = $('#date').val();
                var purchase_no = $('#purchase_no').val();
                var supplier_id = $('#supplier_id').val();
                var category_id = $('#category_id').val();
                var category_name = $('#category_id').find('option:selected').text();
                var product_id = $('#product_id').val();
                var product_name = $('#product_id').find('option:selected').text();

                // validation check
                if (date == '') {
                    $.notify("Date is Required", {
                        globalPosition: 'top right',
                        className: 'error'
                    });
                    return false;
                }

                if (purchase_no == '') {
                    $.notify("Purchase No is Required", {
                        globalPosition: 'top right',
                        className: 'error'
                    });
                    return false;
                }

                if (supplier_id == '') {
                    $.notify("Supplier is Required", {
                        globalPosition: 'top right',
                        className: 'error'
                    });
                    return false;
                }

                if (category_id == '') {
                    $.notify("Category is Required", {
                        globalPosition: 'top right',
                        className: 'error'
                    });
                    return false;
                }

                if (product_id == '') {
                    $.notify("Product is Required", {
                        globalPosition: 'top right',
                        className: 'error'
                    });
                    return false;
                }

                var source = $("#document-template").html();
                var template = Handlebars.compile(source);
                var data = {
                    date: date,
                    purchase_no: purchase_no,
                    supplier_id: supplier_id,
                    category_id: category_id,
                    category_name: category_name,
                    product_id: product_id,
                    product_name: product_name
                };
                var html = template(data);
                $("#addRow").append(html);

            });

            //remove data from table
            $(document).on("click", ".removeeventmore", function(event) {
                $(this).closest(".delete_add_more_item").remove();
                totalAmountPrice();
            });

            //calculation in table
            $(document).on('keyup click', '.unit_price,.buying_qty', function() {
                var unit_price = $(this).closest("tr").find("input.unit_price").val();
                var qty = $(this).closest("tr").find("input.buying_qty").val();
                var total = unit_price * qty;
                $(this).closest("tr").find("input.buying_price").val(total);
                totalAmountPrice();
            });

            //calculation sum of amount in table
            function totalAmountPrice() {
                var sum = 0;
                $(".buying_price").each(function() {
                    var value = $(this).val();
                    if (!isNaN(value) && value.length != 0) {
                        sum += parseFloat(value);
                    }
                });
                $('#estimated_amount').val(sum);
            }
        });
    </script>





    <script type="text/javascript">
        $(function() {
            $(document).on('change', '#category_id', function() {
                var category_id = $(this).val();
                $.ajax({
                    url: "{{route('get-product')}}",
                    type: "GET",
                    data: {
                        category_id: category_id
                    },
                    success: function(data) {
                        var html = '<option value="">Select Product</option>';
                        $.each(data, function(key, v) {
                            html += '<option value=" ' + v.id + ' ">' + v.name + '</option>';
                        });
                        $('#product_id').html(html);
                    }
                })
            });
        });
    </script>

    @endsection