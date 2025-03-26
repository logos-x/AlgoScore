@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-6 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 sidebar">
                    <div class="sub-title">
                        <h3>Categories</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-flush" id="accordionExample">
                                @if($categories->isNotEmpty())
                                    @foreach($categories as $key => $category)
                                        <div class="accordion-item">
                                            @if($category->sub_category->isNotEmpty())
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $key }}" aria-expanded="false" aria-controls="collapse-{{ $key }}">
                                                        {{ $category->name }}
                                                    </button>
                                                </h2>
                                            @else
                                                <a href="{{ route('front.shop', $category->slug) }}"
                                                   class="nav-item nav-link {{ ($categorySelected == $category->id) ? 'text-primary' : ''}}">{{ $category->name }}</a>
                                            @endif

                                            @if($category->sub_category->isNotEmpty())
                                                <div id="collapse-{{ $key }}" class="accordion-collapse collapse {{ ($categorySelected == $category->id) ? 'show' : ''}}"
                                                     aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                                    <div class="accordion-body">
                                                        <div class="navbar-nav">
                                                            @foreach($category->sub_category as $subCategory)
                                                                <a href="{{ route('front.shop', [$category->slug, $subCategory->slug]) }}"
                                                                   class="nav-item nav-link {{ ($subCategorySelected == $subCategory->id) ? 'text-primary' : '' }}">{{ $subCategory->name }}</a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
{{--                                <div class="accordion-item">--}}
{{--                                    <h2 class="accordion-header" id="headingOne">--}}
{{--                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">--}}
{{--                                            Electronics--}}
{{--                                        </button>--}}
{{--                                    </h2>--}}
{{--                                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">--}}
{{--                                        <div class="accordion-body">--}}
{{--                                            <div class="navbar-nav">--}}
{{--                                                <a href="" class="nav-item nav-link">Mobile</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Tablets</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Laptops</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Speakers</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Watches</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="accordion-item">--}}
{{--                                    <h2 class="accordion-header" id="headingOne">--}}
{{--                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">--}}
{{--                                            Men's Fashion--}}
{{--                                        </button>--}}
{{--                                    </h2>--}}
{{--                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">--}}
{{--                                        <div class="accordion-body">--}}
{{--                                            <div class="navbar-nav">--}}
{{--                                                <a href="" class="nav-item nav-link">Shirts</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Jeans</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Shoes</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Watches</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Perfumes</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="accordion-item">--}}
{{--                                    <h2 class="accordion-header" id="headingOne">--}}
{{--                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">--}}
{{--                                            Women's Fashion--}}
{{--                                        </button>--}}
{{--                                    </h2>--}}
{{--                                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">--}}
{{--                                        <div class="accordion-body">--}}
{{--                                            <div class="navbar-nav">--}}
{{--                                                <a href="" class="nav-item nav-link">T-Shirts</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Tops</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Jeans</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Shoes</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Watches</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Perfumes</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="accordion-item">--}}
{{--                                    <h2 class="accordion-header" id="headingOne">--}}
{{--                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">--}}
{{--                                            Applicances--}}
{{--                                        </button>--}}
{{--                                    </h2>--}}
{{--                                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">--}}
{{--                                        <div class="accordion-body">--}}
{{--                                            <div class="navbar-nav">--}}
{{--                                                <a href="" class="nav-item nav-link">TV</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Washing Machines</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Air Conditioners</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Vacuum Cleaner</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Fans</a>--}}
{{--                                                <a href="" class="nav-item nav-link">Air Coolers</a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                            </div>
                        </div>
                    </div>

                    <div class="sub-title mt-5">
                        <h3>Difficulty Level</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            @foreach(['Beginner', 'Intermediate', 'Advanced'] as $level)
                                <div class="form-check mb-2">
                                    <input {{ (in_array($level, $levelsArray)) ? 'checked' : '' }} class="form-check-input level-label" type="checkbox" name="level[]" value="{{ $level }}" id="level-{{ $level }}">
                                    <label class="form-check-label" for="level-{{ $level }}">
                                        {{ $level }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="sub-title mt-5">
                        <h3>Price</h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <input type="text" class="js-range-slider" name="my_range" value="" />

                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row pb-3">
                        <div class="col-12 pb-1">
                            <div class="d-flex align-items-center justify-content-end mb-4">
                                <div class="ml-2">
                                    <select name="sort" id="sort" class="form-control">
                                        <option {{ ($sort == 'latest') ? 'selected' : '' }} value="latest">Latest</option>
                                        <option {{ ($sort == 'price_desc') ? 'selected' : '' }} value="price_desc">Price High</option>
                                        <option {{ ($sort == 'price_asc') ? 'selected' : '' }} value="price_asc">Price Low</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        @if($products->isNotEmpty())
                            @foreach($products as $product)
                                @php
                                    $productImage = $product->product_images->first();
                                @endphp
                                <div class="col-md-4">
                                    <div class="card product-card">
                                        <div class="product-image position-relative">
                                            <a href="{{ route('front.product', $product->slug) }}" class="product-img">
                                                @if(!empty($productImage->image))
                                                    <img class="card-img-top" src="{{ asset('uploads/product/small/'.$productImage->image) }}" alt="">
                                                @else
                                                    <img class="card-img-top" src="{{ asset('front-assets/images/default-150x150.png') }}" alt="">
                                                @endif
                                            </a>
                                            <a class="whishlist" href="222"><i class="far fa-heart"></i></a>

                                            <div class="product-action">
                                                <a class="btn btn-dark" href="#">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body text-center mt-3">
                                            <a class="h6 link" href="product.php">{{ $product->name }}</a>
                                            <div class="price mt-2">
                                                <span class="h5"><strong>{{ number_format($product->price, 0, ',', '.') }} ₫</strong></span>
                                                @if($product->compare_price > 0)
                                                    <span class="h6 text-underline"><del>{{ number_format($product->compare_price, 0, ',', '.') }} ₫</del></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif


                        <div class="col-md-12 pt-5">
                            {{ $products->withQueryString()->links() }}
{{--                            <nav aria-label="Page navigation example">--}}
{{--                                <ul class="pagination justify-content-end">--}}
{{--                                    <li class="page-item disabled">--}}
{{--                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>--}}
{{--                                    </li>--}}
{{--                                    <li class="page-item"><a class="page-link" href="#">1</a></li>--}}
{{--                                    <li class="page-item"><a class="page-link" href="#">2</a></li>--}}
{{--                                    <li class="page-item"><a class="page-link" href="#">3</a></li>--}}
{{--                                    <li class="page-item">--}}
{{--                                        <a class="page-link" href="#">Next</a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </nav>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJS')
    <script>
        rangeSlider = $(".js-range-slider").ionRangeSlider({
            skin: "round",
            type: "double",
            min: 0,
            max: 500000,
            from: {{ $priceMin }},
            step: 10,
            to: {{ $priceMax }},
            max_postfix: "+",
            postfix: "₫",
            onFinish: function () {
                apply_filters();
            }
        });

        var slider = $(".js-range-slider").data("ionRangeSlider");

        $(".level-label").change(function () {
            apply_filters();
        });

        $("#sort").change(function () {
           apply_filters();
        });

        function apply_filters() {
            var levels = [];

            $(".level-label").each(function () {
               if ($(this).is(":checked") == true) {
                   levels.push($(this).val());
               }
            });

            var url = '{{ url()->current() }}?';

            if (levels.length > 0) {
                url += '&level='+levels.toString();
            }

            url += '&price_min='+slider.result.from+'&price_max='+slider.result.to;


            // Sorting
            url += '&sort='+$("#sort").val();

            window.location.href = url;

        }

    </script>
@endsection
