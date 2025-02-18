@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Contact List</h2>

    <div class="d-flex justify-content-end mb-3">

        <form action="{{ route('contacts.import.process') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
            @csrf
            <input type="file" name="file" accept=".xml" required class="form-control w-auto">
            <button type="submit" class="btn btn-primary">Import Contacts</button>
        </form>

        <a href="{{ route('contacts.export.xml') }}" class="btn btn-success mx-2">Export Contacts</a>
        <a href="{{ route('contacts.create') }}" class="btn btn-success">Add Contact</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <table id="contactTable" class="table table-striped table-bordered mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $contact)
            <tr>
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->phone }}</td>
                <td>
                    <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable for the contacts table
        $('#contactTable').DataTable({
            "paging": true, // Enable pagination
            "searching": true, // Enable search functionality
            "ordering": true, // Enable sorting
            "info": true, // Show info about the table (e.g., "Showing 1 to 10 of 50 entries")
            "lengthChange": false // Disable the option to change the number of rows per page
        });
    });
</script>
@endsection