@extends('layouts.admin')
@section('more-css')
<link rel="stylesheet" href="{{asset('src/plugins/src/autocomplete/css/autoComplete.02.css')}}">
@endsection
@section('title', 'Product Create')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> {{ $product->name }}
            </h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('product.update',$product->productID) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group row mb-1">
                    <label for="autoComplete1" class="form-label required col-sm-4 col-md-6 col-lg-2  col-form-label">Product Name: </label>
                    <div class="col-sm-8 col-md-6 col-lg-4">
                        <input type="text" name="name" id="autoComplete1" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>

                    <label for="code" class="form-label required col-sm-4 col-md-6 col-lg-2 col-form-label">Product Code: </label>
                    <div class="col-sm-8 col-md-6 col-lg-4">
                        <input type="number" name="code" class="form-control" value="{{ old('code',  $product->code) }}" required onchange="productCode(this.value)">
                    </div>
                </div>
                <div class="form-group row mb-1">
                    <label for="Litters" class=" form-label required col-sm-6 col-md-6 col-lg-2  col-form-label">Liters : </label>
                    <div class="col-sm-6 col-md-6 col-lg-4">
                        <div class="input-group">
                            <input type="number" step="any" placeholder="Enter Liters" value="{{ $product->ltr }}" required class="form-control" name="ltr" aria-label="Recipient's username" aria-describedby="button-addon2">
                            <button class="btn btn-default">Ltrs</button>
                        </div>
                    </div>
                    <label for="weight" class="form-label required col-sm-6 col-md-6 col-lg-2  col-form-label">Weight : </label>
                    <div class="col-sm-6 col-md-6 col-lg-4" >
                        <div class="input-group">
                            <input type="number" class="form-control" step="any" placeholder="Enter Weight" value="{{ $product->weight }}" name="weight"  aria-label="Recipient's username" aria-describedby="button-addon2">
                            <button class="btn btn-default">KG</button>
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-1">
                    <label for="grade" class="form-label required col-sm-6 col-md-6 col-lg-2  col-form-label">Grade / Viscosity : </label>
                    <div class="col-sm-6 col-md-6 col-lg-4" >
                        <input type="text" class="form-control" placeholder="Enter Grade / Viscosity" value="{{ $product->grade }}" name="grade" id="">
                    </div>
                    <label for="brandID" class=" form-label required col-sm-6 col-md-6 col-lg-2  col-form-label">Brand : </label>
                    <div class="col-sm-6 col-md-6 col-lg-4">
                        <select name="brandID" class="form-select" required>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->brandID }}" {{ old('brandID', $product->brandID) == $brand->brandID ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="form-group row mb-1">
                    <label for="categoryID" class=" form-label required col-sm-6 col-md-6 col-lg-2  col-form-label">Category : </label>
                    <div class="col-sm-6 col-md-6 col-lg-4" >
                        <select name="categoryID" class="form-select" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->categoryID }}" {{ old('categoryID', $product->categoryID) == $category->categoryID ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="productUnit" class="form-label required col-sm-6 col-md-6 col-lg-2  col-form-label">Product Unit: </label>
                    <div class="col-sm-6 col-md-6 col-lg-4" >
                        <select name="productUnit" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->unitID }}" {{ $product->productUnit == $unit->unitID ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="form-group row mb-1">
                    <label for="alertQuantity" class="form-label required col-sm-6 col-md-6 col-lg-2  col-form-label">Alert Quantity: </label>
                    <div class="col-sm-6 col-md-6 col-lg-4">
                        <input type="number" name="alertQuantity" class="form-control" value="{{ old('alertQuantity', $product->alertQuantity) }}">
                    </div>
                    <label for="image" class="form-label col-sm-6 col-md-6 col-lg-2 col-form-label mt-2">Picture: </label>
                    <div class=" col-sm-6 col-md-6 col-lg-4 mt-1">
                        <input type="file" name="image" class="form-control" value="{{ $product->image }}">
                        <img width="30%" class="mt-2 img-circle" src="{{ asset($product->image) }}" />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-2">
                        <input class="btn btn-primary" id="saveButton" type="submit" value="Save">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('more-script')
<script src="{{asset('src/plugins/src/autocomplete/autoComplete.min.js')}}"></script>
<script src="{{asset('src/plugins/src/autocomplete/custom-autoComplete.js')}}"></script>
    <script>
        function productCode(productCode) {
            $.ajax({
                url: "{{ route('ajax.handle',"getProductCode") }}",
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    productCode: productCode,
                },
                success: function (result) {
                    if (result.length > 0 && result[0].code) {
                        alert('This code already exists.');
                        $('input[name="code"]').val('');
                    }
                }
            });
        }

        var pros = @json($pros);
        const autoCompleteJS = new autoComplete({
    selector: "#autoComplete",
    placeHolder: "Enter Product Name",
    data: {
        src: pros,
        cache: false,
    },
    resultsList: {
        element: (list, data) => {
            if (!data.results.length) {
                // Create "No Results" message element
                const message = document.createElement("div");
                // Add class to the created element
                message.setAttribute("class", "no_result");
                // Add message text content
                message.innerHTML = `<span>Found No Results for "${data.query}"</span>`;
                // Append message element to the results list
                list.prepend(message);
            }
        },
        noResults: true,
    },
    resultItem: {
        highlight: {
            render: true
        }
    },
    events: {
        input: {
          focus() {
            if (autoCompleteJS.input.value.length) autoCompleteJS.start();
          },
          selection(event) {
            const feedback = event.detail;
            // Prepare User's Selected Value
            const selection = feedback.selection.value;
            // Replace Input value with the selected value
            autoCompleteJS.input.value = selection;
          },
        },
    },
});


    </script>
@endsection
