<table id="datatables" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Duration</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @forelse($syncs as $key => $sync)
        <tr>
            <td style="white-space: nowrap" data-order="{{ $key }}">Render #{{ $sync->id }}</td>
            <td>{{ $sync->duration }} milliseconds</td>
            <td>{{ $sync->created_at->diffForHumans() }}</td>
            <td style="white-space: nowrap;">
                <a href="{{ route('sync.show', $sync) }}" class="btn btn-xs btn-primary">View</a>
            </td>
        </tr>
    @empty
        <tr>
            <td align="center" colspan="5"><strong>No synchronizations</strong></td>
        </tr>
    @endforelse
    
    </tbody>
</table>