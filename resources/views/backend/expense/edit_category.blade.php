@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">تعديل الفئة</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">تعديل بيانات الفئة</h4><br>

                        <form method="post" action="{{ route('expense.updatecategory', $category->id) }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">الاسم</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="name" value="{{ $category->name }}" id="name">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="description" class="col-sm-2 col-form-label">التفاصيل</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="description" id="description">{{ $category->description }}</textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary waves-effect waves-light">تحديث الفئة</button>
                        </form>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

@endsection
