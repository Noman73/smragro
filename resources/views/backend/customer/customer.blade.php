 <!-- Content Wrapper. Contains page content -->
 @extends('layouts.master')
 @section('link')
 <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
 <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <style type="text/css">
  .file {
      border: 1px solid #ccc;
      display: inline-block;
      width: 100px;
      cursor: pointer;
      background-color:green;
      color:white;
  }
  .file:hover{
    background-color:#fff000;
  }
  .image-upload{
    margin:0 auto;
  }

  
  </style>
 @endsection
 @section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Manage Customer</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Customer</li>
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
                  <div class="card-title">Customer </div>
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
                    <th>SL</th>
                    <th>Name</th>
                    <th>Adress</th>
                    <th>Mobile</th>
                    <th>Bank Name</th>
                    <th>Account No.</th>
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
              <h5 class="modal-title" id="exampleModalLabel">Add New Customer</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <input type="hidden" id="id">
                <div class="text-center">
                    <img id="imagex" src="{{asset('storage/admin-lte/dist/img/avatar5.png')}}" class="d-flex image-upload" style="height:100px;width:100px;">
                    <input class="d-none" type="file" id="file" name="" onchange="readURL(this)">
                    <label for="file"  class="file">Choose</label>
                    <div id="photo_msg" class="invalid-feedback">
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-8 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="recipient-name" class="col-form-label">Company Name:</label>
                      <input type="text" class="form-control" id="company_name" placeholder="Enter Company Name">
                      <div class="invalid-feedback" id="company_name_msg">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="recipient-name" class="col-form-label">Name:</label>
                      <input type="text" class="form-control" id="name" placeholder="Enter Name">
                      <div class="invalid-feedback" id="name_msg">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="recipient-email" class="col-form-label">Email:</label>
                      <input type="email" class="form-control" id="email" placeholder="Enter Email">
                      <div class="invalid-feedback" id="email_msg">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 mr-auto ml-auto"> 
                    <div class="form-group">
                      <label for="message-text" class="col-form-label">Adress:</label>
                      <input type="text" class="form-control" id="adress" placeholder="Enter Adress">
                      <div class="invalid-feedback" id="adress_msg">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="message-text" class="col-form-label">Phone:</label>
                      <input type="number" class="form-control" id="phone" placeholder="Enter Phone Number">
                      <div class="invalid-feedback" id="phone_msg">
                      </div>
                    </div>
                  </div>
                 
                  <div class="col-md-8 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="message-text" class="col-form-label">NID:</label>
                      <input type="number" class="form-control" id="nid" placeholder="Enter Phone Number">
                      <div class="invalid-feedback" id="nid_msg">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="message-text" class="col-form-label">Birth Date:</label>
                      <input type="text" class="form-control" id="birth_date" placeholder="Enter Phone Number">
                      <div class="invalid-feedback" id="birth_date_msg">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="message-text" class="col-form-label">phone 2:</label>
                      <input type="text" class="form-control" id="phone2" placeholder="Enter Phone Number">
                      <div class="invalid-feedback" id="phone2_msg">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="message-text" class="col-form-label">Bank Name:</label>
                      <input type="text" class="form-control" id="bank_name" placeholder="Enter Bank Name">
                      <div class="invalid-feedback" id="bank_name_msg">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="message-text" class="col-form-label">Bank Account No:</label>
                      <input type="text" class="form-control" id="bank_account_no" placeholder="Enter Bank Name">
                      <div class="invalid-feedback" id="bank_account_no_msg">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 mr-auto ml-auto">
                    <div class="form-group">
                      <label for="recipient-name" class="col-form-label">Comment:</label>
                      <textarea id="comment" rows="5" class='form-control' placeholder="Write Comment"></textarea>
                      <div class="invalid-feedback" id="comment_msg">
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
  @include('backend.customer.internal-assets.js.script')
  @endsection