
@extends('layouts.master')

@section('content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
          <div class="col-12">
            <div class="row">
              <div class="col-md-3 col-12">
                <div class="form-group">
                  <input type="text" class="form-control form-control-sm" id="fromDate" placeholder="From Date">
                </div>
              </div>
              <div class="col-md-3 col-12">
                <div class="form-group">
                  <input type="text" class="form-control form-control-sm" id="toDate" placeholder="To Date">
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 id="istovriti">45121</h3>
                <p><strong>Total Expence</strong></p>
              </div>
              <div class="icon">
                <i class="fa fa-money-bill-wave"></i>
              </div>
              <a href="{{URL::to('/')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <!-- ./col -->
        </div>
    </div><!-- /.container-fluid -->
  </section>
@endsection

