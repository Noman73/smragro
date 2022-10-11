 <!-- Content Wrapper. Contains page content -->
 @extends('layouts.master')
 @section('link')
 <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  
 @endsection
 @section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Manage Product</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Product</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <div class="card ">
            <div class="card-header bg-dark">
              <div class="row">
                <div class="col-6">
                  <div class="card-title">Product </div>
                </div>
                <div class="col-6">
                  <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal" data-whatever="@mdo">Add New</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-sm text-center table-bordered" id="datatable">
                <thead>
                  <tr>
                    <th>SL.</th>
                    <th>Category</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
      </div><!-- /.container-fluid -->
      {{-- modal --}}
      <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <input type="hidden" id="id">
                <div class="row">
                    <div class="col-md-6">
                      <div class="col-md-10 mr-auto ml-auto">
                        <div class="form-group">
                          <label for="recipient-name" class="col-form-label">Brand/Company:</label>
                          <select id="brand" class="form-control"></select>
                          <div class="invalid-feedback" id="brand_msg">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-10 mr-auto ml-auto">
                        <div class="form-group">
                          <label for="recipient-name" class="col-form-label">Category:</label>
                          <select id="category" class="form-control">
                            <option value=""></option>
                          </select>
                          <div class="invalid-feedback" id="category_msg">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-10 mr-auto ml-auto">
                        <div class="form-group">
                          <label for="recipient-name" class="col-form-label">Product Name:</label>
                          <input type="text" class="form-control" id="name" placeholder="Enter Name">
                          <div class="invalid-feedback" id="name_msg">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-10 mr-auto ml-auto">
                        <div class="form-group">
                          <label for="recipient-name" class="col-form-label">Product Code:</label>
                          <input disabled type="text" class="form-control" id="product_code" placeholder="Enter Product Code">
                          <div class="invalid-feedback" id="product_code_msg">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-10 mr-auto ml-auto">
                        <div class="form-group">
                          <label for="recipient-name" class="col-form-label">Part ID:</label>
                          <input type="text" class="form-control" id="part_id" placeholder="Enter Part ID">
                          <div class="invalid-feedback" id="part_id_msg">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-10 mr-auto ml-auto d-none">
                        <div class="form-group">
                          <label for="recipient-name" class="col-form-label">Model No:</label>
                          <input type="text" class="form-control" id="model_no" placeholder="Enter Model No">
                          <div class="invalid-feedback" id="model_no_msg">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-10 mr-auto ml-auto d-none">
                        <div class="form-group">
                          <label for="recipient-name" class="col-form-label">Warranty:</label>
                          <input type="text" class="form-control" id="warranty" placeholder="Enter Warranty">
                          <div class="invalid-feedback" id="warranty_msg">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-10 mr-auto ml-auto">
                        <div class="form-group">
                          <label for="recipient-name" class="col-form-label">Unit Type:</label>
                          <select name="unit_type" id="unit_type" class="form-control">
                          </select>
                          <div class="invalid-feedback" id="unit_type_msg">
                          </div>
                        </div>
                      </div>
                  </div>
                  <div class="col-md-6 mt-2">
                    <div class="col-md-10 mr-auto ml-auto">
                      <label class="label-control d-block" for="">Product Types</label>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="sale">
                        <label class="form-check-label" for="sale">Sale</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="purchase">
                        <label class="form-check-label" for="sale">Purchase</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="production">
                        <label class="form-check-label" for="production">Production</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="combobox">
                        <label class="form-check-label" for="production">Combo</label>
                      </div>
                    </div>
                    <div class="col-md-10 mr-auto ml-auto d-none mt-4" id="product-table">
                      <table class="table table-sm text-center table-bordered">
                        <thead>
                          <tr>
                            <th width="50%">product</th>
                            <th width="30%">qantity</th>
                            <th width="20%">Action</th>
                          </tr>
                        </thead>
                        <tbody id="products">

                        </tbody>
                      </table>
                      <button class="btn btn-primary" onclick="event.preventDefault();addItem()">Add</button>
                    </div>
                    <div class="col-md-10 mr-auto ml-auto mt-1">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Sale Price:</label>
                        <input type="number" class="form-control" id="sale_price" placeholder="Enter Sale Price">
                        <div class="invalid-feedback" id="sale_price_msg">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-10 mr-auto ml-auto">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Buy Price:</label>
                        <input type="number" class="form-control" id="buy_price" placeholder="Enter Buy Price">
                        <div class="invalid-feedback" id="buy_price_msg">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-10 mr-auto ml-auto">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Re-Order Level:</label>
                        <input type="number" class="form-control" id="reorder_level" placeholder="Enter Re-Order Level">
                        <div class="invalid-feedback" id="reorder_level_msg">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-10 mr-auto ml-auto">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Image:</label>
                        <input type="file" class="form-control" id="image">
                        <div class="invalid-feedback" id="image_msg">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-10 mr-auto ml-auto">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Status:</label>
                        <select name="status" id="status" class="form-control">
                          <option value="1">Active</option>
                          <option value="0">Deactive</option>
                        </select>
                        <div class="invalid-feedback" id="status_msg">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="formRequest()">Save</button>
            </div>
          </div>
        </div>
      </div>
      {{-- endmodal --}}
    </section>
  @endsection

  @section('script')
  <script src="{{asset('storage/adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.0/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  @include('backend.product.internal-assets.js.script')
  @endsection