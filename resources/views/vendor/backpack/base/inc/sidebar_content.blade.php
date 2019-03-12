<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
<li><a href="{{ route("admin.stocking") }}"><i class="fa fa-archive"></i> <span>Danh sách nhập kho</span></a></li>
<li><a href="{{ route("admin.product") }}"><i class="fa fa-product-hunt"></i> <span>Danh sách sản phẩm</span></a></li>

<li class="header">File Manager</li>
<li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/elfinder') }}"><i class="fa fa-files-o"></i> <span>File manager</span></a></li>
