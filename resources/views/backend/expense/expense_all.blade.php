@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">جميع المصاريف</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <a href="{{route('expense.create')}}" class="btn btn-secondary waves-effect waves-light" style="float:right;">اضافة مصروف</a>
                        <br><br>

                        <h4 class="card-title">بيانات جميع المصاريف</h4><br>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>التاريخ</th>
                                    <th>التفاصيل</th>
                                    <th>المبلغ </th>
                                    <th>الفئة </th>
                                    <th>العمليات </th>

                                </tr>
                            </thead>


                            <tbody>
                                @foreach($allData as $key =>$item)
                                <tr>
                                    <td>{{( $key+1 )}}</td>
                                    <td>{{( $item->date )}}</td>
                                    <td>{{$item->detials}}</td>
                                    <td>{{$item->amount }}</td>
                                    <td>{{ $item->category ? $item->category->name : 'N/A' }}</td>
                                    <td>
                                        <a href=" {{ route('expense.edit', $item->id) }}" class="btn btn-info sm" title="Edit Data"><i class="fas fa-edit"></i></a>

                                        <a href=" {{ route('expense.delete', $item->id) }}" class="btn btn-danger sm" title="Delete Data" id="delete"><i class="fas fa-trash"></i></a>
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