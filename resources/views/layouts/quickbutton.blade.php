

<li class='d-none'>
    <li class="nav-item mt-1 mr-1">
        <a class="btn btn-sm btn-primary" href="{{URL::to('admin/purchase')}}">Purchase</a>
    </li>
    <li class="nav-item mt-1 mr-1 ">
        <a class="btn btn-sm btn-primary" href="{{URL::to('admin/invoice')}}">Invoice</a>
    </li>
    <li class="nav-item mt-1 mr-1">
        <a class="btn btn-sm btn-primary" href="{{URL::to('admin/receive')}}">Receive</a>
    </li>
    <li class="nav-item mt-1 mr-1">
        <a class="btn btn-sm btn-primary" href="{{URL::to('admin/payment')}}">Payment</a>
    </li>
    <li class="nav-item mt-1 mr-1">
        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#calculator">
            <i class="fas fa-calculator"></i>
        </button>
    </li>
    <li class="nav-item mt-1 mr-1">
        <button type="button" class="btn btn-sm btn-primary"  id="micro">
            <i class="fas fa-microphone"></i>
        </button>
    </li>
    <li class="nav-item mt-2 mr-1">
        Current Balance : <span class="font-weight-bold text-danger" id='cash_plus_bank'></span>
    </li>
</li>