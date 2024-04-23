@extends('admin.layout.master')
@section('content')
<style>
    table thead tr,
    table tbody tr{
        padding: 0;
        margin: 0;
    }
</style>
<!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Order List</li>
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

                    <div class="card">
                        <div class="card-header">
                            <!-- <div class="float-right">
                                <a href="{{ route('product.create') }}" class="btn btn-primary">Create Product</a>
                            </div> -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="example1" class="table data-table table-bordered table-striped">
                                <thead class="p-0">
                                    <tr>
                                        <th>#</th>
                                        <th>Order Date</th>
                                        <th>First Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Total Amount</th>
                                        <th>Payment Method</th>
                                        <th>Payment Status</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="p-0">
                                  
                                </tbody>
                            </table>
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

<script type="text/javascript">
  $(function () {
      
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('admin/order_list') }}",
        columns: [
            {data: 'sl', name: 'sl'},
            {data: 'order_date', name: 'order_date'},
            {data: 'first_name', name: 'first_name'},
            {data: 'phone', name: 'phone'},
            {data: 'email', name: 'email'},
            {data: 'total_amount', name: 'total_amount'},
            {data: 'payment_method', name: 'payment_method'},
            {data: 'is_payment', name: 'is_payment'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
      
  });
</script>

@endsection