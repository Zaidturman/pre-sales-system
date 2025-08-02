@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">جميع الزبائن</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <a href="{{route('customer.add')}}" class="btn btn-secondary waves-effect waves-light" style="float:right;">اضافة زبون</a>
                        <br><br>

                        <h4 class="card-title">بيانات جميع الزبائن</h4><br>
                        
                        <!-- Filters Section -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name_filter">بحث بالاسم</label>
                                    <input type="text" class="form-control" id="name_filter" placeholder="ادخل اسم الزبون">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="email_filter">بحث بالبريد الإلكتروني</label>
                                    <input type="text" class="form-control" id="email_filter" placeholder="ادخل البريد الإلكتروني">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="address_filter">بحث بالعنوان</label>
                                    <input type="text" class="form-control" id="address_filter" placeholder="ادخل العنوان">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group" style="margin-top: 28px;">
                                    <button id="filter_btn" class="btn btn-primary">تصفية</button>
                                    <button id="reset_filter_btn" class="btn btn-secondary">إعادة تعيين</button>
                                </div>
                            </div>
                        </div>
                        <!-- End Filters Section -->

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>صورة الزبون</th>
                                    <th>الايميل</th>
                                    <th>العنوان</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($customers as $key =>$item)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td><img src="{{ asset($item->customer_image) }}" style="width:60px;height:50px;"></td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->address }}</td>
                                    <td>
                                        <a href="{{route('customer.edit',$item->id)}}" class="btn btn-info sm" title="Edit Data"><i class="fas fa-edit"></i></a>
                                        <a href="{{route('customer.delete',$item->id)}}" class="btn btn-danger sm" title="Delete Data" id="delete"><i class="fas fa-trash"></i></a>
                                        <a href="{{route('customer.view',$item->id)}}" class="btn btn-primary sm" title="Customer Invoice Details"><i class="fas fa-eye"></i></a>
                                        <a href="{{route('partialpayments.create',$item->id)}}" class="btn btn-success sm" title="Customer pind Details"><i class="fas fa-plus"></i></a>
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

@section('scripts')
<!-- Include DataTables JS -->
<script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable with proper configuration
    var table = $('#datatable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
        },
        "columnDefs": [
            { "orderable": false, "targets": [2, 5] }, // Disable sorting on image and actions columns
            { "searchable": false, "targets": [0, 2, 5] } // Disable searching on serial no, image and actions columns
        ]
    });
    
    // Apply individual column filtering
    $('#name_filter').on('keyup', function() {
        table.column(1).search(this.value).draw();
    });
    
    $('#email_filter').on('keyup', function() {
        table.column(3).search(this.value).draw();
    });
    
    $('#address_filter').on('keyup', function() {
        table.column(4).search(this.value).draw();
    });
    
    // Reset all filters
    $('#reset_filter_btn').on('click', function() {
        $('#name_filter').val('');
        $('#email_filter').val('');
        $('#address_filter').val('');
        
        table.columns().search('').draw();
    });
    
    // Apply all filters on filter button click
    $('#filter_btn').on('click', function() {
        table.draw();
    });
    
    // Apply filters when pressing Enter in any filter field
    $('.form-control').keypress(function(e) {
        if (e.which == 13) {
            table.draw();
        }
    });
});
</script>
@endsection

@endsection