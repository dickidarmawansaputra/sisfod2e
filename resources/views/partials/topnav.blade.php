<form class="form-inline mr-auto" action="">
  <ul class="navbar-nav mr-3">
    <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
  </ul>
</form>
<ul class="navbar-nav navbar-right">
  <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
    <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div></a>
    <div class="dropdown-menu dropdown-menu-right">
      <a href="#" class="dropdown-item has-icon">
        <i class="far fa-user"></i> Pengaturan Profil
      </a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item has-icon text-danger" href="{{ route('logout') }}"
         onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();">
         <i class="fas fa-sign-out-alt"></i> Logout
      </a>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
      </form>
    </div>
  </li>
</ul>
