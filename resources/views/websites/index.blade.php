@extends('layouts.app')

@section('content')
    <!-- ========== title-wrapper start ========== -->
    <div class="title-wrapper pt-30">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="title mb-30">
                    <h2>{{ __('All websites') }}</h2>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- ========== title-wrapper end ========== -->

    <div class="card-styles">
        <div class="card-style-3 mb-30">
            <div class="card-content">

                @session('success')
                    <div class="alert-box success-alert">
                        <div class="alert">
                            <h4 class="alert-heading">Success</h4>
                            <p class="text-medium">
                                {{ $value }}
                            </p>
                        </div>
                    </div>
                @endsession

                <a href="{{ route('websites.create') }}" class="main-btn primary-btn square-btn btn-hover">
                    <i class="lni lni-plus"></i>
                    Add website
                </a>
                <div class="table-wrapper table-responsive">
                    <table class="table striped-table">
                        <thead>
                            <tr>
                                <th>
                                    <h6>Website url</h6>
                                </th>
                                <th>
                                    <h6>admin Email</h6>
                                </th>
                                <th>
                                    <h6>Admin phone</h6>
                                </th>
                                <th>
                                    <h6>Status</h6>
                                </th>
                                <th>
                                    <h6>Actions</h6>
                                </th>
                            </tr>
                            <!-- end table row-->
                        </thead>
                        <tbody>
                            @foreach ($websites as $website)
                                <tr>
                                    <td>
                                        <p>{{ $website->url }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $website->email }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $website->phone }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $website->is_up }}</p>
                                    </td>
                                    <td>
                                        <a href="{{ route('websites.edit', $website) }}"
                                            class="main-btn primary-btn btn-hover">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                            <!-- end table row -->
                        </tbody>
                    </table>
                    <!-- end table -->

                    {{ $websites->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection
