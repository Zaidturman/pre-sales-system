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
                                <th>رقم المصروف</th>
                                <th>التاريخ</th>
                                <th>الكمية </th>
                                <th>الفئة </th>
                                <th>العمليات </th>
                            </tr>
                            </thead>


                            <tbody>
                            @foreach($allData as $key =>$item)
                            <tr>
                                <td>{{( $key+1 )}}</td>
                                <td>{{( $item->id )}}</td>
                                <td>#{{( $item->date )}}</td>
                                <td>{{$item->amount }}</td>
                                <td>{{$item->category->name }}</td>
                                <td>    
                                    <a href="{{route('expense.print',$item->id)}}" class="btn btn-info sm" title="Print Expense"><i class="fas fa-print"></i></a>
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
