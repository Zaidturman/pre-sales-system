@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">جميع الفواتير</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">

            <form method="GET" action="{{ route('invoice.search') }}" class="col-10">
                <div class="row mb-3">
                    <div class="col-md-10">
                        <input type="text" name="search_item" id="search_item" class="form-control" placeholder="ابحث عن صنف...">
                        <div id="productList" class="dropdown-menu show w-100"></div> <!-- قائمة منسدلة -->
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">بحث</button>
                    </div>
                </div>
            </form>
            <div class="col-2">

                <a class=" btn btn-danger" href="{{route('invoice.all')}}">اعادة تعيين</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <a href="{{route('invoice.add')}}" class="btn btn-secondary waves-effect waves-light" style="float:right;">اضافة فاتورة</a>
                        <br><br>

                        <h4 class="card-title">بيانات جميع الفواتير</h4><br>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الزبون</th>
                                    <th>رقم الفاتورة</th>
                                    <th>التاريخ</th>
                                    <th>الملاحظات</th>
                                    <th>المبلغ </th>
                                    <th>العمليات </th>

                                </tr>
                            </thead>


                            <tbody>
                                @foreach($allData as $key =>$item)
                                <tr>
                                    <td>{{( $key+1 )}}</td>
                                    <td>{{( $item['payment']['customer']['name'] ?? 'N/A' )}}</td>
                                    <td>#{{( $item->invoice_no )}}</td>
                                    <td>{{ date('d-m-Y',strtotime($item->date)) }}</td>
                                    <td>{{( $item->description )}}</td>

                                    <td>$ {{( $item['payment']['total_amount'] ?? 'N/A' )}}</td>
                                    <td>

                                        <a href="{{route('invoice.deleteafterapprove', $item->invoice_no)}}" class="btn btn-danger sm" title="Delete Data" id="delete"><i class="fas fa-trash"></i></a>

 <!-- زر عرض التفاصيل -->
 <a href="{{ route('print.invoice', $item->id) }}" class="btn btn-info btn-sm" title="عرض التفاصيل">
    <i class="ri-eye-line"></i>
</a>

<!-- زر تعديل الفاتورة -->
<a href="{{ route('invoices.edit', $item->id) }}" class="btn btn-warning btn-sm" title="تعديل">
    <i class="ri-edit-2-line"></i>
</a>
                                    </td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->


    </div> <!-- container-fluid -->
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $('#search_item').keyup(function(){
        let query = $(this).val();
        if(query.length > 1) {
            $.ajax({
                url: "{{ route('invoice.autocomplete') }}",
                type: "GET",
                data: {'query': query},
                success: function(data) {
                    $('#productList').html(data).show();
                }
            });
        } else {
            $('#productList').hide();
        }
    });

    $(document).on('click', '.dropdown-item', function(){
        $('#search_item').val($(this).text());
        $('#productList').hide();
    });
});
</script>


@endsection