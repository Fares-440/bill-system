@extends('layouts.master')
@section('title')
    الاقسام
@endsection
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الاعدادات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    الاقسام</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    <!--Page Widget Error-->
    @if ($errors->any())
        <div class="card bd-0 mg-b-20 bg-danger-transparent alert p-0">
            <div class="card-header text-danger font-weight-bold">
                <i class="far fa-times-circle"></i> بيانات الخطأ
                <button aria-label="Close" class="close" data-dismiss="alert" type="button"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="card-body text-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <!--Page Widget Error-->
    <!--Page Widget success-->
    @if (session()->has('Add'))
        <div class="card bd-0 mg-b-20 bg-success-transparent alert p-0">
            <div class="card-header text-success font-weight-bold">
                <i class="far fa-times-circle"></i> بيانات الاضافة
                <button aria-label="Close" class="close" data-dismiss="alert" type="button"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="card-body text-success">
                <ul>
                    <li>{{ session()->get('Add') }}</li>
                </ul>
            </div>
        </div>
    @endif
    @if (session()->has('edit'))
        <div class="card bd-0 mg-b-20 bg-success-transparent alert p-0">
            <div class="card-header text-success font-weight-bold">
                <i class="far fa-times-circle"></i> البيانات المعدلة
                <button aria-label="Close" class="close" data-dismiss="alert" type="button"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="card-body text-success">
                <ul>
                    <li>{{ session()->get('edit') }}</li>
                </ul>
            </div>
        </div>
    @endif
    @if (session()->has('delete'))
        <div class="card bd-0 mg-b-20 bg-success-transparent alert p-0">
            <div class="card-header text-success font-weight-bold">
                <i class="far fa-times-circle"></i> البيانات الحذف
                <button aria-label="Close" class="close" data-dismiss="alert" type="button"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="card-body text-success">
                <ul>
                    <li>{{ session()->get('delete') }}</li>
                </ul>
            </div>
        </div>
    @endif
    <!--Page Widget success-->
    {{-- <!-- row -->
				<div class="row">
                    @if (session()->has('Add'))
                        <div class="alert alert-success alert-dismissable fade show" role="alert">
                            <strong>{{session()->get('Add')}}</strong>
                            <button aria-label="Close" class="close mr-2" data-dismiss="alert" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session()->has('Error'))
                        <div class="alert alert-danger alert-dismissable fade show" role="alert">
                            <strong>{{session()->get('Error')}}</strong>
                            <button aria-label="Close" class="close  mr-2" data-dismiss="alert" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif --}}
    <!--div-->
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    @can('اضافة قسم')
                        <a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-slide-in-right"
                            data-toggle="modal" href="#modaldemo8">اضافة قسم</a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table key-buttons text-md-nowrap" data-page-length="50">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">اسم القسم</th>
                                <th class="border-bottom-0">الوصف</th>
                                <th class="border-bottom-0">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($sections as $section)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $section->section_name }}</td>
                                    <td>{{ $section->description }}</td>
                                    <td>
                                        @can('تعديل قسم')
                                            <a href="#modaledit" id="edit" class="modal-affect btn btn-info"
                                                data-effect="effect-scale" data-section_id="{{ $section->id }}"
                                                data-section_name="{{ $section->section_name }}"
                                                data-description="{{ $section->description }}" data-toggle="modal"
                                                title="تعديل"><i class="las la-pen"></i>
                                            </a>
                                        @endcan
                                        @can('حذف قسم')
                                            <a href="#modaldelete" id="delete" class="modal-affect btn btn-danger"
                                                data-effect="effect-scale" data-section_id="{{ $section->id }}"
                                                data-section_name="{{ $section->section_name }}"
                                                data-description="{{ $section->description }}" data-toggle="modal"
                                                title="خذف"><i class="las la-trash"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--/div-->
    <!-- Modal effects -->
    <div class="modal" id="modaldemo8">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">اضافة قسم</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('sections') }}" class="form-horizontal" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="inputName">اسم القسم</label>
                            <input type="text" class="form-control" name="section_name" id="inputName"
                                placeholder="اسم القسم">
                        </div>
                        <div class="form-group">
                            <label for="description">الملاحظات</label>
                            <textarea class="form-control" name="description" id="description" placeholder="الملاحظات"
                                rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 100px;"></textarea>
                        </div>
                        <div class="modal-footer" style="border-top: none">
                            <button class="btn ripple btn-success" type="submit">اضافة</button>
                            <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal effects-->
    {{-- Start Edit modal --}}
    <!-- Modal effects -->
    <div class="modal" id="modaledit">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">تعديل القسم</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('sections') }}" class="form-horizontal" method="POST">
                        @csrf
                        {{-- {{method_field('PUT')}} --}}
                        <input name="_method" type="hidden" value="PUT">
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="id" id="section_id">
                        </div>
                        <div class="form-group">
                            <label for="inputNameEdit">اسم القسم</label>
                            <input type="text" class="form-control" name="section_name" id="inputNameEdit"
                                placeholder="اسم القسم">
                        </div>
                        <div class="form-group">
                            <label for="descriptionEdit">الملاحظات</label>
                            <textarea class="form-control" name="description" id="descriptionEdit" placeholder="الملاحظات"
                                rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 100px;"></textarea>
                        </div>
                        <div class="modal-footer" style="border-top: none">
                            <button class="btn ripple btn-info" type="submit">تعديل</button>
                            <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal effects-->
    {{-- End Edit modal --}}
    {{-- Start delete modal --}}
    <!-- Modal effects -->
    <div class="modal" id="modaldelete">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">حذف القسم</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('sections') }}" class="form-horizontal" method="POST">
                        @csrf
                        <input name="_method" type="hidden" value="delete">
                        <input type="hidden" class="form-control" name="id" id="section_iddelete">
                        <div class="form-group">
                            <label for="inputNameEdit">هل انت متاكد من عملية الحذف</label>
                            <input type="text" disabled="disabled" class="form-control" name="section_name"
                                id="inputNamedelete" placeholder="اسم القسم">
                        </div>
                        <div class="modal-footer">
                            <button class="btn ripple btn-danger" type="submit">تاكيد</button>
                            <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal effects-->
    {{-- End delete modal --}}
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- Internal Modal js-->
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('a#edit').on('click', function(event) {
                event.preventDefault();
                var id = $(this).data('section_id');
                var name = $(this).data('section_name');
                var description = $(this).data('description');
                var section_id = $('#section_id');
                var nameEdit = $('#inputNameEdit');
                var descriptionEdit = $('#descriptionEdit');
                section_id.val(id);
                nameEdit.val(name);
                descriptionEdit.val(description);
            });
            $('a#delete').on('click', function(event) {
                event.preventDefault();
                var id = $(this).data('section_id');
                var name = $(this).data('section_name');
                var section_id = $('#section_iddelete');
                var namedelete = $('#inputNamedelete');
                section_id.val(id);
                namedelete.val(name);
            });
        });

    </script>
@endsection
