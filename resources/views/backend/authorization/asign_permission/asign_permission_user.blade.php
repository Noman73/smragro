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
                  <tr>
                    <td>{{$role->name}}</td>
                    <td>
                      {{-- tab start --}}
                      <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @foreach($permission as $perm)
                        <li class="nav-item font-weight-bold">
                          <a class="nav-link" id="{{strtolower(str_replace(" ","_",$perm->name.$role->id))}}-tab" data-toggle="tab" href="#{{strtolower(str_replace(" ","_",$perm->name.$role->id))}}" role="tab" aria-controls="{{strtolower(str_replace(" ","_",$perm->name.$role->id))}}"
                            aria-selected="true">{{$perm->name}}</a>
                        </li>
                        @endforeach
                      </ul>
                      <div class="tab-content" id="myTabContent">
                        @foreach($permission as $perm)
                        <div class="tab-pane fade " id="{{strtolower(str_replace(' ','_',$perm->name.$role->id))}}" role="tabpanel" aria-labelledby="{{strtolower(str_replace(' ','_',$perm->name.$role->id))}}-tab">
                          <div class="container m-4">
                          @foreach($perm->permission as $p)
                          <label for="">{{$p->name}}</label>
                            <input type="hidden" name="role[]" value="{{$role->name}}">
                            <input id="data{{$role->id.$p->id}}" type="checkbox" name="permissions[]" value="{{$p->name}}"><br/>
                          @endforeach
                          </div>
                        </div>
                        @endforeach
                      </div>
                      {{-- tab end --}}
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