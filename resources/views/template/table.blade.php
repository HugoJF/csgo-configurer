<table id="datatables" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Folder</th>
        <th>Modified At</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach($templates as $key=>$template)
        <tr>
            <td data-order="{{ $key }}">{{ $template->name }}</td>
            <td>{{ $template->description }}</td>
            <td>{{ $template->folder }}</td>
            <td>{{ $template->modified_at->diffForHumans() }}</td>
            
            <td style="white-space: nowrap;">
                <a href="{{ route('template.show', $template) }}" class="btn btn-xs btn-success">View</a>
            </td>
        </tr>
    @endforeach
    
    </tbody>
</table>