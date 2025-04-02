@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">جميع المشتريات</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <a href="{{route('purchase.add')}}" class="btn btn-secondary waves-effect waves-light" style="float:right;">اضافة مشتريات</a>
                        <br><br>

                        <h4 class="card-title">جميع بيانات المشتريات</h4><br>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>رقم فاتورة المشتريات</th>
                                    <th>التاريخ</th>
                                    <th>المورد</th>
                                    <th>الفئة</th>
                                    <th>الكمية</th>
                                    <th>اسم المنتج</th>
                                    <th>الحالة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($allData as $key =>$item)
                                
                                <tr>
                                    <td>{{( $key+1 )}}</td>
                                    <td>{{( $item->purchase_no )}}</td>
                                    <td>{{ date('d-m-Y',strtotime($item->date)) }}</td>
                                    <td>{{( $item['supplier']['name'] )}}</td>
                                    <td>{{( $item['category']['name'] )}}</td>
                                    <td>{{( $item->buying_qty )}}</td>
                                    <td>{{ $item['product']['name'] ?? 'N/A' }}</td>

                                    

                                    <td>
                                        @if($item->status == '0')
                                        <span class="btn btn-warning">معلقة</span>
                                        @elseif($item->status == '1')
                                        <span class="btn btn-success">تمت الموافقة</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($item->status == '0')
                                        <a href="{{route('purchase.delete',$item->id)}}" class="btn btn-danger sm" title="Delete Data" id="delete"><i class="fas fa-trash"></i></a>
                                        @endif
                                        <a href="{{route('purchase.deleteafterapprove', $item->purchase_no)}}" class="btn btn-danger sm" title="Delete Data" id="delete"><i class="fas fa-trash"></i></a>
                                      
                                            <a href="{{ route('purchase.details', $item->purchase_no) }}" class="btn btn-info sm" title="View Details"><i class="fas fa-info-circle"></i></a> <!-- زر عرض التفاصيل -->

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