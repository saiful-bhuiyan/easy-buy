@extends('admin.layout.master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Product Color</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('color.index') }}">Product Color</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <!-- /.card -->

                <!-- /.card-header -->
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="flex">
                            <h3 class="card-title">Edit Color</h3>
                            <div class="float-right">
                                <a href="{{ route('color.index') }}" class="btn btn-dark">View Color</a>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('color.update',$data->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row px-4 py-2" style="margin-bottom: 0;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Color Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Enter Title" value="{{ $data->title }}">
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Color</label>
                                        <div class="input-group my-colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                            <input type="text" name="code" class="form-control" value="{{ $data->code }}" autocomplete="off" data-original-title="" title="">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fas fa-square"></i></span>
                                            </div>
                                        </div>
                                        <!-- /.input group -->
                                        @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row px-4 py-2" style="margin-bottom: 0;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputStatus">Status</label>
                                    <select id="inputStatus" name="status" class="form-control custom-select @error('status') is-invalid @enderror" required>
                                        <option value="1" {{ $data->status == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $data->status == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
    //color picker with addon
    $(function(){
        $('.my-colorpicker2').colorpicker()

        $('.my-colorpicker2').on('colorpickerChange', function(event) {
        $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
        })
    })
    
</script>

@endsection