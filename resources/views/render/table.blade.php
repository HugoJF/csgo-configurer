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
    @forelse($renders as $key => $render)
        <tr>
            <td style="white-space: nowrap" data-order="{{ $key }}">Render #{{ $render->id }}</td>
            <td>{{ $render->duration }} milliseconds</td>
            <td>{{ $render->created_at->diffForHumans() }}</td>
            <td style="white-space: nowrap;">
                <a href="{{ route('render.show', $render) }}" class="btn btn-xs btn-primary">View</a>
            </td>
        </tr>
    @empty
        <tr>
            <td align="center" colspan="5"><strong>No renders</strong></td>
        </tr>
    @endforelse
    
    </tbody>
</table>