@extends('admin.layout.master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Shipping Charge</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shipping_charge.index') }}">Shipping Charge</a></li>
                    <li class="breadcrumb-item active">Create</li>
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
                            <h3 class="card-name">Add New Shipping Charge</h3>
                            <div class="float-right">
                                <a href="{{ route('shipping_charge.index') }}" class="btn btn-dark">View Shipping</a>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('shipping_charge.store') }}" method="POST">
                        @csrf
                        <div class="row px-4 py-2" style="margin-bottom: 0;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Area Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter Name" value="{{ old('name') }}">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" placeholder="" value="{{ old('price') }}">
                                    @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row px-4 py-2" style="margin-bottom: 0;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputStatus">Status</label>
                                    <select id="inputStatus" name="status" class="form-control custom-select @error('status') is-invalid @enderror" required>
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
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

@section('script')
@endsection

@endsection