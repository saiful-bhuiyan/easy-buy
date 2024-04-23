@extends('admin.layout.master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Product Cuppon</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cuppon.index') }}">Product Cuppon</a></li>
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
                            <h3 class="card-title">Edit Cuppon</h3>
                            <div class="float-right">
                                <a href="{{ route('cuppon.index') }}" class="btn btn-dark">View Cuppon</a>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('cuppon.update',$data->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row px-4 py-2" style="margin-bottom: 0;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Cuppon Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter Name" value="{{ $data->name }}">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select id="type" name="type" class="form-control custom-select @error('type') is-invalid @enderror" required>
                                        <option value="Amount" {{ $data->type == 'Amount' ? 'selected' : '' }}>Amount</option>
                                        <option value="Percent" {{ $data->type == 'Percent' ? 'selected' : '' }}>Percent</option>
                                    </select>
                                    @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row px-4 py-2" style="margin-bottom: 0;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Percent / Amount</label>
                                    <input type="text" class="form-control @error('percent_amount') is-invalid @enderror" id="percent_amount" name="percent_amount" placeholder="" value="{{ $data->percent_amount }}">
                                    @error('percent_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date and time:</label>
                                    <input type="datetime-local" class="form-control @error('expire_date') is-invalid @enderror" name="expire_date" value="{{ $data->expire_date }}" placeholder="enter expire date">
                                    @error('expire_date')
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

@endsection