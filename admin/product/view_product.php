<?php
require_once '../../config/database.php';
?>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 for alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container">
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#productModal">
        <i class="fas fa-plus"></i> Add Product
    </button>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">Products List</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Add New Product</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <form id="addProductForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Product Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                <input type="text" name="name" class="form-control" placeholder="Enter product name"
                                    required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Category</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <?php
                                    $catRes = $conn->query("SELECT * FROM categories");
                                    while ($cat = $catRes->fetch_assoc()) {
                                        echo "<option value='{$cat['category_id']}'>{$cat['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Price ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="price" class="form-control" placeholder="0.00"
                                        step="0.01" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Stock Quantity</label>
                                <input type="number" name="stock_quantity" class="form-control" placeholder="e.g. 100"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Product Image</label>
                                <input type="file" name="product_image" class="form-control" id="imgInput"
                                    accept="image/*" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                placeholder="Enter product details..."></textarea>
                        </div>

                        <div class="mb-3 text-center d-none" id="previewContainer">
                            <p class="text-muted small">Image Preview:</p>
                            <img id="imgPreview" src="#" class="img-thumbnail shadow-sm" style="max-height: 120px;">
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addProductForm" class="btn btn-success">Save Product</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        function loadData() {
            $.ajax({
                url: "product/fetch.php",
                type: "GET",
                dataType: "json",
                success: function (data) {
                    // console.log("Data received:", data);
                    let rows = '';
                    if (data.length > 0) {
                        $.each(data, function (index, product) {
                            rows += `<tr>
                            <td>${product.product_id}</td>
                            <td>${product.category_name}</td>
                            <td>${product.name}</td>
                            <td>$${product.price}</td>
                            <td>${product.stock_quantity}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>`;
                        });
                    } else {
                        rows = "<tr><td colspan='6' class='text-center'>No products found</td></tr>";
                    }
                    $("#productTableBody").html(rows);
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", error);
                    console.error("Response:", xhr.responseText);
                }
            });
        }
        loadData();

        // Insert Product
        $("#addProductForm").submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "product/insert.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (res) {
                    Swal.fire(
                        'Success!',
                        res, 'success');

                    loadData();
                },
                error: function (xhr, status, error) {
                    console.error("Insert Error:", error);
                    Swal.fire('Error!', 'Failed to add product', 'error');
                }
            });
        });

        // Image preview
        $("#imgInput").change(function () {
            const file = this.files[0];
            if (file) {
                $("#previewContainer").removeClass('d-none');
                $("#imgPreview").attr('src', URL.createObjectURL(file));
            }
        });
    });
</script>