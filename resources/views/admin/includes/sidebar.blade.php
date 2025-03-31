<!-- Sidebar -->
<div class="sidebar" id="sidebar">
	<div class="sidebar-inner slimscroll">
		<div id="sidebar-menu" class="sidebar-menu">

			<ul>
				{{-- <li class="menu-title">
					<span>Main</span>
				</li> --}}
				<li class="{{ route_is('dashboard') ? 'active' : '' }}">
					<a href="{{route('dashboard')}}"><i class="fe fe-home"></i> <span>Dashboard</span></a>
				</li>

				@can('view-category')
				<li class="{{ route_is('categories.*') ? 'active' : '' }}">
					<a href="{{route('categories.index')}}"><i class="fe fe-layout"></i> <span>Categories</span></a>
				</li>
				@endcan

				@can('view-products')
				<li class="submenu">
					<a href="#"><i class="fe fe-document"></i> <span> Products</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is(('products.*')) ? 'active' : '' }}" href="{{route('products.index')}}">Products</a></li>
						@can('create-product')<li><a class="{{ route_is('products.create') ? 'active' : '' }}" href="{{route('products.create')}}">Add Product</a></li>@endcan
						@can('view-outstock-products')<li><a class="{{ route_is('outstock') ? 'active' : '' }}" href="{{route('outstock')}}">Out-Stock</a></li>@endcan
						@can('view-expired-products')<li><a class="{{ route_is('expired') ? 'active' : '' }}" href="{{route('expired')}}">Expired</a></li>@endcan
					</ul>
				</li>
				@endcan

				@can('view-purchase')
				<li class="submenu">
					<a href="#"><i class="fe fe-star-o"></i> <span> Purchase</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is('purchases.*') ? 'active' : '' }}" href="{{route('purchases.index')}}">Purchase</a></li>
						@can('create-purchase')
						<li><a class="{{ route_is('purchases.create') ? 'active' : '' }}" href="{{route('purchases.create')}}">Add Purchase</a></li>
						@endcan
					</ul>
				</li>
				@endcan
				@can('view-sales')
				<li class="submenu">
					<a href="#"><i class="fe fe-activity"></i> <span> Sale</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is('sales.*') ? 'active' : '' }}" href="{{route('sales.index')}}">Sales</a></li>
						@can('create-sale')
						<li><a class="{{ route_is('sales.create') ? 'active' : '' }}" href="{{route('sales.create')}}">Add Sale</a></li>
						@endcan
					</ul>
				</li>
				@endcan
                <li class="submenu">
					<a href="#"><i class="fe fe-beginner"></i> <span> Patients</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is('patients.*') ? 'active' : '' }}" href="{{route('patients.index')}}">Patients</a></li>

						<li><a class="{{ route_is('patients.create') ? 'active' : '' }}" href="{{route('patients.create')}}">Ajout Patient</a></li>

					</ul>
				</li>
                <li class="submenu">
					<a href="#"><i class="fe fe-book"></i> <span> Docteurs</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is('doctors.*') ? 'active' : '' }}" href="{{route('doctors.index')}}">Docteurs</a></li>

						<li><a class="{{ route_is('doctors.create') ? 'active' : '' }}" href="{{route('doctors.create')}}">Ajout Docteur</a></li>

					</ul>
				</li>
                <li class="submenu">
					<a href="#"><i class="fe fe-fork"></i> <span>Type d'Examens</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is('exam-types.*') ? 'active' : '' }}" href="{{route('exam-types.index')}}">Examens</a></li>

						<li><a class="{{ route_is('exam-types.create') ? 'active' : '' }}" href="{{route('exam-types.create')}}">Ajout Examen</a></li>

					</ul>
				</li>
                <li class="submenu">
					<a href="#"><i class="fe fe-add-cart"></i> <span> Facturation</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is('invoices.*') ? 'active' : '' }}" href="{{route('invoices.index')}}">Facture</a></li>

					</ul>
				</li>
                <li class="submenu">
					<a href="#"><i class="fe fe-align-bottom"></i> <span> Gestion de compte</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is('finances.*') ? 'active' : '' }}" href="{{route('finances.index')}}">Caisse</a></li>
						<li><a class="{{ route_is('finances.create') ? 'active' : '' }}" href="{{route('finances.create')}}">Ajout</a></li>

					</ul>
				</li>
				@can('view-supplier')
				<li class="submenu">
					<a href="#"><i class="fe fe-user"></i> <span> Supplier</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is('suppliers.*') ? 'active' : '' }}" href="{{route('suppliers.index')}}">Supplier</a></li>
						@can('create-supplier')<li><a class="{{ route_is('suppliers.create') ? 'active' : '' }}" href="{{route('suppliers.create')}}">Add Supplier</a></li>@endcan
					</ul>
				</li>
				@endcan

                @can('view-service')
				<li class="submenu">
					<a href="#"><i class="fe fe-building"></i> <span> Département</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is('service.*') ? 'active' : '' }}" href="{{route('service.index')}}">Services</a></li>
						@can('create-service')<li><a class="{{ route_is('service.create') ? 'active' : '' }}" href="{{route('service.create')}}">Ajout un Service</a></li>@endcan
						<li><a class="{{ route_is('departments.*') ? 'active' : '' }}" href="{{route('departments.index')}}">departments</a></li>

					</ul>
				</li>
				@endcan

				@can('view-reports')
				<li class="submenu">
					<a href="#"><i class="fe fe-document"></i> <span> Reports</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is('sales.report') ? 'active' : '' }}" href="{{route('sales.report')}}">Sale Report</a></li>
						<li><a class="{{ route_is('purchases.report') ? 'active' : '' }}" href="{{route('purchases.report')}}">Purchase Report</a></li>
					</ul>
				</li>
				@endcan

				@can('view-access-control')
				<li class="submenu">
					<a href="#"><i class="fe fe-lock"></i> <span> Access Control</span> </a>
					<ul style="display: none;">
						@can('view-permission')
						<li><a class="{{ route_is('permissions.index') ? 'active' : '' }}" href="{{route('permissions.index')}}">Permissions</a></li>
						@endcan
						@can('view-role')
						<li><a class="{{ route_is('roles.*') ? 'active' : '' }}" href="{{route('roles.index')}}">Roles</a></li>
						@endcan
					</ul>
				</li>
				@endcan

				@can('view-users')
				<li class="{{ route_is('users.*') ? 'active' : '' }}">
					<a href="{{route('users.index')}}"><i class="fe fe-users"></i> <span>Users</span></a>
				</li>
				@endcan

				<li class="{{ route_is('profile') ? 'active' : '' }}">
					<a href="{{route('profile')}}"><i class="fe fe-user-plus"></i> <span>Profile</span></a>
				</li>
				<li class="{{ route_is('backup.index') ? 'active' : '' }}">
					<a href="{{route('backup.index')}}"><i class="material-icons">backup</i> <span>Backups</span></a>
				</li>
				@can('view-settings')
				<li class="{{ route_is('settings') ? 'active' : '' }}">
					<a href="{{route('settings')}}">
						<i class="material-icons">settings</i>
						 <span> Settings</span>
					</a>
				</li>
				@endcan
			</ul>
		</div>
	</div>
</div>
<!-- /Sidebar -->
