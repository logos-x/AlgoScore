@extends('admin.layouts.app')

@section("content")
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Song</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="brands.html" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" method="POST" id="songForm" name="songForm">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Title">
                                </div>
                                <p></p>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="composer">Composer</label>
                                    <input type="text" name="composer" id="composer" class="form-control" placeholder="Composer">
                                </div>
                                <p></p>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="singer">Singer</label>
                                    <input type="text" name="singer" id="singer" class="form-control" placeholder="Singer">
                                </div>
                                <p></p>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Slug</label>
                                    <input readonly type="text" name="slug" id="slug" class="form-control" placeholder="Slug">
                                </div>
                                <p></p>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Action</option>
                                        <option value="0">Block</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary">Create</button>
                    <a href="brands.html" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section("customJS")
    <script>
        $("#songForm").submit(function(e){
            e.preventDefault();
            var element = $("#songForm");
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route("songs.store") }}',
                type: 'POST',
                data: element.serializeArray(),
                datType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"] == true) {
                        window.location.href="{{ route("songs.index") }}";

                        $("#title").removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html("");

                        $("#slug").removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html("");

                        $("#composer").removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html("");

                        $("#singer").removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html("");

                    } else {
                        var errors = response['errors'];
                        if (errors['title']) {
                            $("#title").addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback')
                                .html(errors['title']);
                        } else {
                            $("#title").removeClass('is-invalid')
                                .siblings('p').removeClass('invalid-feedback')
                                .html("");
                        }

                        if (errors['slug']) {
                            $("#slug").addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback')
                                .html(errors['slug']);
                        } else {
                            $("#slug").removeClass('is-invalid')
                                .siblings('p').removeClass('invalid-feedback')
                                .html("");
                        }

                        if (errors['composer']) {
                            $("#composer").addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback')
                                .html(errors['composer']);
                        } else {
                            $("#composer").removeClass('is-invalid')
                                .siblings('p').removeClass('invalid-feedback')
                                .html("");
                        }

                        if (errors['singer']) {
                            $("#singer").addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback')
                                .html(errors['singer']);
                        } else {
                            $("#singer").removeClass('is-invalid')
                                .siblings('p').removeClass('invalid-feedback')
                                .html("");
                        }
                    }



                }, error: function(jqxHR, exception){
                    console.log("Something went wrong");
                }
            })
        });

        $("#title, #composer").change(function () {
            let title = $("#title").val();
            let singer = $("#composer").val();

            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{ route("getSlug") }}',
                type: 'get',
                data: {title: title, singer: singer},
                datType: 'json',
                success: function (response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"] == true) {
                        $("#slug").val(response["slug"]);
                    }

                }
            });
        });
    </script>
@endsection
