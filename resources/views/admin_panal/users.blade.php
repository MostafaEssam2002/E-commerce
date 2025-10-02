@extends('admin_panal.master')

@section("content")
<div class="container mt-4">
    <h2>Users Table</h2>

    <div id="loading" class="text-center my-4">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="usersTable" style="display: none;">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registration Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
                <!-- Data will appear here -->
            </tbody>
        </table>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bi me-2" id="toastIcon"></i>
            <strong class="me-auto" id="toastTitle">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastBody">
            Message here
        </div>
    </div>
</div>

@endsection

@section('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    .toast {
        min-width: 300px;
    }
    .toast-header.bg-success {
        background-color: #198754 !important;
        color: white;
    }
    .toast-header.bg-danger {
        background-color: #dc3545 !important;
        color: white;
    }
    .toast-header.bg-warning {
        background-color: #ffc107 !important;
        color: black;
    }
</style>
@endsection

@section('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Toast notification function
    function showToast(title, message, type = 'success') {
        var toastEl = document.getElementById('liveToast');
        var toastHeader = toastEl.querySelector('.toast-header');
        var toastIcon = document.getElementById('toastIcon');
        var toastTitle = document.getElementById('toastTitle');
        var toastBody = document.getElementById('toastBody');

        // Reset classes
        toastHeader.className = 'toast-header';
        toastIcon.className = 'bi me-2';

        // Set icon and color based on type
        if(type === 'success') {
            toastHeader.classList.add('bg-success');
            toastIcon.classList.add('bi-check-circle-fill');
        } else if(type === 'error') {
            toastHeader.classList.add('bg-danger');
            toastIcon.classList.add('bi-x-circle-fill');
        } else if(type === 'warning') {
            toastHeader.classList.add('bg-warning');
            toastIcon.classList.add('bi-exclamation-triangle-fill');
        }

        toastTitle.textContent = title;
        toastBody.textContent = message;

        var toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 3000
        });
        toast.show();
    }

    // Fetch data from server
    $.ajax({
        url: '/api/users', // Change the URL according to your route
        method: 'GET',
        dataType: 'json',
        success: function(users) {
            // Hide loading screen
            $('#loading').hide();

            // Show table
            $('#usersTable').show();

            // Clear old content
            $('#usersTableBody').empty();

            // Add data to table
            if(users.length > 0) {
                $.each(users, function(index, user) {
                    var row = '<tr>' +
                        '<td>' + user.id + '</td>' +
                        '<td>' + user.name + '</td>' +
                        '<td>' + user.email + '</td>' +
                        '<td>' + new Date(user.created_at).toLocaleDateString('en-US') + '</td>' +
                        '<td>' +
                            '<button class="btn btn-sm btn-primary edit-btn" data-id="' + user.id + '">Edit</button> ' +
                            '<button class="btn btn-sm btn-danger delete-btn" data-id="' + user.id + '">Delete</button>' +
                        '</td>' +
                    '</tr>';

                    $('#usersTableBody').append(row);
                });
            } else {
                $('#usersTableBody').append('<tr><td colspan="5" class="text-center">No data available</td></tr>');
            }

            // Initialize DataTables with pagination and sorting
            $('#usersTable').DataTable({
                "pageLength": 10, // Number of rows per page
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]], // Page length options
                "order": [[0, 'asc']], // Default sort by ID ascending
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "Showing 0 to 0 of 0 entries",
                    "infoFiltered": "(filtered from _MAX_ total entries)",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    },
                    "zeroRecords": "No matching records found"
                },
                "columnDefs": [
                    { "orderable": false, "targets": 4 } // Disable sorting on Actions column
                ],
                "responsive": true
            });
        },
        error: function(xhr, status, error) {
            $('#loading').hide();
            showToast('Error', 'An error occurred while loading data: ' + error, 'error');
        }
    });

    // Handle edit button
    $(document).on('click', '.edit-btn', function() {
        var userId = $(this).data('id');

        // Get user data from the row
        var row = $(this).closest('tr');
        var userName = row.find('td:eq(1)').text();
        var userEmail = row.find('td:eq(2)').text();

        // Create modal for editing
        var editModal = `
            <div class="modal fade" id="editModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editForm">
                                <input type="hidden" id="editUserId" value="${userId}">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" id="editUserName" value="${userName}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" id="editUserEmail" value="${userEmail}" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="saveEdit">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if any
        $('#editModal').remove();

        // Append and show modal
        $('body').append(editModal);
        var modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    });

    // Handle save edit
    $(document).on('click', '#saveEdit', function() {
        var userId = $('#editUserId').val();
        var userName = $('#editUserName').val();
        var userEmail = $('#editUserEmail').val();

        // Validate
        if(!userName || !userEmail) {
            showToast('Warning', 'Please fill all fields', 'warning');
            return;
        }

        // Send AJAX request to update
        $.ajax({
            url: '/api/users/' + userId,
            method: 'PUT',
            data: {
                name: userName,
                email: userEmail,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Update the table row
                var table = $('#usersTable').DataTable();
                var rowIndex = $('button.edit-btn[data-id="' + userId + '"]').closest('tr').index();

                // Update row data
                $('button.edit-btn[data-id="' + userId + '"]').closest('tr').find('td:eq(1)').text(userName);
                $('button.edit-btn[data-id="' + userId + '"]').closest('tr').find('td:eq(2)').text(userEmail);

                // Close modal
                $('#editModal').modal('hide');

                // Show success message
                showToast('Success', 'User updated successfully!', 'success');
            },
            error: function(xhr, status, error) {
                showToast('Error', 'Error updating user: ' + error, 'error');
            }
        });
    });

    // Handle delete button
    $(document).on('click', '.delete-btn', function() {
        var userId = $(this).data('id');
        var row = $(this).closest('tr');

        // Create confirmation modal
        var confirmModal = `
            <div class="modal fade" id="confirmModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Confirm Delete</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this user?</p>
                            <p class="text-muted">This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if any
        $('#confirmModal').remove();

        // Append and show modal
        $('body').append(confirmModal);
        var modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        modal.show();

        // Handle confirm delete
        $(document).on('click', '#confirmDelete', function() {
            // Send AJAX request to delete
            $.ajax({
                url: '/api/users/' + userId,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Remove row from DataTable
                    var table = $('#usersTable').DataTable();
                    table.row(row).remove().draw();

                    // Close modal
                    $('#confirmModal').modal('hide');

                    // Show success message
                    showToast('Success', 'User deleted successfully!', 'success');
                },
                error: function(xhr, status, error) {
                    showToast('Error', 'Error deleting user: ' + error, 'error');
                }
            });
        });
    });
});
</script>
@endsection
