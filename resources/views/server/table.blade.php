@push('head')
    <style rel="stylesheet">
        .blur {
            filter: blur(4px);
            transition: 3s -webkit-filter ease-in-out;
        }
        
        .blur:hover {
            filter: none;
        }
    </style>
@endpush

<table id="datatables" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>@lang('messages.server-name')</th>
        <th>@lang('messages.server-ip')</th>
        <th>@lang('messages.server-port')</th>
        <th>@lang('messages.server-password')</th>
        <th>@lang('messages.server-ftp-host')</th>
        <th>@lang('messages.server-ftp-user')</th>
        <th>@lang('messages.server-ftp-password')</th>
        <th>@lang('messages.server-ftp-root')</th>
        <th>@lang('messages.last-update')</th>
        <th>@lang('messages.actions')</th>
    </tr>
    </thead>
    <tbody>
    @forelse($servers as $key=>$server)
        <tr>
            <td data-order="{{ $key }}">{{ $server->name }}</td>
            <td>{{ $server->ip }}</td>
            <td>{{ $server->port }}</td>
            <td><span class="blur">{{ $server->password }}</span></td>
            <td>{{ $server->ftp_host }}</td>
            <td>{{ $server->ftp_user }}</td>
            <td><span class="blur">{{ $server->ftp_password }}</span></td>
            <td>{{ $server->ftp_root }}</td>
            <td>{{ $server->updated_at->diffForHumans() }}</td>
            
            <td style="white-space: nowrap;">
                <a href="{{ route('server.show', $server) }}" class="btn btn-xs btn-success">View</a>
                <a href="{{ route('server.edit', $server) }}" class="btn btn-xs btn-primary">Edit</a>
                <a href="{{ route('server.render', $server) }}" class="btn btn-xs btn-primary">Render</a>
                <a href="{{ route('server.sync', $server) }}" class="btn btn-xs btn-primary">Sync</a>
                {!! Form::open(['route' => ['server.delete', $server], 'method' => 'DELETE', 'style' => 'display:inline']) !!}
                <button class="btn btn-xs btn-danger">Delete</button>
                {!! Form::close() !!}
            </td>
        </tr>
    @empty
        
        <tr>
            <td align="center" colspan="14"><strong>No servers to display</strong></td>
        </tr>
    @endforelse
    
    </tbody>
</table>