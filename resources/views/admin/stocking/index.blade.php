@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            <span class="text-capitalize">Kho Hàng</span>
            <small id="datatable_info_stack">Danh sách Nhập kho</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url(config('backpack.base.route_prefix')) }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">Danh sách Nhập kho</li>
        </ol>
    </section>
@endsection

@section('content')
    <!-- Default box -->
    <div class="box">
        <div class="box-header">
        </div>
        <div class="box-body">
            <form method="GET">
                <div class="col-md-7">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <div class="input-group">
                                <span class="input-group-addon" style="border:none;padding-left:0">Thời gian:</span>

                                <select name="type-date" class="form-control">
                                    {{ $typedate = isset($params['type-date']) ? $params['type-date'] : '' }}
                                    <option value="updated_at" {{ $typedate == 'updated_at' ? 'selected="selected"' : '' }}>Ngày cập nhật</option>
                                    <option value="created_at" {{ $typedate == 'created_at' ? 'selected="selected"' : '' }}>Ngày tạo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            {{--<input name="q" type="hidden" value="{{ !empty(request()->input('q')) ? request()->input('q') : '' }}" >--}}
                            <input class="form-control date-picker" value="{{ !empty(request()->input('start_date')) ? request()->input('start_date') : '' }}" name="start_date" type="text" placeholder="Ngày bắt đầu">
                        </div>
                        <div class="col-sm-4">
                            <input class="form-control date-picker" value="{{ !empty(request()->input('end_date')) ? request()->input('end_date') : '' }}" name="end_date" type="text" placeholder="Ngày kết thúc">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon" style="border:none;padding-left:0">Từ khóa:</span>
                                <select class="form-control" name="type">
                                    {{ $type = isset($params['type']) ? $params['type'] : '' }}
                                    <option value="stocking_code" {{ $type == 'stocking_code' ? 'selected="selected"' : '' }}>Mã vận đơn</option>
                                    <option value="supplier_name" {{ $type == 'supplier_name' ? 'selected="selected"' : '' }}>Nhà cung cấp</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <input type="text" name="keyword" class="form-control" value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                        </div>
                        <div style="clear:both"></div>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <div class="col-md-12">
                        <button class="btn btn-primary"><i class="fa fa-search"></i> Tìm kiếm</button>
                        <button class="btn btn-primary bg-olive" name="export" value="1"><i class="fa fa-file-excel-o"></i> Export</button>
                        <a class="btn btn-default" href="{!! route('admin.stocking') !!}"><i class="fa fa-ban"></i> Xóa tìm kiếm</a>
                    </div>
                </div>
            </form>
            <hr style="clear: both;">

            <div class="table-responsive no-padding">
                <table id="crudTable" class="table table-hover" style="max-width: 300%; width: 300%">
                    <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã vận đơn</th>
                        <th>Chi tiết đơn hàng</th>
                        <th>Kho chứa</th>
                        <th>Nhà cung cấp</th>
                        <th>Ngày giao hàng</th>
                        <th>Nhân viên phụ trách</th>
                        <th>Trạng thái</th>
                        <th>Loại nhập kho</th>
                        <th>Người cập nhật</th>
                        <th>Ngày tạo</th>
                        <th>Ngày cập nhật</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($object->count())
                        @foreach ($object as $index => $item)
                        <tr>
                            <td align="center">{{$stt++}}</td>
                            <td>{{$item["stocking_code"]}}</td>
                            <td>

                            </td>
                            <td>{{$item["warehouse_name"]}}</td>
                            <td>{{$item["supplier_name"]}}</td>
                            <td>{{($item['date_receive'] != null) ? \Carbon\Carbon::parse($item['date_receive'])->format('d/m/Y H:i'):""}}</td>
                            <td>{{$item["staff_receive_name"]}}</td>
                            <td>
                                @switch($item["status"])
                                    @case("pending")
                                        Đang chờ
                                        @break
                                    @case("return")
                                        Trả hàng
                                        @break
                                    @case("cancel")
                                        Hủy hàng
                                        @break
                                    @case("success")
                                        Thành công
                                        @break
                                @endswitch
                            </td>
                            <td>
                                @switch($item["type"])
                                    @case("default")
                                        Mặc định
                                        @break
                                    @case("transfer_warehouse")
                                        Chuyển kho
                                        @break
                                @endswitch
                            </td>
                            <td>{{$item["staff_updated_name"]}}</td>
                            <td>{{($item['created_at'] != null) ? \Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i'):""}}</td>
                            <td>{{($item['updated_at'] != null) ? \Carbon\Carbon::parse($item['updated_at'])->format('d/m/Y H:i'):""}}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="17" align="center">Tạm thời chưa có dữ liệu.</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>STT</th>
                        <th>Mã vận đơn</th>
                        <th>Chi tiết đơn hàng</th>
                        <th>Kho chứa</th>
                        <th>Nhà cung cấp</th>
                        <th>Ngày giao hàng</th>
                        <th>Nhân viên phụ trách</th>
                        <th>Trạng thái</th>
                        <th>Loại nhập kho</th>
                        <th>Người cập nhật</th>
                        <th>Ngày tạo</th>
                        <th>Ngày cập nhật</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div><!-- /.box-body -->
        @include('admin.inc.paging')
    </div><!-- /.box -->

    <div class="modal fade" id="myModal" role="dialog"></div>

    <style>
        @media (min-width: 768px) {
            .modal-dialog {
                width: 750px !important;
            }
        }
    </style>
@endsection

@section('after_scripts')
    <script type="text/javascript" src="{{ asset('vendor/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    {{--<script type="text/javascript" src="{{ asset('static/admin/js/main/qualify-user.js') }}"></script>--}}
    <script>
        $(document).ready(function(){
            $('.date-picker').datepicker({format: 'dd/mm/yyyy',});
        });
    </script>
@endsection