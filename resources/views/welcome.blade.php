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
     <button
        class="btn btn-primary btn-sm viewUser" 
        data-bs-toggle="modal" 
        data-bs-target="#userModalAdd">
      Add Grade
     </button>

     <button
        class="btn btn-primary btn-sm viewUser" 
        data-bs-toggle="modal" 
        data-bs-target="#subjectModalAdd">
      Add Subject
     </button>

    <button
      class="btn btn-primary btn-sm viewUser" 
      data-bs-toggle="modal" 
      data-bs-target="#sectionModalAdd">
      Add Section
    </button>

    <form id="filterForm" method="GET" action="" class="d-flex gap-2 mb-3">

      <!-- Search -->
      <input 
          type="text" 
          name="search" 
          id="searchInput"
          class="form-control"
          placeholder="Search student or subject"
          value="{{ request('search') }}"
      >

      <!-- Dropdown -->
      <select name="subject_id" id="subjectSelect" class="form-control">
          <option value="">All Subjects</option>
          @foreach ($subjectDropdowns as $sub)
              <option value="{{ $sub->ID }}" 
                  {{ request('subject_id') == $sub->ID ? 'selected' : '' }}>
                  {{ $sub->subject_name }}
              </option>
          @endforeach
      </select>

     </form>

    {{-- Table for grades - Main Table --}}
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Grade</th>
                <th>Subject</th>
                <th>Student</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grades as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->grades }}</td>
                <td>{{ $user->subject_name }}</td>
                <td>{{ $user->student }}</td>
                <td>
                    <!-- Button to trigger modal -->
                    <button 
                        class="btn btn-primary btn-sm viewUser" 
                        data-bs-toggle="modal" 
                        data-bs-target="#userModalView"
                        data-id="{{ $user->id }}"
                        data-grades="{{ $user->grades }}"
                        data-subject_names="{{ $user->subject_name }}"
                        data-students="{{ $user->student}}">
                        View
                    </button>
                    <button 
                        class="btn btn-primary btn-sm updateUser" 
                        data-bs-toggle="modal" 
                        data-bs-target="#userUpdateView"
                        data-id="{{ $user->id }}"
                        data-grade="{{ $user->grades }}"
                        data-student="{{ $user->student }}"
                        data-subject="{{ $user->subject_id }}"
                        data-subject_name="{{ $user->subject_name}}">
                        Update
                    </button>
                    <form action="{{ route('grade.delete', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete this grade?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $grades->links() }}
    </div>

    {{-- Table for Subjects --}}
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>subject</th>
                <th>description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subjects as $user)
            <tr>
                <td>{{ $user->ID }}</td>
                <td>{{ $user->subject_name }}</td>
                <td>{{ $user->description }}</td>
                
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $subjects->links() }}
    </div>

    <!-- Bootstrap Modals -->
    {{-- Add Modal - Grades --}}
    <div class="modal fade" id="userModalAdd" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">User Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/grade/store" method="POST">
              @csrf
              <div class="form-group">
                <input type="text" class="form-control mb-2" name="grades" placeholder="Grade" required>
                <input type="text" class="form-control mb-2"  name="subject" placeholder="Subject" required>
                <input type="text" class="form-control mb-2"  name="student" placeholder="Student" required>
              </div>
              <div class="modal-footer">
            <button type="submit" class="btn btn-secondary">Add Grade</button>
          </div>
            </form>
          </div>
          
        </div>
      </div>
    </div>

    {{-- View Modal - Grades--}}
    <div class="modal fade" id="userModalView" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">User Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <p><strong>ID:</strong> <span id="modalUserId"></span></p>
              <p><strong>Grade:</strong> <span id="modalGrade"></span></p>
              <p><strong>Subject:</strong> <span id="modalSubject"></span></p>
              <p><strong>Student:</strong> <span id="modalStudent"></span></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Save</button>
          </div>
        </div>
      </div>
    </div>

    {{-- Update Modal - Grades--}}
    <div class="modal fade" id="userUpdateView"  tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">Edit Grade</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/grade/update/{id}" id="editForm" method="POST">
              @csrf
              @method('PUT')

              <div class="form-group">
                <input type="text" class="form-control mb-2" id="editGrades" name="grades" placeholder="Grade" readonly required>
                <select class="form-control mb-2" name="subject" id="editSubject">
                   @foreach ($subjectDropdown as $sub)
                    <option  value="{{ $sub->ID }}">
                      {{ $sub->subject_name }}
                    </option> 
                   @endforeach 
                </select>
                <input type="text" class="form-control mb-2" id="editStudent" name="student" placeholder="Student" readonly required>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="enableEditBtn">Edit</button>
                <button type="submit" class="btn btn-primary d-none" id="saveBtn">Save</button>

              </div>     
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- Add Modal - Subjects --}}
    <div class="modal fade" id="subjectModalAdd" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">User Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/subject/store" method="POST">
              @csrf
              <div class="form-group">
                <input type="text" class="form-control mb-2" id="editSubject" name="subject" placeholder="Subject" required>
                <input type="text" class="form-control mb-2" id="editStudent" name="description" placeholder="Description" required>
              </div>
              <div class="modal-footer">
            <button type="submit" class="btn btn-secondary">Add Grade</button>
          </div>
            </form>
          </div>
          
        </div>
      </div>
    </div>

    {{-- Add Modal - Section --}}
    <div class="modal fade" id="sectionModalAdd" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">Add Section</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/section/store" method="POST">
              @csrf
              <div class="form-group">
                <input type="text" class="form-control mb-2" id="editSubject" name="gradeLevel" placeholder="Grade Level" required>
                <input type="text" class="form-control mb-2" id="editSubject" name="section" placeholder="Section" required>
                <input type="text" class="form-control mb-2" id="editStudent" name="teacher" placeholder="Teacher" required>
              </div>
              <div class="modal-footer">
            <button type="submit" class="btn btn-secondary">Add Section</button>
          </div>
            </form>
          </div>
          
        </div>
      </div>
    </div>

     <!-- Script to populate modal -->
    <script>
      document.querySelectorAll('.updateUser').forEach(button =>{
        button.addEventListener('click', function(){
          
            // fill input fields
          document.getElementById('editGrades').value = this.dataset.grade;
          document.getElementById('editSubject').value= this.dataset.subject;
          document.getElementById('editStudent').value = this.dataset.student;

  
          // update form action
          document.getElementById('editForm').action = "/grade/update/" + this.dataset.id;

          // reset: disable editing and hide save button
          document.getElementById('editGrades').readOnly = true;
          document.getElementById('editStudent').readOnly = true;
          document.getElementById('editSubject').disabled  = true;

         
        });
      });

      document.getElementById('enableEditBtn').addEventListener('click', function(){

        // enable editing
        document.getElementById('editGrades').readOnly = false;
        document.getElementById('editSubject').disabled = false;
        document.getElementById('editStudent').readOnly = false;

        // show Save button
        document.getElementById('enableEditBtn').classList.add('d-none');
        document.getElementById('saveBtn').classList.remove('d-none');
      });

      document.querySelectorAll('.viewUser').forEach(button => {
        button.addEventListener('click', function() {
          document.getElementById('modalUserId').textContent = this.dataset.id;
          document.getElementById('modalGrade').textContent = this.dataset.grade;
          document.getElementById('modalSubject').value = this.dataset.subject_name;
          document.getElementById('modalStudent').textContent = this.dataset.student;
        });
      });
    
         // Get elements
    const searchInput = document.getElementById('searchInput');
    const subjectSelect = document.getElementById('subjectSelect');
    const form = document.getElementById('filterForm');

    // Submit form on search input change (debounced)
    let timeout = null;
    searchInput.addEventListener('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            form.submit();
        }, 500); // wait 500ms after typing stops
    });

    // Submit form on dropdown change
    subjectSelect.addEventListener('change', function() {
        form.submit();
    });
    </script>

</body>
</html>
