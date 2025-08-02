@extends('admin.admin_master')
@section('admin')
<style>
    .search-container {
        direction: rtl;
        font-family: 'Tajawal', Arial, sans-serif;
        max-width: 600px;
        margin: 20px auto;
        padding: 0 15px;
    }
    
    .search-form {
        width: 100%;
    }
    
    .search-input-group {
        display: flex;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 30px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .search-input-group:focus-within {
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
    }
    
    .search-input {
        flex: 1;
        padding: 12px 20px;
        border: none;
        outline: none;
        font-size: 16px;
        background: #f8f9fa;
        text-align: right;
    }
    
    .search-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0 20px;
        background: #4e73df;
        color: white;
        border: none;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        transition: background 0.3s;
    }
    
    .search-button:hover {
        background: #3a5bc7;
    }
    
    .search-icon {
        margin-right: -4px;
    }
    
    @media (max-width: 768px) {
        .search-input {
            padding: 10px 15px;
            font-size: 14px;
        }
        
        .search-button {
            padding: 0 15px;
            font-size: 14px;
        }
    }
    .reset-button {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 15px;
        background: #f8f9fa;
        color: #6c757d;
        border: none;
        cursor: pointer;
        font-size: 16px;
        text-decoration: none;
        transition: all 0.3s;
        border-right: 1px solid #e9ecef;
    }
    
    .reset-button:hover {
        background: #e9ecef;
        color: #495057;
    }
</style>
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
                            <div class="search-container">
    <form method="GET" action="{{ route('customer.search') }}" class="search-form">
        <div class="search-input-group">
            <input 
                type="text" 
                name="address" 
                placeholder="ابحث حسب العنوان..." 
                class="search-input"
                aria-label="بحث حسب العنوان"
            >
            <button type="submit" class="search-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="search-icon">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg>
                <span>بحث</span>
            </button>
            <a href="{{ route('customer.all') }}" class="reset-button">
                إعادة تعيين
            </a>
        </div>
    </form>
</div>
                        <br><br>

                        <h4 class="card-title">بيانات جميع الزبائن</h4><br>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم </th>
                                    <th>صورة الزبون</th>
                                    <th>الايميل</th>
                                    <th>العنوان</th>
                                    <th>العمليات </th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($customers as $key =>$item)
                                <tr>
                                    <td>{{( $key+1 )}}</td>
                                    <td>{{( $item->name )}}</td>
                                    <td><img src="{{asset($item->customer_image)}}" style="width:60px;height:50px;"></td>
                                    <td>{{( $item->email )}}</td>
                                    <td>{{( $item->address )}}</td>
                                    <td>
                                        <a href="{{route('customer.edit',$item->id)}}" class="btn btn-info sm" title="Edit Data"><i class="fas fa-edit"></i></a>

                                        <a href="{{route('customer.delete',$item->id)}}" class="btn btn-danger sm" title="Delete Data" id="delete"><i class="fas fa-trash"></i></a>
                                        <a href="{{route('customer.view',$item->id)}}" class="btn btn-primary sm" title="Customer Invoice Details"><i class="fas fa-eye"></i></a>
                                        <a href="{{route('partialpayments.create',$item->id)}}" class="btn btn-success sm" title="Customer pind Details"><i class="fas fa-plus"></i>
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

@endsection