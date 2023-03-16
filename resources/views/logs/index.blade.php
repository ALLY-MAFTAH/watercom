@extends('layouts.app')
@section('title')
    Activity Logs
@endsection
@section('style')
@endsection
@section('content')
    <div class="card">
        <div class=" card-header">
            <div class="row">
                <div class="col">
                    <div class=" text-left">
                        <h5 class="my-0">
                            <span class="">
                                <b>{{ __(' ACTIVITY LOGS') }}
                                </b>
                            </span>
                        </h5>
                    </div>
                </div>
                <div class="col text-right">

                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="data-tebo1"
                class="dt-responsive nowrap table shadow rounded-3 table-responsive-sm  table-striped table-hover"
                style="width: 100%">
                <thead class="shadow rounded-3">
                    <th style="max-width: 20px">#</th>
                    <th>Time</th>
                    <th>Subject</th>
                    <th>User Id</th>
                    <th>Method</th>
                    <th>Ip</th>
                    <th>URL</th>
                    <th width="300px">User Agent</th>

                </thead>
                <tbody>
                    @if ($logs->count())
                        @foreach ($logs as $key => $log)
                            @php
                                $perfomer = '';
                                $perfomer = App\Models\User::find($log->user_id);
                            @endphp
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $log->updated_at->format('D, d M Y \a\t H:i:s') }} </td>
                                @if ($perfomer)
                                <td>{{ $perfomer->name . ' ' . $log->subject }}</td>
                                @else
                                <td> <span class="text-white bg-danger">Deleted User</span> {{$log->subject }}</td>
                                @endif
                                <td>{{ $log->user_id }}</td>
                                <td><label class="label label-info">{{ $log->method }}</label></td>
                                <td class="text-warning">{{ $log->ip }}</td>
                                <td class="text-success">{{ $log->url }}</td>
                                <td class="text-danger">{{ $log->agent }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
