@extends('admin.layout.master')
@section('content')
<!-- uploadify -->
<link href="{{ asset('assets') }}/plugins/Drag-And-Drop/dist/imageuploadify.min.css" rel="stylesheet" />

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Product</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Product</a></li>
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
                            <h3 class="card-title">Edit Product</h3>
                            <div class="float-right">
                                <a href="{{ route('product.index') }}" class="btn btn-dark">View Product</a>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('product.update',$data->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row px-4 py-2" style="margin-bottom: 0;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Product Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Enter Title" value="{{ $data->title }}">
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sku">Sku</label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" placeholder="Enter Sku" value="{{ $data->sku }}">
                                    @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row px-4" style="margin-bottom: 0;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Category</label>
                                    <select name="category_id" id="category_id" class="form-control custom-select @error('category_id') is-invalid @enderror" required>
                                        @if($category)
                                        <option value="" disabled selected>Select</option>
                                        @foreach($category as $v)
                                        <option value="{{ $v->id }}" {{ $data->category_id == $v->id ? 'selected' : '' }}>{{ $v->title }}</option>
                                        @endforeach
                                        @else
                                        <option value="" selected>Category not found</option>
                                        @endif
                                    </select>
                                    @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sub_category_id">Sub Category</label>
                                    <select id="sub_category_id" name="sub_category_id" class="form-control custom-select @error('sub_category_id') is-invalid @enderror" required>
                                        <option value="" disabled selected>Select</option>
                                    </select>
                                    @error('sub_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row px-4" style="margin-bottom: 0;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="brand_id">Brand</label>
                                    <select id="brand_id" name="brand_id" class="form-control custom-select @error('brand_id') is-invalid @enderror" required>
                                        @if($brand)
                                        <option value="" disabled selected>Select</option>
                                        @foreach($brand as $v)
                                        <option value="{{ $v->id }}" {{ $data->brand_id == $v->id ? 'selected' : '' }}>{{ $v->title }}</option>
                                        @endforeach
                                        @else
                                        <option value="" selected>Brand not found</option>
                                        @endif
                                    </select>
                                    @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" placeholder="Enter Price" value="{{ $data->price }}">
                                    @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row px-4" style="margin-bottom: 0;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="old_price">Old Price</label>
                                    <input type="text" class="form-control @error('old_price') is-invalid @enderror" id="old_price" name="old_price" placeholder="Enter Old Price" value="{{ $data->old_price }}">
                                    @error('old_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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

                        <div class="row px-4" style="margin-bottom: 0;">
                            <div class="card-body p-2">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Color</label>
                                            <select name="color[]" class="duallistbox" multiple="multiple">
                                            @foreach($color as $v)
                                                @php
                                                    $isSelected = in_array($v->id, $selectedColors) ? 'selected' : '';
                                                @endphp
                                                <option value="{{ $v->id }}" {{ $isSelected }}>{{ $v->title }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </div>
                        </div>

                        <div class="row px-4" style="margin-bottom: 0;">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="size">Size</label>
                                    <div>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Name
                                                    </th>
                                                    <th>
                                                        Price
                                                    </th>
                                                    <th>
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="appendSize">
                                                <tr>
                                                    <td><input type="text" name="size[1000][name]" class="form-control" placeholder="Enter Size" /></td>
                                                    <td><input type="text" name="size[1000][price]" class="form-control" placeholder="Enter Price" /></td>
                                                    <td>
                                                        <button type="button"  class="btn btn-primary btn-sm addSize">Add</button>
                                                    </td>
                                                </tr>
                                                @php
                                                $i = 1;
                                                @endphp
                                                @foreach($productSize as $v)
                                                <tr>
                                                    <td><input type="text" name="size[{{ $i }}][name]" class="form-control" value="{{ $v->name }}" placeholder="Enter Size" /></td>
                                                    <td><input type="text" name="size[{{ $i }}][price]" class="form-control" value="{{ $v->price }}" placeholder="Enter Price" /></td>
                                                    <td>
                                                        <button type="button"  class="btn btn-danger btn-sm deleteSize">Delete</button>
                                                    </td>
                                                </tr>
                                                @php
                                                $i++;
                                                @endphp
                                                @endforeach
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row px-4" style="margin-bottom: 0;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Short Description</label>
                                    <textarea type="text" class="form-control @error('short_description') is-invalid @enderror summernote" name="short_description" placeholder="Enter Short Description">{{ $data->short_description }}</textarea>
                                    @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row px-4" style="margin-bottom: 0;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea type="text" class="form-control @error('description') is-invalid @enderror summernote" name="description" placeholder="Enter Description">{{ $data->description }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row px-4" style="margin-bottom: 0;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Additional Information</label>
                                    <textarea type="text" class="form-control @error('additional_information') is-invalid @enderror summernote" name="additional_information" placeholder="Enter Additional Information">{{ $data->additional_information }}</textarea>
                                    @error('additional_information')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row px-4" style="margin-bottom: 0;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Shipping returns</label>
                                    <textarea type="text" class="form-control @error('shipping_returns') is-invalid @enderror summernote" name="shipping_returns" placeholder="Enter Shipping returns">{{ $data->shipping_returns }}</textarea>
                                    @error('shipping_returns')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row px-4">
                            <div class="col-12 mx-auto">
                                <h6 class="mb-0 text-uppercase">Image Uploadify</h6>
                                <hr/>
                                <div class="card">
                                    <div class="card-body">
                                        <input id="image-uploadify" name="image[]" type="file" accept="image/*" multiple>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-4">
                                <label for="">Old Images</label>
                                <div class="row sortable">
                                    @foreach($productImage as $image)
                                    <div class='col-md-2 border sortable-image imgDiv ui-state-default' id="{{ $image->id }}">
                                        <!-- <button type='button' class='btn btn-danger fas fa-times-circle'></button> -->
                                        <img src="{{ asset('upload/products/' . $image->image_name) }}" alt="{{ $image->image_name }}" height="100px" width="100px">
                                        <!-- add a virtical divider -->
                                        <button class="btn btn-danger btn-sm product_image" style="margin-left:16px;margin-right:auto;" type="button">Delete</button>
                                    </div>
                                    @endforeach
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

<!-- uploadify -->
<script src="{{ asset('assets') }}/plugins/Drag-And-Drop/dist/imageuploadify.min.js"></script>

<script>
    $(function() {
    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox();

    $('#image-uploadify').imageuploadify();

    var i = 1000; 
    $('body').delegate('.addSize', 'click', function() {
        i++;
        var html = '<tr>\n\
                        <td><input type="text" name="size['+i+'][name]" class="form-control" placeholder="Enter Size" /></td>\n\
                        <td><input type="text" name="size['+i+'][price]" class="form-control" placeholder="Enter Price" /></td>\n\
                        <td>\n\
                            <button type="button"  class="btn btn-danger btn-sm deleteSize">Delete</button>\n\
                        </td>\n\
                    </tr>';
        $('#appendSize').append(html);
    });

    $('body').on('click', '.product_image', function() {
    var src = $(this).prev('img').attr('src');
    var parentDiv = $(this).closest('.imgDiv');
        if(src != "") {
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/deleteProductImage") }}',
                data: {
                    image_name : src
                },
                success: function(response, status, xhr) {
                    if (xhr.status === 404) {
                        toastr.error('Image file not found on the server');
                    } else {
                        if (xhr.status == 200) {
                            toastr.success(response.message);
                            parentDiv.remove();
                        } else {
                            toastr.error('Unexpected error');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    alert('AJAX request failed with status: ' + status);
                }
            });
        } else {
            alert('No image found');
        }
    });

    function getSubCategory() {
        let category_id = $('#category_id').val(); 
        if (category_id != "") {
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/getSubCategoryByCatId") }}',
                data: {
                    category_id: category_id
                },
                success: function(response) {
                    $('#sub_category_id').html(response);
                    @if(isset($data->sub_category_id) && $data->sub_category_id > 0)
                    var subCategoryId = {{ json_encode($data->sub_category_id) }};
                    $('#sub_category_id').val(subCategoryId);
                    @endif
                }
            });
        } else {
            alert('Select Valid Category');
        }
    }

    $('body').delegate('.deleteSize', 'click', function() {
        $(this).parent().parent().remove();
    });

    $('body').delegate('#category_id', 'change', function() {
        let category_id = $('#category_id').val(); 
        if (category_id != "") {
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/getSubCategoryByCatId") }}',
                data: {
                    category_id: category_id
                },
                success: function(response) {
                    $('#sub_category_id').html(response);
                }
            });
        } else {
            alert('Select Valid Category');
        }
    });

    // summernote editor
    $('.summernote').summernote();

    @if(isset($data->sub_category_id) && $data->sub_category_id > 0)
    var cat_id = $('#category_id').val();
    if(cat_id != "")
    {
        getSubCategory();
       
    }
    @endif
});

$( function() {
    $( ".sortable" ).sortable({
        update : function(event,ui){
            var img_id = new Array();
            $('.sortable-image').each(function(){
                var id = $(this).attr('id');
                img_id.push(id);
            })
            
            $.ajax({
                type : 'POST',
                url : '{{ url("admin/productImageSortable") }}',
                data : {
                    img_id : img_id
                },
                dataType : "json",
                success : function(response, status, xhr)
                {
                    if(xhr.status == 200)
                    {
                        toastr.success(response.message)
                    }
                    else
                    {
                        toastr.error(response.message)
                    }
                },
                error : function(response, status, xhr)
                {
                    toastr.error(response.message)
                }
            })
        }
    });
  } );
  </script>

</script>

@endsection