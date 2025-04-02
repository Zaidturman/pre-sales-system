@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">جميع الفئات</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <a href="{{route('expense.createcategory')}}" class="btn btn-secondary waves-effect waves-light" style="float:right;">اضافة فئة</a>
                        <br><br>

                        <h4 class="card-title"  >جميع بيانات فئات المصاريف</h4><br>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>الاسم</th>
                                    <th>التفاصيل</th>
                                    <th>عدد المصروفات</th>
                                    <th width="20%">العمليات</th>
                                </tr>
                            </thead>


                            <tbody>
                            @foreach($categories as $index=>$category)
                                <tr>
                                    <td>{{$index +1}}</td>
                                    <td>{{$category->name}}</td>
                                    <td>{{$category->description}}</td>
                                    <td>{{$category->expenses_count}}</td>
                                    <td>
                                        <a href="{{route('expense.editcategory',$category->id)}}" class="btn btn-info sm" title="Edit Data"><i class="fas fa-edit"></i></a>

                                        <a href="" class="btn btn-danger sm" title="Delete Data" id="delete"><i class="fas fa-trash"></i></a>
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

@endsection