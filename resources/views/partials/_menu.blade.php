<div class="sidebar__menu-group">
  <ul class="sidebar_nav">
    <li
      class="has-child {{ Request::routeIs('dashboard.index') || Request::routeIs('suppliers.payments.reminder') || Request::routeIs('clients.claims.reminder')  ? 'open' : '' }}">
      <a href="#" class="{{ Request::routeIs('dashboard.index') ? 'active' : '' }}">
        <span class="nav-icon uil uil-create-dashboard"></span>
        <span class="menu-text">{{ trans('menu.dashboard-menu-title') }}</span>
        <span class="toggle-icon"></span>
      </a>
      <ul>
        <li class="{{ Request::routeIs('dashboard.index') ? 'active' : '' }}"><a
            href="{{ route('dashboard.index') }}">{{ trans('menu.dashboard-index') }}</a></li>
        <li class="{{ Request::routeIs('suppliers.payments.reminder') ? 'active' : '' }}"><a
            href="{{ route('suppliers.payments.reminder') }}">{{ trans('menu.dashboard-suppliers-payment-reminder')
            }}</a></li>
        <li class="{{ Request::routeIs('companies.payments.reminder') ? 'active' : '' }}"><a
            href="{{ route('companies.payments.reminder') }}">{{ trans('menu.dashboard-companies-claim-reminder')
            }}</a></li>
        <li class="{{ Request::routeIs('clients.claims.reminder') ? 'active' : '' }}"><a
            href="{{ route('clients.claims.reminder') }}">{{ trans('menu.dashboard-Clients-claim-reminder')
            }}</a></li>
      </ul>
    </li>
    @can('view buy ship orders')
    <li class="has-child {{ Route::is('buy_ship_orders.*') ? 'open' : '' }}">
      <a href="#" class="{{ Route::is('buy_ship_orders.*') ? 'active' : '' }}">
        <span class="nav-icon uil uil-store-alt"></span>
        <span class="menu-text">{{ trans('order.buy-orders') }}</span>
        <span class="toggle-icon"></span>
      </a>
      <ul>
        {{-- <li><a href="{{ route('project.project_list',app()->getLocale()) }}"
            class="{{ Request::is(app()->getLocale().'/applications/project/list') ? 'active':'' }}">{{
            trans('menu.project-title') }}</a></li> --}}
        <li
          class="{{ Request::routeIs('buy_ship_orders.index') || Route::is('buy_ship_orders.edit') ? 'active' : '' }}">
          <a href="{{ route('buy_ship_orders.index') }}"
            class="{{ Request::routeIs('buy_ship_orders.index') ? 'active' : '' }}">{{ trans('order.orders') }}</a>
        </li>
        @can('add buy ship orders')
        <li class="{{ Request::routeIs('buy_ship_orders.create') ? 'active' : '' }}"><a
            href="{{ route('buy_ship_orders.create') }}"
            class="{{ Request::routeIs('buy_ship_orders.create') ? 'active' : '' }}">{{ trans('order.add-order') }}</a>
        </li>
        @endcan

      </ul>
    </li>
    @endcan
    @can('view repositories items')
    <li class="has-child {{ Route::is('repository.*') ? 'open' : '' }}">
      <a href="#" class="{{ Route::is('repository.*') ? 'active' : '' }}">
        <span class="nav-icon uil uil-house-user"></span>
        <span class="menu-text">{{ trans('repo.repos') }}</span>
        <span class="toggle-icon"></span>
      </a>
      <ul>
        <li class="{{ Request::routeIs('repository.*') || Route::is('repository.*') ? 'active' : '' }}">
          <a href="{{ route('repository.index') }}"
            class="{{ Request::routeIs('repository.index') ? 'active' : '' }}">{{ trans('repo.repos') }}</a>
        </li>
      </ul>
    </li>
    @endcan
    @can('view containers')
    <li class="has-child {{ Route::is('containers.*') ? 'open' : '' }}">
      <a href="#" class="{{ Route::is('containers.*') ? 'active' : '' }}">
        <span class="nav-icon uil uil-store"></span>
        <span class="menu-text">{{ trans('container.containers') }}</span>
        <span class="toggle-icon"></span>
      </a>
      <ul>
        <li class="{{ Request::routeIs('containers.index') || Route::is('containers.edit') ? 'active' : '' }}"><a
            href="{{ route('containers.index') }}" class="{{ Request::routeIs('containers.index') ? 'active' : '' }}">{{
            trans('container.containers') }}</a>
        </li>
        @can('add containers')
        <li class="{{ Request::routeIs('containers.create') ? 'active' : '' }}"><a
            href="{{ route('containers.create') }}"
            class="{{ Request::routeIs('containers.create') ? 'active' : '' }}">{{ trans('container.add-container')
            }}</a>
        </li>
        @endcan
      </ul>
    </li>
    @endcan
    @can('view transfers')
    <li class="has-child {{ Route::is('transfers.*') ? 'open' : '' }}">
      <a href="#" class="{{ Route::is('transfers.*') ? 'active' : '' }}">
        <span class="nav-icon la la-money-bill-wave"></span>
        <span class="menu-text">{{ trans('transfer.transfers') }}</span>
        <span class="toggle-icon"></span>
      </a>
      <ul>
        <li class="{{ Request::routeIs('transfer.transfers') || Route::is('transfers.edit') ? 'active' : '' }}"><a
            href="{{ route('transfers.index') }}" class="{{ Request::routeIs('transfers.index') ? 'active' : '' }}">{{
            trans('transfer.transfers') }}</a></li>
        @can('add containers')
        <li class="{{ Request::routeIs('transfers.create') ? 'active' : '' }}"><a href="{{ route('transfers.create') }}"
            class="{{ Request::routeIs('transfers.create') ? 'active' : '' }}">{{ trans('transfer.add-transfer') }}</a>
        </li>
        @endcan
      </ul>
    </li>
    @endcan
    @can('view expenses')
    <li class="has-child {{ Route::is('expenses.*') ? 'open' : '' }}">
      <a href="#" class="{{ Route::is('expenses.*') ? 'active' : '' }}">
        <span class="nav-icon la la-money-bill-wave"></span>
        <span class="menu-text">{{ trans('expense.expenses') }}</span>
        <span class="toggle-icon"></span>
      </a>
      <ul>
        <li class="{{ Request::routeIs('expenses.index') || Route::is('expenses.edit') ? 'active' : '' }}"><a
            href="{{ route('expenses.index') }}" class="{{ Request::routeIs('expenses.index') ? 'active' : '' }}">{{
            trans('expense.expenses') }}</a></li>
        @can('add expenses')
        <li class="{{ Request::routeIs('expenses.create') ? 'active' : '' }}"><a href="{{ route('expenses.create') }}"
            class="{{ Request::routeIs('expenses.create') ? 'active' : '' }}">{{ trans('expense.add-expense') }}</a>
        </li>
        @endcan
      </ul>
    </li>
    <li class=" {{ Route::is('income.outcome') ? 'active' : '' }}">
      <a href="{{route('income.outcome')}}" class="{{ Route::is('income.outcome') ? 'active' : '' }}">
        <span class="nav-icon la la-money-bill-wave"></span>
        <span class="menu-text">{{ trans('expense.income-outcome') }}</span>
      </a>
    </li>
    @endcan

    <li class="menu-title mt-30">
      <span>{{ trans('menu.definitions-menu-title') }}</span>
    </li>
    @can('view suppliers')
    <li class="has-child {{ Route::is('suppliers.*') ? 'open' : '' }}">
      <a href="#" class="{{ Route::is('suppliers.*') ? 'active' : '' }}">
        <span class="nav-icon uil uil-user"></span>
        <span class="menu-text">{{ trans('supplier.suppliers') }}</span>
        <span class="toggle-icon"></span>
      </a>
      <ul>
        {{-- <li><a href="{{ route('project.project_list',app()->getLocale()) }}"
            class="{{ Request::is(app()->getLocale().'/applications/project/list') ? 'active':'' }}">{{
            trans('menu.project-title') }}</a></li> --}}
        <li class="{{ Request::routeIs('suppliers.index') || Route::is('suppliers.edit') ? 'active' : '' }}"><a
            href="{{ route('suppliers.index') }}" class="{{ Request::routeIs('suppliers.index') ? 'active' : '' }}">{{
            trans('supplier.suppliers') }}</a></li>
        @can('add suppliers')
        <li class="{{ Request::routeIs('suppliers.create') ? 'active' : '' }}"><a href="{{ route('suppliers.create') }}"
            class="{{ Request::routeIs('suppliers.create') ? 'active' : '' }}">{{ trans('supplier.add-supplier') }}</a>
        </li>
        @endcan

      </ul>
    </li>
    @endcan

    @can('view products')
    <li class="has-child {{ Route::is('products.*') ? 'open' : '' }}">
      <a href="#" class="{{ Route::is('products.*') ? 'active' : '' }}">
        <span class="nav-icon uil uil-user"></span>
        <span class="menu-text">{{ trans('products.products') }}</span>
        <span class="toggle-icon"></span>
      </a>
      <ul>
        {{-- <li><a href="{{ route('project.project_list',app()->getLocale()) }}"
            class="{{ Request::is(app()->getLocale().'/applications/project/list') ? 'active':'' }}">{{
            trans('menu.project-title') }}</a></li> --}}
        <li class="{{ Request::routeIs('products.index') || Route::is('products.edit') ? 'active' : '' }}"><a
            href="{{ route('products.index') }}" class="{{ Request::routeIs('products.index') ? 'active' : '' }}">{{
            trans('products.products') }}</a></li>
        @can('add products')
        <li class="{{ Request::routeIs('products.create') ? 'active' : '' }}"><a href="{{ route('products.create') }}"
            class="{{ Request::routeIs('products.create') ? 'active' : '' }}">{{ trans('products.add') }}</a></li>
        @endcan

      </ul>
    </li>
    @endcan

    @can('view clients')
    <li class="has-child {{ Route::is('clients.*') ? 'open' : '' }}">
      <a href="#" class="{{ Route::is('clients.*') ? 'active' : '' }}">
        <span class="nav-icon uil uil-user"></span>
        <span class="menu-text">{{ trans('client.clients') }}</span>
        <span class="toggle-icon"></span>
      </a>
      <ul>
        {{-- <li><a href="{{ route('project.project_list',app()->getLocale()) }}"
            class="{{ Request::is(app()->getLocale().'/applications/project/list') ? 'active':'' }}">{{
            trans('menu.project-title') }}</a></li> --}}
        <li class="{{ Request::routeIs('clients.index') || Route::is('clients.edit') ? 'active' : '' }}"><a
            href="{{ route('clients.index') }}" class="{{ Request::routeIs('clients.index') ? 'active' : '' }}">{{
            trans('client.clients') }}</a></li>
        @can('add clients')
        <li class="{{ Request::routeIs('clients.create') ? 'active' : '' }}"><a href="{{ route('clients.create') }}"
            class="{{ Request::routeIs('clients.create') ? 'active' : '' }}">{{ trans('client.add-client') }}</a></li>
        @endcan

      </ul>
    </li>
    @endcan

    @can('view shipping companies')
    <li class="has-child {{ Route::is('shipping_companies.*') ? 'open' : '' }}">
      <a href="#" class="{{ Route::is('shipping_companies.*') ? 'active' : '' }}">
        <span class="nav-icon uil uil-process"></span>
        <span class="menu-text">{{ trans('company.companies') }}</span>
        <span class="toggle-icon"></span>
      </a>
      <ul>
        {{-- <li><a href="{{ route('project.project_list',app()->getLocale()) }}"
            class="{{ Request::is(app()->getLocale().'/applications/project/list') ? 'active':'' }}">{{
            trans('menu.project-title') }}</a></li> --}}
        <li
          class="{{ Request::routeIs('shipping_companies.index') || Route::is('shipping_companies.edit') ? 'active' : '' }}">
          <a href="{{ route('shipping_companies.index') }}"
            class="{{ Request::routeIs('shipping_companies.index') ? 'active' : '' }}">{{ trans('company.companies')
            }}</a>
        </li>
        @can('add shipping companies')
        <li class="{{ Request::routeIs('shipping_companies.create') ? 'active' : '' }}"><a
            href="{{ route('shipping_companies.create') }}"
            class="{{ Request::routeIs('shipping_companies.create') ? 'active' : '' }}">{{ trans('company.add-company')
            }}</a>
        </li>
        @endcan

      </ul>
    </li>
    @endcan
    @can('view repositories')
    <li class="has-child {{ Route::is('repositories.*') ? 'open' : '' }}">
      <a href="#" class="{{ Route::is('repositories.*') ? 'active' : '' }}">
        <span class="nav-icon uil uil-house-user"></span>
        <span class="menu-text">{{ trans('repo.repos') }}</span>
        <span class="toggle-icon"></span>
      </a>
      <ul>
        {{-- <li><a href="{{ route('project.project_list',app()->getLocale()) }}"
            class="{{ Request::is(app()->getLocale().'/applications/project/list') ? 'active':'' }}">{{
            trans('menu.project-title') }}</a></li> --}}
        <li class="{{ Request::routeIs('repositories.index') || Route::is('repositories.edit') ? 'active' : '' }}"><a
            href="{{ route('repositories.index') }}"
            class="{{ Request::routeIs('repositories.index') ? 'active' : '' }}">{{ trans('repo.repos') }}</a></li>
        @can('add repositories')
        <li class="{{ Request::routeIs('repositories.create') ? 'active' : '' }}"><a
            href="{{ route('repositories.create') }}"
            class="{{ Request::routeIs('repositories.create') ? 'active' : '' }}">{{ trans('repo.add-repo') }}</a></li>
        @endcan
      </ul>
    </li>
    @endcan
    @can('view brokers')
    <li class="has-child {{ Route::is('brokers.*') ? 'open' : '' }}">
      <a href="#" class="{{ Route::is('brokers.*') ? 'active' : '' }}">
        <span class="nav-icon uil uil-user"></span>
        <span class="menu-text">{{ trans('broker.brokers') }}</span>
        <span class="toggle-icon"></span>
      </a>
      <ul>
        {{-- <li><a href="{{ route('project.project_list',app()->getLocale()) }}"
            class="{{ Request::is(app()->getLocale().'/applications/project/list') ? 'active':'' }}">{{
            trans('menu.project-title') }}</a></li> --}}
        <li class="{{ Request::routeIs('brokers.index') || Route::is('brokers.edit') ? 'active' : '' }}"><a
            href="{{ route('brokers.index') }}" class="{{ Request::routeIs('brokers.index') ? 'active' : '' }}">{{
            trans('broker.brokers') }}</a></li>
        @can('add brokers')
        <li class="{{ Request::routeIs('brokers.create') ? 'active' : '' }}"><a href="{{ route('brokers.create') }}"
            class="{{ Request::routeIs('brokers.create') ? 'active' : '' }}">{{ trans('broker.add-broker') }}</a></li>
        @endcan

      </ul>
    </li>
    @endcan
    @can('view users')
    <li class="menu-title mt-30">
      <span>{{ trans('menu.user-menu-title') }}</span>
    </li>
    <li class="has-child {{ Route::is('users.*') ? 'open' : '' }}">
      <a href="#" class="{{ Route::is('users.*') ? 'active' : '' }}">
        <span class="nav-icon uil uil-users-alt"></span>
        <span class="menu-text">{{ trans('menu.user-menu-title') }}</span>
        <span class="toggle-icon"></span>
      </a>
      <ul>
        {{-- <li><a href="{{ route('project.project_list',app()->getLocale()) }}"
            class="{{ Request::is(app()->getLocale().'/applications/project/list') ? 'active':'' }}">{{
            trans('menu.project-title') }}</a></li> --}}
        <li class="{{ Request::routeIs('users.index') || Route::is('users.edit') ? 'active' : '' }}"><a
            href="{{ route('users.index') }}" class="{{ Request::routeIs('users.index') ? 'active' : '' }}">{{
            trans('menu.user-menu-title') }}</a>
        </li>
        @can('add users')
        <li class="{{ Request::routeIs('users.create') ? 'active' : '' }}"><a href="{{ route('users.create') }}"
            class="{{ Request::routeIs('users.create') ? 'active' : '' }}">{{ trans('menu.user-add') }}</a></li>
        @endcan

      </ul>
    </li>
    @endcan

    @can('update settings')
    <li class="has-child {{ Route::is('settings.*') ? 'open' : '' }}">
      <a href="#" class="{{ Route::is('settings.*') ? 'active' : '' }}">
        <span class="nav-icon uil uil-users-alt"></span>
        <span class="menu-text">{{ trans('menu.settings') }}</span>
        <span class="toggle-icon"></span>
      </a>
      <ul>
        {{-- <li><a href="{{ route('project.project_list',app()->getLocale()) }}"
            class="{{ Request::is(app()->getLocale().'/applications/project/list') ? 'active':'' }}">{{
            trans('menu.project-title') }}</a></li> --}}
        <li class="{{ Request::routeIs('settings.edit') ? 'active' : '' }}"><a
            href="{{ route('settings.edit') }}" class="{{ Request::routeIs('settings.edit') ? 'active' : '' }}">{{
            trans('menu.settings') }}</a>
        </li>
        <li class="{{ Request::routeIs('settings.colors') ? 'active' : '' }}"><a
            href="{{ route('settings.colors') }}" class="{{ Request::routeIs('settings.colors') ? 'active' : '' }}">{{
          trans('menu.settings_colors') }}</a>
        </li>

      </ul>
    </li>
    @endcan
    {{-- <li class="has-child {{ Request::is(app()->getLocale().'/applications/project/*') ? 'open':'' }}">
      <a href="#" class="{{ Request::is(app()->getLocale().'/applications/project/*') ? 'active':'' }}">
        <span class="nav-icon uil uil-folder"></span>
        <span class="menu-text">{{ trans('menu.project-menu-title') }}</span>
        <span class="toggle-icon"></span>
      </a>
      <ul>
        <li><a href="{{ route('project.project_list',app()->getLocale()) }}"
            class="{{ Request::is(app()->getLocale().'/applications/project/list') ? 'active':'' }}">{{
            trans('menu.project-title') }}</a></li>
        <li><a href="{{ route('project.project_detail',app()->getLocale()) }}"
            class="{{ Request::is(app()->getLocale().'/applications/project/project-detail') ? 'active':'' }}">{{
            trans('menu.project-detail') }}</a></li>
        <li><a href="{{ route('project.create_project',app()->getLocale()) }}"
            class="{{ Request::is(app()->getLocale().'/applications/project/create') ? 'active':'' }}">{{
            trans('menu.create-project') }}</a></li>
      </ul>
    </li> --}}
  </ul>
</div>
