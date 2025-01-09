<div class="app-sidebar__overlay" data-toggle="sidebar"></div>

<aside class="app-sidebar">
    <div class="app-sidebar__user"><img width="40 px" class="app-sidebar__user-avatar" src="{{ asset('images/user/'.Auth::user()->image) }}" alt="User Image">

        <div>
            <p class="app-sidebar__user-name">{{ Auth::user()->fullname }}</p>
        </div>
    </div>
    
    <ul class="app-menu">
    @if(Auth::user()->role == 'admin')

        <li><a class="app-menu__item {{ request()->is('/') ? 'active' : ''}}" href="{{route('adminhome')}}"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>        
        <!-- <li><a class="app-menu__item {{ request()->is('/report') ? 'active' : ''}}"  ><i class="app-menu__icon fa fa-file-text-o"></i><span class="app-menu__label">Reports</span></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('x_report')}}"><i class="icon fa fa-circle-o"></i>X-Report</a></li>
                <li><a class="treeview-item" href="{{route('z_report')}}"><i class="icon fa fa-circle-o"></i>Z-Report</a></li>
            </ul>
        </li>       -->  

        <li ><a class="app-menu__item {{ request()->is('customer*') ? 'active' : ''}}" href="{{route('customer.index')}}" ><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Customer</span></a>
            <!-- <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('customer.create')}}"><i class="icon fa fa-circle-o"></i> Add Customer</a></li>
                <li><a class="treeview-item" href="{{route('customer.index')}}"><i class="icon fa fa-circle-o"></i> Manage Customers</a></li>
            </ul> -->
        </li>

        <li><a class="app-menu__item {{ request()->is('product*') ? 'active' : ''}}" href="{{route('product.index')}}" ><i class="app-menu__icon fa fa-cube"></i><span class="app-menu__label">Product</span></a>
            <!-- <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('product.create')}}"><i class="icon fa fa-circle-o"></i> New Product</a></li>
                <li><a class="treeview-item" href="{{route('product.index')}}"><i class="icon fa fa-circle-o"></i> Manage Products</a></li>
            </ul> -->
        </li>

        <li><a class="app-menu__item {{ request()->is('sales') ? 'active' : ''}}" href="{{route('sales')}}"><i class="app-menu__icon fa fa-dollar"></i><span class="app-menu__label">View Sales</span></a></li>

        <li ><a class="app-menu__item {{ request()->is('supplier*') ? 'active' : ''}}" href="{{route('supplier.index')}}" ><i class="app-menu__icon fa fa-truck"></i><span class="app-menu__label">Supplier</span></a>
            <!-- <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('supplier.create')}}"><i class="icon fa fa-circle-o"></i> Add Supplier</a></li>
                <li><a class="treeview-item" href="{{route('supplier.index')}}"><i class="icon fa fa-circle-o"></i> Manage Suppliers</a></li>
            </ul> -->
        </li>

        <li class="treeview"><a class="app-menu__item {{ request()->is('/report') ? 'active' : ''}}" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-file-text-o"></i><span class="app-menu__label">Reports</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
              <li><a class="treeview-item" href="{{route('x_report')}}"><i class="icon fa fa-circle-o"></i>X-Report</a></li>
              <li><a class="treeview-item" href="{{route('overall_report')}}"><i class="icon fa fa-circle-o"></i>Overall-Report</a></li>
            </ul>
        </li>

        <li><a class="app-menu__item {{ request()->is('bt_list') ? 'active' : ''}}" href="{{route('bt_list.index')}}"><i class="app-menu__icon fa fa-dollar"></i><span class="app-menu__label">Bank Transfer List</span></a></li>

        <li><a class="app-menu__item {{ request()->is('tax*') ? 'active' : ''}}" href="{{route('tax.index')}}"><i class="app-menu__icon fa fa-percent"></i><span class="app-menu__label">Tax</span></a>
           <!--  <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('tax.create')}}"><i class="icon fa fa-circle-o"></i> Add Tax</a></li>
                <li><a class="treeview-item" href="{{route('tax.index')}}"><i class="icon fa fa-circle-o"></i> Manage Tax</a></li>
             </ul> -->
        </li>

        <li><a class="app-menu__item {{ request()->is('category*') ? 'active' : ''}}" href="{{route('category.index')}}"><i class="app-menu__icon fa fa-th"></i><span class="app-menu__label">Category</span></a>
            <!-- <ul class="treeview-menu">
                <li><a class="treeview-item " href="{{route('category.create')}}"><i class="icon fa fa-plus"></i>Create Category</a></li>
                <li><a class="treeview-item" href="{{route('category.index')}}"><i class="icon fa fa-edit"></i>Manage Categories</a></li>
            </ul> -->
        </li>

        <li><a class="app-menu__item {{ request()->is('subcategory*') ? 'active' : ''}}" href="{{route('subcategory.index')}}" ><i class="app-menu__icon fa fa-th"></i><span class="app-menu__label">Subcategory</span></a>
            <!-- <ul class="treeview-menu">
                <li><a class="treeview-item " href="{{route('subcategory.create')}}"><i class="icon fa fa-plus"></i>Create Subcategory</a></li>
                <li><a class="treeview-item" href="{{route('subcategory.index')}}"><i class="icon fa fa-edit"></i>Manage Subcategories</a></li>
            </ul> -->
        </li>

        

        <li ><a class="app-menu__item {{ request()->is('unit*') ? 'active' : ''}}" href="{{route('unit.index')}}" ><i class="app-menu__icon fa fa-bars"></i><span class="app-menu__label">Unit</span></a>
            <!-- <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('unit.create')}}"><i class="icon fa fa-circle-o"></i> Add Unit</a></li>
                <li><a class="treeview-item" href="{{route('unit.index')}}"><i class="icon fa fa-circle-o"></i> Manage Units</a></li>
            </ul> -->
        </li>

        <li ><a class="app-menu__item {{ request()->is('rate*') ? 'active' : ''}}" href="{{route('rate.index')}}" ><i class="app-menu__icon fa fa-dollar"></i><span class="app-menu__label">Rate</span></a>
            <!-- <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('rate.create')}}"><i class="icon fa fa-circle-o"></i> Add Rate</a></li>
                <li><a class="treeview-item" href="{{route('rate.index')}}"><i class="icon fa fa-circle-o"></i> Manage Rates</a></li>
            </ul> -->
        </li>


        

        <!-- <li><a class="app-menu__item {{ request()->is('purchase*') ? 'active' : ''}}" href="#" ><i class="app-menu__icon fa fa-exchange"></i><span class="app-menu__label">Purchase</span></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item " href="{{route('purchase.create')}}"><i class="icon fa fa-plus"></i>Purchase Product </a></li>
                <li><a class="treeview-item" href="{{route('purchase.index')}}"><i class="icon fa fa-edit"></i>Manage Purchase</a></li>
            </ul>
        </li> -->

        <!-- <li ><a class="app-menu__item {{ request()->is('customer*') ? 'active' : ''}}" href="{{route('customer.index')}}" ><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Customer</span></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('customer.create')}}"><i class="icon fa fa-circle-o"></i> Add Customer</a></li>
                <li><a class="treeview-item" href="{{route('customer.index')}}"><i class="icon fa fa-circle-o"></i> Manage Customers</a></li>
            </ul> 
        </li> -->

        <li ><a class="app-menu__item {{ request()->is('vehicle*') ? 'active' : ''}}" href="{{route('vehicle.index')}}" ><i class="app-menu__icon fa fa-car"></i><span class="app-menu__label">Vehice</span></a>
            <!-- <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('vehicle.create')}}"><i class="icon fa fa-circle-o"></i> Add Vehicle</a></li>
                <li><a class="treeview-item" href="{{route('vehicle.index')}}"><i class="icon fa fa-circle-o"></i> Manage Vehicles</a></li>
            </ul> -->
        </li>

        <li ><a class="app-menu__item {{ request()->is('route*') ? 'active' : ''}}" href="{{route('route.index')}}" ><i class="app-menu__icon fa fa-road"></i><span class="app-menu__label">Route</span></a>
            <!-- <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('route.create')}}"><i class="icon fa fa-circle-o"></i> Add Route</a></li>
                <li><a class="treeview-item" href="{{route('route.index')}}"><i class="icon fa fa-circle-o"></i> Manage Routes</a></li>
            </ul> -->
        </li>

        <li ><a class="app-menu__item {{ request()->is('price*') ? 'active' : ''}}" href="{{route('price.index')}}" ><i class="app-menu__icon fa fa-money"></i><span class="app-menu__label">Price</span></a>
            <!-- <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('price.create')}}"><i class="icon fa fa-circle-o"></i> Add Price</a></li>
                <li><a class="treeview-item" href="{{route('price.index')}}"><i class="icon fa fa-circle-o"></i> Manage Prices</a></li>
            </ul> -->
        </li>

        <li ><a class="app-menu__item {{ request()->is('company*') ? 'active' : ''}}" href="{{route('company.index')}}" ><i class="app-menu__icon fa fa-building"></i><span class="app-menu__label">Company</span></a>
            <!-- <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('company.create')}}"><i class="icon fa fa-circle-o"></i> Add Company</a></li>
                <li><a class="treeview-item" href="{{route('company.index')}}"><i class="icon fa fa-circle-o"></i> Manage Companies</a></li>
            </ul> -->
        </li>
        @endif
        
        
        <li><a class="app-menu__item {{ request()->is('invoice*') ? 'active' : ''}}" href="{{route('invoice.index')}}" ><i class="app-menu__icon fa fa-file"></i><span class="app-menu__label">Receipt</span></a>
            <!-- <ul class="treeview-menu">
                <li><a class="treeview-item " href="{{route('invoice.create')}}"><i class="icon fa fa-plus"></i>Create Invoice </a></li>
                <li><a class="treeview-item" href="{{route('invoice.index')}}"><i class="icon fa fa-edit"></i>Manage Invoices</a></li>
            </ul> -->
        </li>

        <li><a class="app-menu__item {{ request()->is('stockintransit*') ? 'active' : ''}}" href="{{route('stockintransit.index')}}"><i class="app-menu__icon fa fa-archive"></i><span class="app-menu__label">Stock In Transit</span></a>
            <!-- <ul class="treeview-menu">
                <li><a class="treeview-item" href="{{route('stockintransit.create')}}"><i class="icon fa fa-circle-o"></i> Add Stock In Transit</a></li>
                <li><a class="treeview-item" href="{{route('stockintransit.index')}}"><i class="icon fa fa-circle-o"></i> Manage Stock In Transits</a></li>
            </ul> -->
        </li>
        @if(Auth::user()->role != 'admin')
        <li ><a class="app-menu__item {{ request()->is('/report') ? 'active' : ''}}" href="{{route('x_report')}}"><i class="app-menu__icon fa fa-file-text-o"></i><span class="app-menu__label">X-Report</span></a>
            <!-- <ul class="treeview-menu">
              <li><a class="treeview-item" href="{{route('x_report')}}"><i class="icon fa fa-circle-o"></i>X-Report</a></li>
            </ul> -->
        </li>
        @endif

    </ul>
</aside>
