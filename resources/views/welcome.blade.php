
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



@extends('layouts.dashboard')  <!-- Use the layout -->

@section('title', 'Home')       <!-- Page title -->

@section('content')  
    <h2 style="text-align:center;"> Product</h2>
     <button
        class="btn btn-primary btn-sm viewUser" 
        data-bs-toggle="modal" 
        data-bs-target="#productModalAdd">
      Add Product
     </button>

     <button
        class="btn btn-primary btn-sm viewUser" 
        data-bs-toggle="modal" 
        data-bs-target="#recipeModalAdd">
      Add Recipe
     </button>

    <button
      class="btn btn-primary btn-sm viewUser" 
      data-bs-toggle="modal" 
      data-bs-target="#ingridientModalAdd">
      Add Ingrident
    </button>

     <button
        class="btn btn-primary btn-sm viewUser" 
        data-bs-toggle="modal" 
        data-bs-target="#categoryModalAdd">
      Add Category
     </button>

    {{-- Filter and Search --}}
    {{-- <form id="filterForm" method="GET" action="" class="d-flex gap-2 mb-3">

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

     </form> --}}

    {{-- Table for grades - Main Table --}}
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Prooduct</th>
                <th>price</th>
                <th>Recipe</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
          @foreach($product as $products)
          <tr>
            <td>{{ $products->id }}</td>
            <td>{{ $products->productName }}</td>
            <td>{{ $products->price }}</td>
            <td>{{ $products->description }}</td>
            <td>
              <img 
                src="{{ asset('storage/' . $products->productPicture) }}" 
                width="80" 
                height="80"
                style="object-fit: cover;"
                >
            </td>
            <td>
              @foreach($recipes[$products->id] ?? [] as $ing)
                  <span>{{ $ing->ingridientName }}</span><br>
              @endforeach
            </td>
            <td>
              <!-- Button to trigger modal -->
              <button 
                class="btn btn-primary btn-sm viewProduct" 
                data-bs-toggle="modal" 
                data-bs-target="#productModalView"
                data-id="{{ $products->id }}"
                data-product_name="{{ $products->productName }}"
                data-price="{{ $products->price }}"
                data-recipe="{{ implode(', ', collect($recipes[$products->id] ?? [])->pluck('ingridientName')->toArray()) }}"
                data-desc="{{ $products->description}}">
                  View
              </button> 
              <button 
                class="btn btn-primary btn-sm updateProduct" 
                data-bs-toggle="modal" 
                data-bs-target="#userUpdateView"
                data-product_id="{{ $products->id }}"
                data-product_name_update="{{ $products->productName }}"
                data-price_update="{{ $products->price }}"
                data-desc_update="{{ $products->description}}"
                data-ingredients='@json($recipes[$products->id] ?? [])'>
                  Update
                </button> 
                <form action="{{ route('product.delete', $products->id) }}" method="POST" style="display:inline;">
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
      {{ $product->links() }}
    </div>
    <div class="container mt-4">
      <div class="row">
        @foreach($product as $products)
          <div class="col-md-3 mb-4 viewProduct"
           data-bs-toggle="modal" 
                data-bs-target="#productModalView"
                data-id="{{ $products->id }}"
                data-product_name="{{ $products->productName }}"
                data-price="{{ $products->price }}"
                data-recipe="{{ implode(', ', collect($recipes[$products->id] ?? [])->pluck('ingridientName')->toArray()) }}"
                data-desc="{{ $products->description}}">
            <div class="card h-100 shadow-sm">
              <img 
                src="{{ asset('storage/' . $products->productPicture) }}"
                class="card-img-top"
                style="height:200px; object-fit:cover;"
                alt="Product Image">

              <div class="card-body">
                <h5 class="card-title">{{ $products->productName }}</h5>
                <p class="card-text">₱{{ $products->price }}</p>
                <p class="card-text text-muted">
                  {{ $products->description }}
                </p>
              </div>

            </div>
          </div>
        @endforeach
      </div>
    </div>


    <!-- Bootstrap Modals -->
    {{-- Add Modal - Product --}}
    <div class="modal fade" id="productModalAdd" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">Product Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/product/add" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <input type="text" class="form-control mb-2" name="productName" placeholder="Product Name" required>
                <input type="text" class="form-control mb-2"  name="price" placeholder="Price" required>
                <input type="text" class="form-control mb-2"  name="description" placeholder="Description" required>
                <label>Categories</label>
                <select name="categories[]" id="categories" multiple  class="form-control mb-2">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->category }}</option>
                    @endforeach
                </select>
                <div id="ingredientWrapper">
                  <div class="ingredient-row mb-3">
                    <select class="form-control mb-2" name="ingridientId[]" required>
                      <option value="">Select Ingredient</option>
                      @foreach ($queryIngridient as $ingridient)
                        <option value="{{ $ingridient->Id }}">
                          {{ $ingridient->ingridientName }}
                        </option>
                      @endforeach
                    </select>
                    <input type="number" class="form-control mb-2" name="qty[]" placeholder="Quantity" required>
                  </div>
                </div>
                <!-- Add Row Button -->
                <button type="button" id="addRow" class="btn btn-secondary btn-sm">
                  Add Ingredient
                </button>
                 <input type="file" name="photo">
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-secondary">Add Product</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    {{-- View Modal - Product--}}
    <div class="modal fade" id="productModalView" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">Product Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><strong>ID:</strong> <span id="modalProductId"></span></p>
            <p><strong>Product:</strong> <span id="modalProductName"></span></p>
            <p><strong>Price:</strong> <span id="modalProductPrice"></span></p>
            <p><strong>Description:</strong> <span id="modalProductDescription"></span></p>
            <hr>
            <p><strong>Recipe:</strong> <span id="modalProductRecipe"></span></p>
          </div>
        </div>
      </div>
    </div>
    {{-- Update Modal - Product--}}
    <div class="modal fade" id="userUpdateView"  tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">Edit Grade</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/product/update/{id}" id="editForm" method="POST">
              @csrf
              @method('PUT')
              <div class="form-group">
                <input type="text" class="form-control mb-2" id="editProductId" name="id" placeholder="" readonly required>
                <input type="text" class="form-control mb-2" id="editProduct" name="productName" placeholder="" readonly required>
                <input type="text" class="form-control mb-2" id="editPrice" name="price" placeholder="" readonly required>
                <input type="text" class="form-control mb-2" id="editDescription" name="description" placeholder="" readonly required>
              </div>
              
              <div id="editIngredientWrapper"></div>
              <button type="button"
                      id="addEditRow"
                      class="btn btn-secondary btn-sm d-none">
                Add Ingredient
              </button>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="enableEditBtn">Edit</button>
                <button type="submit" class="btn btn-primary d-none" id="saveBtn">Save</button>
              </div>     
            </form>
          </div>
        </div>
      </div>
    </div>
    {{-- Add Modal - Recipe --}}
    <div class="modal fade" id="recipeModalAdd" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">Add Recipe</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/recipe/store" method="POST">
              @csrf
              <div class="form-group">
                <input type="text" class="form-control mb-2" id="editRecipeId" name="recipeId" placeholder="Recipe" required>
                {{-- <div id="ingredientWrapper">
                  <div class="ingredient-row mb-3">
                    <select class="form-control mb-2" name="ingridientId[]" required>
                      <option value="">Select Ingredient</option>
                      @foreach ($queryIngridient as $ingridient)
                        <option value="{{ $ingridient->Id }}">
                          {{ $ingridient->ingridientName }}
                        </option>
                      @endforeach
                    </select>
                    <input type="number" class="form-control mb-2" name="qty[]" placeholder="Quantity" required>
                  </div>
                </div>
                <!-- Add Row Button -->
                <button type="button" id="addRow" class="btn btn-secondary btn-sm">
                  Add Ingredient
                </button> --}}
                <div class="modal-footer">
                  <button type="submit" class="btn btn-secondary">Add Recipe</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    {{-- Add Modal - Ingridient --}}
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
                <input type="text" class="form-control mb-2" id="editSubject" name="ingridientName" placeholder="Ingridient" required>
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
    {{-- Add Modal - Category --}}
    <div class="modal fade" id="categoryModalAdd" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">Category Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/category/store" method="POST">
              @csrf
              <div class="form-group">
                <input type="text" class="form-control mb-2" id="editSubject" name="category" placeholder="Category" required>
                <input type="text" class="form-control mb-2" id="editStudent" name="description" placeholder="Description" required>
              </div>
              <div class="modal-footer">
            <button type="submit" class="btn btn-secondary">Add Category</button>
          </div>
            </form>
          </div>
          
        </div>
      </div>
    </div>

     <!-- Script to populate modal -->
    <script>
      //category
