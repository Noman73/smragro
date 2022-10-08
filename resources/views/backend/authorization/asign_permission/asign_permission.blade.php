 <!-- Content Wrapper. Contains page content -->
 @extends('layouts.master')
 @section('link')
 <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <style>
       /* Vertical Tabs */
       .vertical-tabs{font-size:14px;padding:10px;color:#008000;}
        .vertical-tabs .nav-tabs .nav-link{background:#4CAF50;border:1px solid transparent;color:#fff;height:37px}
        .vertical-tabs .nav-tabs .nav-link.active{background-color:#009900!important;border-color:transparent !important;color:#fff;}
        .vertical-tabs .nav-tabs .nav-link{border:1px solid transparent;border-top-left-radius:0rem!important;}
        .vertical-tabs .tab-content>.active{background:#fff;display:block;}
        .vertical-tabs .nav.nav-tabs{border-bottom:0;border-right:1px solid transparent;display:block;float:left;margin-right:20px;padding-right:15px;}
        .vertical-tabs div.tab-content{height:300px;display:flex;overflow:scroll;align-items: center;justify-content: center;}
        .vertical-tabs .sv-tab-panel{background:#fff;height:145px;padding-top:10px;}
        .vertical-tabs div#home-v.tab-pane .sv-tab-panel{background:#a6dba6}
        .vertical-tabs div#profile-v.tab-pane .sv-tab-panel{background:#99d699;}
        .vertical-tabs div#messages-v.tab-pane .sv-tab-panel{background:#8cd18c}
        .vertical-tabs div#settings-v.tab-pane .sv-tab-panel{background:#80cc80}
       
        /* Vertical Tabs */
  </style>
 @endsection
 @section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Permission Asign</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Permission Asign</li>
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
                  <div class="card-title">Permission Asign </div>
                </div>
                <div class="col-6">
                </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-sm text-center table-bordered">
                <thead>
                  <tr>
                    <th>Role</th>
                    <th>Permissions</th>
                  </tr>
                </thead>
                <tbody>
                  @php 
                  $roles=App\Models\Role::all();
                  $i=0;
                  @endphp
                  @foreach($roles as $role)
                  <tr class="clearfix">
                    <td>{{$role->name}}</td>
                    <td class="">
                      {{-- vertical tab --}}
                      <div class="vertical-tabs">
                        <ul class="nav nav-tabs" role="tablist">
                          @foreach($permission as $perm)
                           <li class="nav-item">
                              <a class="nav-link" data-toggle="tab" href="#{{strtolower(str_replace(" ","_",$perm->name.$role->id))}}-v" role="tab" aria-controls="{{strtolower(str_replace(" ","_",$perm->name.$role->id))}}">{{$perm->name}}</a>
                          </li>
                          @endforeach
                        </ul>
                        <div class="tab-content">
                          @foreach($permission as $perm)
                            <div class="tab-pane" id="{{strtolower(str_replace(" ","_",$perm->name.$role->id))}}-v" role="tabpanel">
                                <div class="sv-tab-panel">
                                
                                  @foreach($perm->permission as $p)
                                  <div class="text-center d-block">
                                    <input style="accent-color:green;" id="data{{$role->id.$p->id}}" type="checkbox" name="permissions[]" value="{{$p->name}}">
                                    <label for="">{{$p->name}}</label><br/>
                                    <input type="hidden" name="role[]" value="{{$role->name}}">
                                  </div>
                                  @endforeach
                                </div>
                                
                            </div>
                           @endforeach
                        </div>
                     </div>
                     {{-- end tab --}}
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              <button class="btn  btn-primary mt-3 float-right" onclick="formRequest()">Save</button>
            </div>
          </div>
      </div><!-- /.container-fluid -->
    </section>
  @endsection

  @section('script')
  <script src="{{asset('storage/adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
  <script src="{{asset('storage/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.0/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  @include('backend.authorization.asign_permission.internal-assets.js.script')
  @endsection