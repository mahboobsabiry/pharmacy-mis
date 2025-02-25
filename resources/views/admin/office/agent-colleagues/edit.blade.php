@extends('layouts.admin.master')
<!-- Title -->
@section('title', 'ویرایش معلومات همکار نماینده')
<!-- Extra Styles -->
@section('extra_css')
    <!---Fileupload css-->
    <link href="{{ asset('backend/assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet">
    <!---Fancy uploader css-->
    <link href="{{ asset('backend/assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet">
    <!--Sumoselect css-->
    <link href="{{ asset('backend/assets/plugins/sumoselect/sumoselect.css') }}" rel="stylesheet">
@endsection
<!--/==/ End of Extra Styles -->

<!-- Main Content of The Page -->
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <!-- Breadcrumb -->
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">ویرایش معلومات همکار نماینده</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.office.agents.index') }}">@lang('pages.companies.agents')</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.office.agent-colleagues.show', $colleague->id) }}">@lang('global.details')</a></li>
                    <li class="breadcrumb-item active" aria-current="page">ویرایش معلومات همکار نماینده</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">

            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Main Row -->
        <div class="row">
            <div class="col-lg-12">
                <!-- Errors Message -->
                @include('admin.inc.alerts')

                <!-- Card -->
                <div class="card">
                    <!-- Form Title -->
                    <div class="card-header">
                        <h6 class="card-title tx-15 tx-bold mb-1">ویرایش معلومات ({{ $colleague->name }})</h6>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="">
                            <!-- Form -->
                            <form method="post" action="{{ route('admin.office.agent-colleagues.update', $colleague->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Name -->
                                        <div class="form-group @error('name') has-danger @enderror">
                                            <p class="mb-2">@lang('form.name'): <span class="tx-danger">*</span></p>
                                            <input type="text" id="name" class="form-control @error('name') form-control-danger @enderror" name="name" value="{{ $colleague->name ?? old('name') }}" required>

                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Phone -->
                                        <div class="form-group @error('phone') has-danger @enderror">
                                            <p class="mb-2">@lang('form.phone'): <span class="tx-danger">*</span></p>
                                            <input type="text" id="phone" class="form-control @error('phone') form-control-danger @enderror" name="phone" value="{{ $colleague->phone ?? old('phone') }}" required>

                                            @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Phone 2 -->
                                        <div class="form-group @error('phone2') has-danger @enderror">
                                            <p class="mb-2">@lang('form.phone') 2:</p>
                                            <input type="text" id="phone2" class="form-control @error('phone') form-control-danger @enderror" name="phone2" value="{{ $colleague->phone2 ?? old('phone2') }}">

                                            @error('phone2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- ID Card Number -->
                                        <div class="form-group @error('id_number') has-danger @enderror">
                                            <p class="mb-2"> نمبر تذکره: <span class="tx-danger">*</span></p>
                                            <input type="text" id="id_number" class="form-control @error('id_number') form-control-danger @enderror" name="id_number" value="{{ $colleague->id_number ?? old('id_number') }}" required>

                                            @error('id_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Address -->
                                        <div class="form-group @error('address') has-danger @enderror">
                                            <p class="mb-2">@lang('global.address'): <span class="tx-danger">*</span></p>
                                            <input type="text" id="address" class="form-control @error('address') form-control-danger @enderror" name="address" value="{{ $colleague->address ?? old('address') }}" required>

                                            @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Description -->
                                        <div class="form-group @error('info') has-danger @enderror">
                                            <p class="mb-2">@lang('form.extraInfo'):</p>
                                            <textarea name="info" class="form-control @error('info') form-control-danger @enderror">{{ $colleague->info ?? old('info') }}</textarea>

                                            @error('info')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Description -->
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Photo -->
                                        <div class="form-group @error('photo') has-danger @enderror">
                                            <p class="mb-2">@lang('form.photo'):</p>
                                            @if($colleague->image)
                                                <img src="{{ $colleague->image ?? asset('assets/images/avatar-default.jpeg') }}" alt="{{ $colleague->name }}" width="40">
                                            @endif
                                            <input type="file" class="dropify" name="photo" accept="image/*" data-height="200" />
                                            @error('photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Photo -->

                                        <!-- Signature -->
                                        <div class="form-group @error('signature') has-danger @enderror">
                                            <p class="mb-2">امضاء:</p>
                                            @if($colleague->signature)
                                                <img src="{{ asset('storage/agent-colleagues/signatures/' . $colleague->signature) ?? asset('assets/images/avatar-default.jpeg') }}" alt="امضاء" width="40">
                                            @endif
                                            <input type="file" class="dropify" name="signature" accept="image/*" data-height="200" />
                                            @error('signature')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Signature -->
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn ripple btn-primary rounded-2" type="submit">@lang('global.save')</button>
                                </div>
                            </form>
                            <!--/==/ End of Form -->
                        </div>
                    </div>
                    <!--/==/ End of Card Body -->
                </div>
                <!--/==/ End of Card -->
            </div>
        </div>
        <!--/==/ End of Main Row -->
    </div>
@endsection
<!--/==/ End of Main Content of The Page -->

<!-- Extra Scripts -->
@section('extra_js')
    <!--Fileuploads js-->
    <script src="{{ asset('backend/assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <!--Fancy uploader js-->
    <script src="{{ asset('backend/assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
    <!-- Form-elements js-->
    <script src="{{ asset('backend/assets/js/advanced-form-elements.js') }}"></script>

    <!-- Form-elements js-->
    <script src="{{ asset('backend/assets/js/form-elements.js') }}"></script>
@endsection
<!--/==/ End of Extra Scripts -->
