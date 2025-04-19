<!-- Sidebar -->
<div class="sidebar" id="sidebar">
	<div class="sidebar-inner slimscroll">
		<div id="sidebar-menu" class="sidebar-menu">

			<ul>
				{{-- <li class="menu-title">
					<span>Main</span>
				</li> --}}
				<li class="{{ route_is('dashboard') ? 'active' : '' }}">
					<a href="{{route('dashboard')}}"><i class="fe fe-home"></i> <span>Tableau de bord</span></a>
				</li>
                @can('view-service')
				<li class="submenu">
					<a href="#"><i class="fe fe-building"></i> <span> Département</span> </a>
					<ul style="display: none;">
						{{-- <li><a class="{{ route_is('service.*') ? 'active' : '' }}" href="{{route('service.index')}}">Services</a></li>
						@can('create-service')<li><a class="{{ route_is('service.create') ? 'active' : '' }}" href="{{route('service.create')}}">Nouveau Service</a></li>@endcan --}}
						<li><a class="{{ route_is('departments.*') ? 'active' : '' }}" href="{{route('departments.index')}}">Departments</a></li>

					</ul>
				</li>
				@endcan
                @can('view-doctor')
                <li class="submenu">
					<a href="#"><i class="fe fe-book"></i> <span> Médecins</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is('doctors.*') ? 'active' : '' }}" href="{{route('doctors.index')}}">Liste des médecins</a></li>
					</ul>
				</li>
                @endcan
                @can('view-patient')
                <li class="submenu">
					<a href="#"><i class="fe fe-beginner"></i> <span> Patients</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is('patients.*') ? 'active' : '' }}" href="{{route('patients.index')}}">Liste des patients</a></li>
					</ul>
				</li>
                @endcan
                @can('view-exam')
                <li class="submenu">
					<a href="#"><i class="fe fe-fork"></i> <span>Examens médicaux</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is('exam-types.*') ? 'active' : '' }}" href="{{route('exam-types.index')}}">Examens</a></li>
					</ul>
				</li>
                @endcan
				@can('view-products')
				<li class="submenu">
					<a href="#"><i class="fe fe-document"></i> <span> Pharmacie</span> </a>
					<ul style="display: none;">
                        @can('view-category')
                        <li class="{{ route_is('categories.*') ? 'active' : '' }}">
                            <a href="{{route('categories.index')}}">
                                {{-- <i class="fe fe-layout"></i> --}}
                                <span>Catégories</span></a>
                        </li>
                        @endcan
                        @can('view-purchase')
				{{-- <li class="submenu">
					<a href="#"><i class="fe fe-star-o"></i> <span> Approvisionnements</span> </a>
					<ul style="display: none;"> --}}
						<li><a class="{{ route_is('purchases.*') ? 'active' : '' }}" href="{{route('purchases.index')}}">Approvisionnements</a></li>
						{{-- @can('create-purchase')
						<li><a class="{{ route_is('purchases.create') ? 'active' : '' }}" href="{{route('purchases.create')}}">Nouvel achat</a></li>
						@endcan --}}
					{{-- </ul>
				</li> --}}
				@endcan
                @can('view-supplier')
				{{-- <li class="submenu">
					<a href="#"><i class="fe fe-user"></i> <span> Fournisseurs</span> </a>
					<ul style="display: none;"> --}}
						<li><a class="{{ route_is('suppliers.*') ? 'active' : '' }}" href="{{route('suppliers.index')}}">Fournisseurs</a></li>
						{{-- @can('create-supplier')<li><a class="{{ route_is('suppliers.create') ? 'active' : '' }}" href="{{route('suppliers.create')}}">Nouveau fournisseur</a></li>@endcan
					</ul>
				</li> --}}
				@endcan

						<li><a class="{{ route_is(('products.*')) ? 'active' : '' }}" href="{{route('products.index')}}">Liste des produits</a></li>
						{{-- @can('create-product')<li><a class="{{ route_is('products.create') ? 'active' : '' }}" href="{{route('products.create')}}">Ajouter un produit</a></li>@endcan --}}

                        @can('view-sales')
                        {{-- <li class="submenu">
                            <a href="#"><i class="fe fe-activity"></i> <span> Ventes</span> </a>
                            <ul style="display: none;"> --}}
                                <li><a class="{{ route_is('sales.*') ? 'active' : '' }}" href="{{route('sales.index')}}">Ventes</a></li>
                                {{-- @can('create-sale')
                                <li><a class="{{ route_is('sales.create') ? 'active' : '' }}" href="{{route('sales.create')}}">Nouvelle vente</a></li>
                                @endcan
                            </ul>
                        </li> --}}
                        @endcan
                        @can('view-outstock-products')<li><a class="{{ route_is('outstock') ? 'active' : '' }}" href="{{route('outstock')}}">Rupture de stock</a></li>@endcan
						@can('view-expired-products')<li><a class="{{ route_is('expired') ? 'active' : '' }}" href="{{route('expired')}}">Produits expirés</a></li>@endcan


                    </ul>
				</li>
				@endcan
                @can('view-facture')
                <li class="submenu">
					<a href="#"><i class="fe fe-add-cart"></i> <span> Facturation</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is('invoices.*') ? 'active' : '' }}" href="{{route('invoices.index')}}">Facture</a></li>

					</ul>
				</li>
                @endcan
                @can('view-caisse')
                <li class="submenu">
					<a href="#"><i class="fe fe-align-bottom"></i> <span> Gestion de compte</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is('finances.*') ? 'active' : '' }}" href="{{route('finances.index')}}">Caisse</a></li>
						{{-- <li><a class="{{ route_is('finances.create') ? 'active' : '' }}" href="{{route('finances.create')}}">Nouvelle transaction</a></li> --}}

					</ul>
				</li>
                @endcan
				@can('view-reports')
				<li class="submenu">
					<a href="#"><i class="fe fe-document"></i> <span> Rapports</span> </a>
					<ul style="display: none;">
						<li><a class="{{ route_is('sales.report') ? 'active' : '' }}" href="{{route('sales.report')}}">Rapport des ventes</a></li>
						<li><a class="{{ route_is('purchases.report') ? 'active' : '' }}" href="{{route('purchases.report')}}">Rapport des achats</a></li>
					</ul>
				</li>
				@endcan


				@can('view-users')
				<li class="{{ route_is('users.*') ? 'active' : '' }}">
					<a href="{{route('users.index')}}"><i class="fe fe-users"></i> <span>Utilisateurs</span></a>
				</li>
				@endcan

				<li class="{{ route_is('profile') ? 'active' : '' }}">
					<a href="{{route('profile')}}"><i class="fe fe-user-plus"></i> <span>Profil</span></a>
				</li>

				@can('view-access-control')
				<li class="submenu">
					<a href="#"><i class="fe fe-lock"></i> <span>Contrôle d'accès</span> </a>
					<ul style="display: none;">
						@can('view-permission')
						<li><a class="{{ route_is('permissions.index') ? 'active' : '' }}" href="{{route('permissions.index')}}">Permissions</a></li>
						@endcan
						@can('view-role')
						<li><a class="{{ route_is('roles.*') ? 'active' : '' }}" href="{{route('roles.index')}}">Rôles</a></li>
						@endcan
					</ul>
				</li>
				@endcan
                @can('view-settings')
				<li class="{{ route_is('settings') ? 'active' : '' }}">
					<a href="{{route('settings')}}">
						<i class="material-icons">P</i>
						 <span>Paramètres</span>
					</a>
				</li>
				@endcan
				<li class="{{ route_is('backup.index') ? 'active' : '' }}">
					<a href="{{route('backup.index')}}"><i class="material-icons">Sauvegardes</i> <span>Sauvegardes</span></a>
				</li>

			</ul>
		</div>
	</div>
</div>
<!-- /Sidebar -->

