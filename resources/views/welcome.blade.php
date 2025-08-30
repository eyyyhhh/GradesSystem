<!DOCTYPE html>
<html>
<head>
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
    <title>Grades Table</title>
    <style>
        table {
            border-collapse: collapse;
            width: 60%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px 12px;
            text-align: center;
        }
        th {
            background: #f4f4f4;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Grades Table</h2>
   <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grades as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->grades }}</td>
                <td>{{ $user->student }}</td>
                 <td>
                    <!-- Button to trigger modal -->
                    <button 
                        class="btn btn-primary btn-sm viewUser" 
                        data-bs-toggle="modal" 
                        data-bs-target="#userModal"
                        data-id="{{ $user->id }}"
                        data-name="{{ $user->grades }}"
                        data-email="{{ $user->student }}"
                        data-subject="{{ $user->subject}}">
                        View
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $grades->links() }}
    </div>

     <!-- Bootstrap Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">User Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <p><strong>ID:</strong> <span id="modalUserId"></span></p>
              <p><strong>Name:</strong> <span id="modalUserName"></span></p>
               <p><strong>Email:</strong> <span id="modalUserSubject"></span></p>
              <p><strong>Email:</strong> <span id="modalUserEmail"></span></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>


     <!-- Script to populate modal -->
    <script>
        document.querySelectorAll('.viewUser').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('modalUserId').textContent = this.dataset.id;
                document.getElementById('modalUserName').textContent = this.dataset.name;
                document.getElementById('modalUserEmail').textContent = this.dataset.email;
                document.getElementById('modalUserSubject').textContent = this.dataset.subject;
            });
        });
    </script>

</body>
</html>
