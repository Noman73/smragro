<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    @php 
    $company=App\Models\CompanyInformations::first();
    @endphp
    <a href="{{URL::to('/home')}}" class="brand-link bg-light">
      <img src="{{asset('storage/icon/'.$company->icon)}}" alt="SMRAGRO" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text   font-weight-bold">{{isset($company->company_name) ? $company->company_name : 'Company Name'}}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      
      <!-- SidebarSearch Form -->
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{URL::to('/home')}}" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link">
              <i class="nav-icon fas fa-exchange-alt"></i>
              <p> 
                Transaction
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('Receive View')
              <li class="nav-item">
                <a href="{{URL::to('admin/receive')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Receive</p>
                </a>
              </li>
              @endcan
              @can('Employee Payment View')
              <li class="nav-item">
                <a href="{{URL::to('admin/employee-payment')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Employee Pay</p>
                </a>
              </li>
              @endcan
              @can('Payment View')
              <li class="nav-item">
                <a href="{{URL::to('admin/payment')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Payment</p>
                </a>
              </li>
              @endcan
              @can("Bank Transfer View")
              <li class="nav-item">
                <a href="{{URL::to('admin/fund_transfer')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Bank Transfer
                  </p>
                </a>
              </li>
              @endcan
              @can('Journal View')
              <li class="nav-item">
                <a href="{{URL::to('admin/accounts/journal')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Journal
                  </p>
                </a>
              </li>
              @endcan
              @can("Chart Of Account View")
              <li class="nav-item">
                <a href="{{route('chart-of-account.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Chart of Accounts
                  </p>
                </a>
              </li>
              @endcan
            </ul>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link">
              <i class="nav-icon fas fa-comment-dollar"></i>
              <p>
                Sales
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('Sale Invoice View')
              <li class="nav-item">
                <a href="{{URL::to('admin/invoice')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Invoice</p>
                </a>
              </li>
              @endcan
              @can('Customer Receive View')
              <li class="nav-item">
                <a href="{{URL::to('admin/c-receive')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Cus. Receive</p>
                </a>
              </li>
              @endcan
              @can('Sales Return View')
              <li class="nav-item">
                <a href="{{URL::to('admin/sales_return')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sales Return</p>
                </a>
              </li>
              @endcan
              @can('Sale Invoice List')
              <li class="nav-item">
                <a href="{{URL::to('admin/invoice-list')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Invoice List</p>
                </a>
              </li>
              @endcan
              @can("Condition Sale List")
              <li class="nav-item">
                <a href="{{URL::to('admin/condition-list')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Condition List
                  </p>
                </a>
              </li>
              @endcan
              @can('Regular Condition List')
              <li class="nav-item">
                <a href="{{URL::to('admin/regular-condition-list')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    R Condition List
                  </p>
                </a>
              </li>
              @endcan
              @can("Customer View")
              <li class="nav-item">
                <a href="{{URL::to('admin/customer')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Customer
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{URL::to('admin/customer-multiply')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Customer Muliply
                  </p>
                </a>
              </li>
              @endcan
              @can("Customer View")
              <li class="nav-item">
                <a href="{{URL::to('admin/market')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Market
                  </p>
                </a>
              </li>
              @endcan
              @can('Sale Pricing View')
              <li class="nav-item">
                <a href="{{URL::to('admin/make-price')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Sale Pricing
                  </p>
                </a>
              </li>
              @endcan
              @can('Credit Setup View')
              <li class="nav-item">
                <a href="{{URL::to('admin/credit-setup')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Credit Setup
                  </p>
                </a>
              </li>
              @endcan
              @can("Customer Statement View")
              <li class="nav-item">
                <a href="{{URL::to('/admin/customer-statement-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Customer Statement</p>
                </a>
              </li>
              @endcan
            </ul>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                Purchase
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('Purchase Invoice View')
              <li class="nav-item">
                <a href="{{URL::to('admin/purchase')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase Invoice</p>
                </a>
              </li>
              @endcan
              @can("Purchase Invoice List")
              <li class="nav-item">
                <a href="{{URL::to('admin/purchase-list')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase List</p>
                </a>
              </li>
              @endcan
              @can("Supplier Payment View")
              <li class="nav-item">
                <a href="{{URL::to('admin/s-payment')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sup. Payment</p>
                </a>
              </li>
              @endcan
              @can('Supplier View')
              <li class="nav-item">
                <a href="{{URL::to('admin/supplier')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Supplier
                  </p>
                </a>
              </li>
              @endcan
              @can('Supplier Statement')
              <li class="nav-item">
                <a href="{{URL::to('/admin/supplier-statement-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Supplier Statement</p>
                </a>
              </li>
              @endcan
            </ul>
          </li>
          {{-- setting  --}}
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link">
              <i style="font-size:15px;" class="fas fa-warehouse nav-icon"></i>
              <p>
                Inventory
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('Stock View')
              <li class="nav-item">
                <a href="{{URL::to('admin/stock')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock</p>
                </a>
              </li>
              @endcan
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Product
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @can('Product View')
                  <li class="nav-item">
                    <a href="{{route('product.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Add Product</p>
                    </a>
                  </li>
                  @endcan
                  @can('Category View')
                  <li class="nav-item">
                    <a href="{{route('category.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Category</p>
                    </a>
                  </li>
                  @endcan
                  @can('Brand View')
                  <li class="nav-item">
                    <a href="{{route('category.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Brand</p>
                    </a>
                  </li>
                  @endcan
                  @can('Model View')
                  <li class="nav-item">
                    <a href="{{route('category.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Model</p>
                    </a>
                  </li>
                  @endcan
                  @can('Unit View')
                  <li class="nav-item">
                    <a href="{{route('unit.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Unit</p>
                    </a>
                  </li>
                  @endcan
                </ul>
              </li>
            </ul>
          </li>
        {{-- hr --}}
        <li class="nav-item">
          <a href="javascript:void(0)" class="nav-link">
            <i style="font-size:15px;" class="fas fa-warehouse nav-icon"></i>
            <p>
              HR 
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @can('Employee View')
            <li class="nav-item">
              <a href="{{URL::to('admin/employee')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Employee</p>
              </a>
            </li>
            @endcan
            @can('Salary Setup View')
            <li class="nav-item">
              <a href="{{URL::to('admin/employee-salary')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Salary Setup</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        {{-- hr end --}}
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link">
              <i class="nav-icon fas fa-file-invoice"></i>
              <p>
                Reports
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('Trial Balance Report')
              <li class="nav-item">
                <a href="{{URL::to('admin/trial-balance')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Trial Balance</p>
                </a>
              </li>
              @endcan
              @can('Ledger Report')
              <li class="nav-item">
                <a href="{{URL::to('/admin/ledger-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ledger</p>
                </a>
              </li>
              @endcan
              @can("Customer List Report")
              <li class="nav-item">
                <a href="{{URL::to('/admin/customer-list')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Customer List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{URL::to('/admin/employee-list')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Employee List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{URL::to('/admin/employee-loan')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Employee Loan</p>
                </a>
              </li>
              @endcan
              @can("Supplier List Report")
              <li class="nav-item">
                <a href="{{URL::to('/admin/supplier-list')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Supplier List</p>
                </a>
              </li>
              @endcan
              @can('Payment Report')
              <li class="nav-item">
                <a href="{{URL::to('/admin/payment-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Payment</p>
                </a>
              </li>
              @endcan
              @can('Receive Report')
              <li class="nav-item">
                <a href="{{URL::to('/admin/receive-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Receive</p>
                </a>
              </li>
              @endcan
              @can('Sale Report')
              <li class="nav-item">
                <a href="{{URL::to('/admin/sales-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sales</p>
                </a>
              </li>
              @endcan
              @can("Purchase Report")
              <li class="nav-item">
                <a href="{{URL::to('/admin/purchase-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase</p>
                </a>
              </li>
              @endcan
              @can('Inventory Report')
              <li class="nav-item">
                <a href="{{URL::to('/admin/inventory-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inventory</p>
                </a>
              </li>
              @endcan
              @can('Bank Balance Report')
              <li class="nav-item">
                <a href="{{URL::to('/admin/bank-balance-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Bank Balance</p>
                </a>
              </li>
              @endcan
              @can('Cash In Hand Report')
              <li class="nav-item">
                <a href="{{URL::to('/admin/cash-in-hand-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Cash in Hand</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{URL::to('/admin/profit_loss')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Profit Loss</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{URL::to('/admin/user-wise-amount')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User Wise Amount</p>
                </a>
              </li>
              @endcan
              @can('Total Transaction Report')
              <li class="nav-item">
                <a href="{{URL::to('/admin/total-transaction-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Total Transaction</p>
                </a>
              </li>
              @endcan
              @can('Purchase Pricing Report')
              <li class="nav-item">
                <a href="{{URL::to('/admin/purchase-pricing-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase Pricing</p>
                </a>
              </li>
              @endcan
              @can('Sale Pricing Report')
              <li class="nav-item">
                <a href="{{URL::to('/admin/sale-pricing-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sale Pricing</p>
                </a>
              </li>
              @endcan
              @can('Item List Report')
              <li class="nav-item">
                <a href="{{URL::to('/admin/item-list-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Item List</p>
                </a>
              </li>
              @endcan
              @can("CWS Report")
              <li class="nav-item">
                <a href="{{URL::to('/admin/customer-wise-sale-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>CWS Report</p>
                </a>
              </li>
              @endcan
              @can('SWP')
              <li class="nav-item">
                <a href="{{URL::to('/admin/supplier-wise-purchase-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>SWP Report</p>
                </a>
              </li>
              @endcan
            </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Setting
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Banks & Accounts
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @can('Bank View')
                  <li class="nav-item">
                    <a href="{{URL::to('admin/bank')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Bank</p>
                    </a>
                  </li>
                  @endcan
                  @can('Account Group View')
                  <li class="nav-item">
                    <a href="{{route('group.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Account Group</p>
                    </a>
                  </li>
                  @endcan
                  @can("Account View")
                  <li class="nav-item">
                    <a href="{{route('account-ledger.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Acconts</p>
                    </a>
                  </li>
                  @endcan
                  @can("Sub Account View")
                  <li class="nav-item">
                    <a href="{{URL::to('admin/sub_ledger')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Sub Acconts</p>
                    </a>
                  </li>
                  @endcan
                  @can('Account Class View')
                  <li class="nav-item">
                    <a href="{{route('classes.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Account Class</p>
                    </a>
                  </li>
                  @endcan
                </ul>
              </li>
              @can("General Info View")
              <li class="nav-item">
                <a href="{{URL::to('admin/setting/general-info')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>General Info</p>
                </a>
              </li>
              @endcan
              @can('Invoice Note View')
              <li class="nav-item">
                <a href="{{URL::to('admin/multi_note')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Invoice Note</p>
                </a>
              </li>
              @endcan
              <li class="nav-item">
                <a href="{{URL::to('admin/sms_template')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sms Template</p>
                </a>
              </li>
              @can("Shipping Company View")
              <li class="nav-item">
                <a href="{{URL::to('admin/shipping-company')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Shipping Company</p>
                </a>
              </li>
              @endcan
              @role('Super-Admin')
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Permissions
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{URL::to('admin/roles')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Add Role</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{URL::to('admin/asign-permission-user')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>User Permission</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{URL::to('admin/asign-permission')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Role Permission</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{URL::to('admin/asign-role')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Asign Role</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{URL::to('admin/user')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Create User</p>
                    </a>
                  </li>
                </ul>
              </li>
              @endrole
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>