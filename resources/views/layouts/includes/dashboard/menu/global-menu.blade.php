<li class="menu-section">
  <h4 class="menu-text">Settings</h4>
  <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
</li>

@can('profile.access')
  <li class="menu-item {{ request()->is(['dashboard/change_password']) || request()->routeIs('edit.profile') ? 'menu-item-active' : null }}" aria-haspopup="true">
    <a href="{{ route('edit.profile') }}" class="menu-link">
      <span class="svg-icon menu-icon">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
          <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <polygon points="0 0 24 0 24 24 0 24" />
            <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
            <path
              d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
              fill="#000000" fill-rule="nonzero" />
          </g>
        </svg>
      </span>
      <span class="menu-text">Profile</span>
    </a>
  </li>
@endcan

@canany(['settings.access', 'role_permission.access'])
  <li class="menu-item {{ request()->is(['dashboard/settings*', 'dashboard/role*']) ? 'menu-item-active menu-item-open' : null }}" aria-haspopup="true" data-menu-toggle="hover">
    <a href="javascript:" class="menu-link menu-toggle">
      <span class="svg-icon menu-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
          <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <rect x="0" y="0" width="24" height="24" />
            <path
              d="M7,3 L17,3 C19.209139,3 21,4.790861 21,7 C21,9.209139 19.209139,11 17,11 L7,11 C4.790861,11 3,9.209139 3,7 C3,4.790861 4.790861,3 7,3 Z M7,9 C8.1045695,9 9,8.1045695 9,7 C9,5.8954305 8.1045695,5 7,5 C5.8954305,5 5,5.8954305 5,7 C5,8.1045695 5.8954305,9 7,9 Z"
              fill="#000000" />
            <path
              d="M7,13 L17,13 C19.209139,13 21,14.790861 21,17 C21,19.209139 19.209139,21 17,21 L7,21 C4.790861,21 3,19.209139 3,17 C3,14.790861 4.790861,13 7,13 Z M17,19 C18.1045695,19 19,18.1045695 19,17 C19,15.8954305 18.1045695,15 17,15 C15.8954305,15 15,15.8954305 15,17 C15,18.1045695 15.8954305,19 17,19 Z"
              fill="#000000" opacity="0.3" />
          </g>
        </svg>
      </span>
      <span class="menu-text">Settings</span>
      <i class="menu-arrow"></i>
    </a>
    <div class="menu-submenu">
      <i class="menu-arrow"></i>
      <ul class="menu-subnav">
        @can('settings.access')
          <li class="menu-item {{ request()->is('dashboard/settings/company_settings*') ? 'menu-item-active' : null }}" aria-haspopup="true">
            <a href="{{ route('company.edit') }}" class="menu-link">
              <i class="menu-bullet menu-bullet-line">
                <span></span>
              </i>
              <span class="menu-text">Company Setting</span>
            </a>
          </li>
        @endcan
        @can('role_permission.access')
          <li class="menu-item {{ request()->is('dashboard/role*') ? 'menu-item-active' : null }}" aria-haspopup="true">
            <a href="{{ route('role.assign') }}" class="menu-link">
              <i class="menu-bullet menu-bullet-line">
                <span></span>
              </i>
              <span class="menu-text">Role permission</span>
            </a>
          </li>
        @endcan
      </ul>
    </div>
  </li>
@endcanany
