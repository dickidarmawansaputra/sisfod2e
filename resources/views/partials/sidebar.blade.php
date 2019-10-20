<aside id="sidebar-wrapper">
  <div class="sidebar-brand">
    <a href="">{{ env('APP_NAME') }}</a>
  </div>
  <div class="sidebar-brand sidebar-brand-sm">
    <a href="#">{{ strtoupper(substr(env('APP_NAME'), 0, 2)) }}</a>
  </div>
  <ul class="sidebar-menu">
    <li class="menu-header">Dashboard</li>
    <li class="{{ request()->is('/dashboard/*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('dashboard') }}"><i class="fa fa-rocket"></i> <span>Dashboard</span></a></li>
    <li><a class="nav-link" href="{{ route('upload-surat') }}"><i class="fa fa-file-upload"></i> <span>Upload Surat</span></a></li>
    <li><a class="nav-link" href=""><i class="fa fa-paper-plane"></i> <span>Kirim Surat</span></a></li>
    <li><a class="nav-link" href=""><i class="fa fa-file-alt"></i> <span>Cek Surat</span></a></li>
    <li class="menu-header">Users</li>
    <li><a class="nav-link" href="{{ route('user') }}"><i class="fa fa-users"></i> <span>User</span></a></li>
  </ul>
</aside>
