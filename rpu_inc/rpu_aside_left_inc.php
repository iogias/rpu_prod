<?php
if (!defined('WEB_ROOT')) {
    exit;
}
?>
  <!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?php echo BASE_URI; ?>" class="brand-link">
      <span class="brand-text font-weight-light" style="padding-left:10px;">Aplikasi RPU</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a id="get_rpu_beranda" href="#" class="nav-link a-link-menu-nav">
              <i class="nav-icon fas fa-home"></i>
              <p>Beranda</p>
            </a>
          </li>
          <li class="nav-header pt-1">PENJUALAN</li>
          <li class="nav-item">
            <a id="get_rpu_pos" href="#" class="nav-link a-link-menu-nav">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>POS</p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>
                Pelanggan
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a id="get_rpu_customer" href="#" class="nav-link a-link-menu-nav">
                <i class="nav-icon fas fa-user"></i>
                <p>Customer</p>
              </a>
            </li>
            <li class="nav-item">
              <a id="get_rpu_customer_group" href="#" class="nav-link a-link-menu-nav">
                <i class="nav-icon fas fa-users"></i>
                <p>Group Customer</p>
              </a>
            </li>
            <li class="nav-item">
              <a id="get_rpu_outlet" href="#" class="nav-link a-link-menu-nav">
                <i class="nav-icon fas fa-store"></i>
                <p>Outlet</p>
              </a>
            </li>
            </ul>
          </li>
          <li class="nav-header pt-1">PEMBELIAN</li>
          <li class="nav-item">
            <a id="get_rpu_supplier" href="#" class="nav-link a-link-menu-nav">
              <i class="nav-icon fas fa-truck"></i>
              <p>Supplier</p>
            </a>
          </li>
          <li class="nav-item">
            <a id="get_rpu_pembelian" href="#" class="nav-link a-link-menu-nav">
              <i class="nav-icon fas fa-money-bill"></i>
              <p>Pembelian (PO)</p>
            </a>
          </li>
          <li class="nav-item">
            <a id="get_rpu_biaya" href="#" class="nav-link a-link-menu-nav">
              <i class="nav-icon fas fa-wallet"></i>
              <p>Biaya Operasional</p>
            </a>
          </li>
          <li class="nav-header pt-1">PRODUK</li>
          <li class="nav-item">
            <a id="get_rpu_kategori" href="#" class="nav-link a-link-menu-nav">
              <i class="nav-icon fas fa-cubes"></i>
              <p>Kategori</p>
            </a>
          </li>
          <li class="nav-item">
            <a id="get_rpu_produk" href="#" class="nav-link a-link-menu-nav">
              <i class="nav-icon fas fa-cube"></i>
              <p>Produk</p>
            </a>
          </li>
          <li class="nav-item">
            <a id="get_rpu_stok" href="#" class="nav-link a-link-menu-nav">
              <i class="nav-icon fas fa-box"></i>
              <p>Stok</p>
            </a>
          </li>
          <li class="nav-header pt-1">LAPORAN</li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-list"></i>
              <p>
                Laporan-laporan
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
              <a id="get_rpu_lap_pembelian" href="#" class="nav-link a-link-menu-nav">
                <i class="nav-icon fas fa-clipboard"></i>
                <p>Laporan Pembelian</p>
              </a>
              </li>
              <li class="nav-item">
              <a id="get_rpu_lap_penjualan" href="#" class="nav-link a-link-menu-nav">
                <i class="nav-icon fas fa-clipboard"></i>
                <p>Laporan Penjualan</p>
              </a>
              </li>
              <li class="nav-item">
              <a id="get_rpu_lap_lr" href="#" class="nav-link a-link-menu-nav">
                <i class="nav-icon fas fa-clipboard"></i>
                <p>Laporan Laba-Rugi</p>
              </a>
              </li>
            </ul>
          </li>
          <li class="nav-header pt-1">UTILITAS</li>
          <li class="nav-item">
            <a id="get_rpu_pengaturan" href="#" class="nav-link a-link-menu-nav">
              <i class="nav-icon fas fa-tools"></i>
              <p>Pengaturan</p>
            </a>
          </li>
          <li class="nav-item">
            <a id="get_rpu_user" href="#" class="nav-link a-link-menu-nav">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>Manajemen User</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>


