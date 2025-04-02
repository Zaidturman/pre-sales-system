@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">صفحة اضافة المصاريف</h4><br>

                        <form method="post" action="{{ route('expense.store') }}" id="myForm">
                            @csrf


                            <div class="row mb-3 mt-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">اسم المصروف</label>
                                <div class="form-group col-sm-10">
                                    <input name="name" class="form-control" type="text" value="">
                                </div>
                            </div>
                            <!-- end row -->

                            <div class="row mb-3 mt-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">التاريخ</label>
                                <div class="form-group col-sm-10">
                                    <input name="date" class="form-control" type="date" value="">
                                </div>
                            </div>
                         
                            <div class="row mb-3 mt-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">المبلغ</label>
                                <div class="form-group col-sm-10">
                                    <input name="amount" class="form-control" type="number" value="">
                                </div>
                            </div>
                            <!-- end row -->

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">اسم الفئة</label>
                                <div class="col-sm-10">
                                    <select name="category_id" class="form-select" aria-label="Default select example">
                                        <option selected="">اختر الفئة</option>
                                        @foreach($categories as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- end row -->
                            <div class="row mb-3 mt-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label"> ملاحظات</label>
                                <div class="form-group col-sm-10">
                                    <textarea name="notes" class="form-control" id="example-textarea-input" rows="3"></textarea>
                                </div>
                            </div>
                            <!-- end row -->


                            <input type="submit" class="btn btn-info waves-effect waves-light" value="اضافة مصروف">

                        </form>

                    </div>
                </div>
            </div> <!-- end col -->
        </div>

    </div>
</div>



@endsection