$('#productModalAdd').on('shown.bs.modal', function() {
    $('#categories').select2({
        placeholder: "Select categories",
        width: '100%',
        dropdownParent: $('#productModalAdd') // Important!
    });
});
      document.getElementById('addRow').addEventListener('click', function () {
        let wrapper = document.getElementById('ingredientWrapper');

        // Clone first ingredient row
        let newRow = wrapper.firstElementChild.cloneNode(true);

        // Clear values
        newRow.querySelector('select').value = "";
        newRow.querySelector('input[name="qty[]"]').value = "";

        // Append
        wrapper.appendChild(newRow);
    });

     function createIngredientRow(selectedId = '', qty = '') {
    return `
      <div class="ingredient-row mb-2 d-flex gap-2">
        <select class="form-control" name="ingridientId[]" required>
          <option value="">Select Ingredient</option>
          @foreach ($queryIngridient as $ing)
            <option value="{{ $ing->Id }}"
              ${selectedId == '{{ $ing->Id }}' ? 'selected' : ''}>
              {{ $ing->ingridientName }}
            </option>
          @endforeach
        </select>

        <input type="number"
               class="form-control"
               name="qty[]"
               value="${qty}"
               placeholder="Qty"
               required>

        <button type="button" class="btn btn-danger removeRow">✕</button>
      </div>
    `;
  }

      document.querySelectorAll('.updateProduct').forEach(button =>{
        button.addEventListener('click', function(){
              console.log(JSON.parse(this.dataset.ingredients));
            // fill input fields
          document.getElementById('editProductId').value = this.dataset.product_id;
          document.getElementById('editProduct').value= this.dataset.product_name_update;
          document.getElementById('editPrice').value = this.dataset.price_update;
          document.getElementById('editDescription').value= this.dataset.desc_update;

  
          // update form action
          document.getElementById('editForm').action = "/product/update/" + this.dataset.product_id;


            // Ingredients
          const ingredients = JSON.parse(this.dataset.ingredients);
          const wrapper = document.getElementById('editIngredientWrapper');
          wrapper.innerHTML = '';

          ingredients.forEach(ing => {
            wrapper.insertAdjacentHTML(
              'beforeend',
              createIngredientRow(ing.ingridientId, ing.qty)
            );
          });

            // Lock fields initially
          document.querySelectorAll('#editForm input, #editForm select')
            .forEach(el => el.readOnly = true);

          document.getElementById('addEditRow').classList.add('d-none');
          saveBtn.classList.add('d-none');
          enableEditBtn.classList.remove('d-none');

          // reset: disable editing and hide save button
          document.getElementById('editProductId').readOnly = true;
          document.getElementById('editProduct').readOnly = true;
          document.getElementById('editPrice').readOnly = true;
          document.getElementById('editDescription').readOnly = true;

        });
      });

      document.getElementById('enableEditBtn').addEventListener('click', function(){

    
        // enable editing
        document.getElementById('editProductId').readOnly = false;
        document.getElementById('editProduct').readOnly = false;
        document.getElementById('editPrice').readOnly = false;
        document.getElementById('editDescription').readOnly = false;

        // show Save button
        document.getElementById('enableEditBtn').classList.add('d-none');
        document.getElementById('saveBtn').classList.remove('d-none');

          document.querySelectorAll('#editForm input, #editForm select')
          .forEach(el => el.readOnly = false);

        document.getElementById('addEditRow').classList.remove('d-none');
        enableEditBtn.classList.add('d-none');
        saveBtn.classList.remove('d-none');
      });

      document.getElementById('addEditRow').addEventListener('click', () => {
  document.getElementById('editIngredientWrapper')
    .insertAdjacentHTML('beforeend', createIngredientRow());
});

document.addEventListener('click', e => {
  if (e.target.classList.contains('removeRow')) {
    e.target.closest('.ingredient-row').remove();
  }
});


 
    document.querySelectorAll('.viewProduct').forEach(button => {
        button.addEventListener('click', function() {
          document.getElementById('modalProductId').textContent = this.dataset.id;
          document.getElementById('modalProductName').textContent = this.dataset.product_name;
          document.getElementById('modalProductPrice').textContent = this.dataset.price;
          document.getElementById('modalProductRecipe').textContent = this.dataset.recipe;
          document.getElementById('modalProductDescription').textContent = this.dataset.desc;
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

@endsection
