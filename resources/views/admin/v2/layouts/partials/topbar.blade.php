<header class="">

     <div class="topbar">
     <div class="container-fluid">
          <div class="navbar-header">
               <div class="d-flex align-items-center gap-2">
                    <!-- Menu Toggle Button -->
                    <div class="topbar-item">
                         <button type="button" class="button-toggle-menu topbar-button">
                              <i class="ri-menu-2-line fs-24"></i>
                         </button>
                    </div>
               </div>

               <div class="d-flex align-items-center gap-1">
                    <!-- Theme Color (Light/Dark) -->
                    <div class="topbar-item">
                         <button type="button" class="topbar-button" id="light-dark-mode">
                              <i class="ri-moon-line fs-24 light-mode"></i>
                              <i class="ri-sun-line fs-24 dark-mode"></i>
                         </button>
                    </div>

                    <!-- Category -->
                    <div class="dropdown topbar-item d-none d-lg-flex">
                         <button type="button" class="topbar-button" data-toggle="fullscreen">
                              <i class="ri-fullscreen-line fs-24 fullscreen"></i>
                              <i class="ri-fullscreen-exit-line fs-24 quit-fullscreen"></i>
                         </button>
                    </div>

                    <!-- Notification button removed as requested -->

                    <!-- Theme Setting -->
                    {{-- <div class="topbar-item d-none d-md-flex">
                         <button type="button" class="topbar-button" id="theme-settings-btn" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas">
                              <i class="ri-settings-4-line fs-24"></i>
                         </button>
                    </div> --}}

                    <!-- User -->
                    <div class="dropdown topbar-item">
                         <a type="button" class="topbar-button" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <span class="d-flex align-items-center">
                                   @php
                                   $user = Session::get('user');
                                   $name = isset($user['name']) ? $user['name'] : 'Usuario';
                                   $initials = '';

                                   // Obtener iniciales del nombre
                                   $nameParts = explode(' ', $name);
                                   foreach ($nameParts as $part) {
                                        if (!empty($part)) {
                                             $initials .= strtoupper(substr($part, 0, 1));
                                             if (strlen($initials) >= 2) break; // Máximo 2 iniciales
                                        }
                                   }

                                   // Si solo hay una inicial, usar la primera letra
                                   if (strlen($initials) < 2 && !empty($name)) {
                                        $initials = strtoupper(substr($name, 0, 2));
                                   }
                                   @endphp

                                   <div class="avatar-sm rounded-circle bg-white text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        {{ $initials }}
                                   </div>
                              </span>
                         </a>
                         <div class="dropdown-menu dropdown-menu-end">
                              <!-- item-->
                              <h6 class="dropdown-header">Welcome {{ $name }}!</h6>

                              <div class="dropdown-divider my-1"></div>

                              <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}">
                                   <i class="ri-logout-box-line align-middle me-2 fs-18"></i><span class="align-middle">Logout</span>
                              </a>
                         </div>
                    </div>
               </div>
          </div>
     </div></div>
</header>
