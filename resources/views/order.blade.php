@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    <h4>Dashboard</h4>
    <p class="text-muted">This is the dashboard page</p>

    <button
      class="btn btn-primary btn-sm viewUser" 
      data-bs-toggle="modal" 
      data-bs-target="#ingridientModalAdd">
      Add Ingrident
    </button>
    {{-- table --}}
        {{-- Table for grades - Main Table --}}
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>price</th>
                <th>Recipe</th>
                <th>Description</th>
                <th>payment Method</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        @foreach($tblOrder as $products)
            <tr>
                <td>{{ $products->id }}</td>
                <td>{{ $products->customerName }}</td>
                <td>{{ $products->total }}</td>
                <td>{{ $products->paymentChange }}</td>
                <td>{{ $products->payment }}</td>
                <td>{{ $products->paymentMode }}</td>
                <td>{{ $products->status }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
      {{ $tblOrder->links() }}
    </div>

    {{-- Modal --}}
    {{-- Modal for Add Order --}}
    <div class="modal fade" id="ingridientModalAdd" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">Ingrident Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/ingridient/store" method="POST">
              @csrf
              <div class="form-group">
                <input type="text" class="form-control mb-2" id="editSubject" name="ingridientName" placeholder="Customer Name" required>
                <input type="text" class="form-control mb-2" id="editStudent" name="stock" placeholder="Stock" required>
                <input type="text" class="form-control mb-2" id="editStudent" name="price" placeholder="Price" required>
              </div>
              <div class="modal-footer">
            <button type="submit" class="btn btn-secondary">Add Ingridient</button>
          </div>
            </form>
          </div>
          
        </div>
      </div>
    </div>

@endsection
