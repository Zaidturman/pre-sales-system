@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">صفحة اضافة فئات المصاريف</h4><br>

                        <form method="post" action="{{ route('expense.storecategory') }}" id="myForm">
                            @csrf


                            <div class="row mb-3 mt-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">اسم الفئة</label>
                                <div class="form-group col-sm-10">
                                    <input name="name" class="form-control" type="text" value="">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="description" class="col-sm-2 col-form-label">التفاصيل</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="description" id="description"></textarea>
                                </div>
                            </div>
                            <!-- end row -->


                            <input type="submit" class="btn btn-info waves-effect waves-light" value="اضافة فئة">

                        </form>

                    </div>
                </div>
            </div> <!-- end col -->
        </div>

    </div>
</div>



@endsection