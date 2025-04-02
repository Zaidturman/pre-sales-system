@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>


<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title justify-end">تعديل صفحة المستخدم</h4>

                        <form method="post" action="{{ route('store.profile') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3 mt-3">
                                <div class="col-sm-10">
                                    <input name="name" class="form-control" type="text" value="{{ $editData->name }}" id="example-text-input">
                                </div>
                                <label for="example-text-input" class="col-sm-2 col-form-label">الاسم</label>

                            </div>
                            <!-- end row -->

                            <div class="row mb-3 mt-3">
                                <div class="col-sm-10">
                                    <input name="email" class="form-control" type="email" value="{{ $editData->email }}" id="example-text-input">
                                </div>
                                <label for="example-text-input" class="col-sm-2 col-form-label">الايميل</label>

                            </div>
                            <!-- end row -->

                            <div class="row mb-3 mt-3">
                                <div class="col-sm-10">
                                    <input name="username" class="form-control" type="text" value="{{ $editData->username }}" id="example-text-input">
                                </div>
                                <label for="example-text-input" class="col-sm-2 col-form-label">اسم المستخدم</label>

                            </div>
                            <!-- end row -->

                            <div class="row mb-3 mt-3">
                                <div class="col-sm-10">
                                    <input name="profile_image" class="form-control" type="file" id="image">
                                </div>
                                <label for="example-text-input" class="col-sm-2 col-form-label">صورة </label>
                            </div>
                            <!-- end row -->

                            <div class="row mb-3 mt-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <img id="showImage" class="rounded avatar-lg" alt="200x200" src="{{ (!empty($editData->profile_image))? url('upload/admin_images/'.$editData->profile_image):url('upload/no_image.jpg') }}" alt="Card image cap">
                                </div>
                            </div>
                            <!-- end row -->

                            <input type="submit" class="btn btn-info waves-effect waves-light" value="تعديل الصفحة">

                        </form>

                    </div>
                </div>
            </div> <!-- end col -->
        </div>

    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $('#image').change(function(e) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#showImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
</script>

@endsection