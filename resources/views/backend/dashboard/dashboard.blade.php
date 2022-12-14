
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
                <h3 id="customer_balance">0.00</h3>
                <p><strong>Customer Balance</strong></p>
              </div>
              <div class="icon">
                <i class="fa fa-money-bill-wave"></i>
              </div>
              <a href="{{URL::to('/')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 id="supplier_balance">0.00</h3>
                <p><strong>Supplier Balance</strong></p>
              </div>
              <div class="icon">
                <i class="fa fa-money-bill-wave"></i>
              </div>
              <a href="{{URL::to('/')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 id="total_sale_amount">{{$total_supplier}}</h3>
                <p><strong>Total Sale</strong></p>
              </div>
              <div class="icon">
                <i class="fa fa-money-bill-wave"></i>
              </div>
              <a href="{{URL::to('/')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 id="total_buy_amount">0.00</h3>
                <p><strong>Total Purchase</strong></p>
              </div>
              <div class="icon">
                <i class="fa fa-money-bill-wave"></i>
              </div>
              <a href="{{URL::to('/')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 id="customer">0</h3>
                <p><strong>Total Customer</strong></p>
              </div>
              <div class="icon">
                <i class="fa fa-money-bill-wave"></i>
              </div>
              <a href="{{URL::to('/')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 id="supplier">0</h3>
                <p><strong>Total Supplier</strong></p>
              </div>
              <div class="icon">
                <i class="fa fa-money-bill-wave"></i>
              </div>
              <a href="{{URL::to('/')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 id="current_balance"></h3>
                <p><strong>Balance(Cash + Bank)</strong></p>
              </div>
              <div class="icon">
                <i class="fa fa-money-bill-wave"></i>
              </div>
              <a href="{{URL::to('/')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 id="total_bank"></h3>
                <p><strong>Banks</strong></p>
              </div>
              <div class="icon">
                <i class="fa fa-money-bill-wave"></i>
              </div>
              <a href="{{URL::to('/')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          {{-- table start --}}
            <div class="col-md-6 col-12">
              <div class="card">
                <div class="card-header border-0">
                  <h3 class="card-title">Top 5 Sale Product</h3>
                    <div class="card-tools">
                      <a href="#" class="btn btn-tool btn-sm">
                        <i class="fas fa-download"></i>
                      </a>
                      <a href="#" class="btn btn-tool btn-sm">
                        <i class="fas fa-bars"></i>
                      </a>
                  </div>
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-striped table-valign-middle">
                    <thead>
                      <tr>
                        <th>Product</th>
                        <th>Code</th>
                        <th>Quantity</th>
                      </tr>
                    </thead>
                    <tbody id='top_product'>
                    <tbody>
                  </table>
                </div>
              </div>
            </div>
            {{-- table end --}}
            {{--bank table start  --}}
            <div class="col-md-6 col-12">
              <div class="card">
                <div class="card-header border-0">
                  <h3 class="card-title">Bank Balance</h3>
                    <div class="card-tools">
                      <a href="#" class="btn btn-tool btn-sm">
                        <i class="fas fa-download"></i>
                      </a>
                      <a href="#" class="btn btn-tool btn-sm">
                        <i class="fas fa-bars"></i>
                      </a>
                  </div>
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-striped table-valign-middle">
                    <thead>
                      <tr>
                        <th>Bank</th>
                        <th>Code</th>
                        <th>Balance</th>
                      </tr>
                    </thead>
                    <tbody id='bank_table'>
                    <tbody>
                  </table>
                </div>
              </div>
            </div>
            {{-- table end --}}
          </div>
          {{--  --}}

          <!-- ./col -->
          <!-- ./col -->
        </div>
    </div><!-- /.container-fluid -->
  </section>
@endsection
@section('script')
@include('backend.dashboard.internal-assets.js.script');
@endsection

