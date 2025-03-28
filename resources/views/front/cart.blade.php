@extends('front.layouts.app')

@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                        <li class="breadcrumb-item">Cart</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class=" section-9 pt-4">
            <div class="container">
                <div class="row">
                    @if(Session::has('success'))
                        <div class="col-md-12">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {!! Session::get('success') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                    @if(Session::has('error'))
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ Session::get('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                    @if(Cart::count() > 0)
                        <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table" id="cart">
                                <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Remove</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartContent as $cartItem)
                                        <tr>
                                            <td class="text-start">
                                                <div class="d-flex align-items-center">
                                                    @if(!empty($cartItem->options->productImage->image))
                                                        <img class="card-img-top" src="{{ asset('uploads/product/small/'.$cartItem->options->productImage->image) }}" alt="">
                                                    @else
                                                        <img class="card-img-top" src="{{ asset('front-assets/images/default-150x150.png') }}" alt="">
                                                    @endif
                                                    <h2>{{ $cartItem->name }}</h2>
                                                </div>
                                            </td>
                                            <td>{{ number_format($cartItem->price, 0, ',', '.') }} ₫</td>
                                            <td>
                                                <button onclick="deleteItem('{{ $cartItem->rowId }}');" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                        <div class="col-md-4">
                        <div class="card cart-summery">
                            <div class="sub-title">
                                <h3 class="bg-white">Cart Summery</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>Subtotal</div>
                                    <div>{{ Cart::subtotal() }}</div>
                                </div>
                                <div class="d-flex justify-content-between pb-2">
                                    <div>Shipping</div>
                                    <div>$0</div>
                                </div>
                                <div class="d-flex justify-content-between summery-end">
                                    <div>Total</div>
                                    <div>{{ Cart::subtotal() }}</div>
                                </div>
                                <div class="pt-5">
                                    <a href="login.php" class="btn-dark btn btn-block w-100">Proceed to Checkout</a>
                                </div>
                            </div>
                        </div>
{{--                        <div class="input-group apply-coupan mt-4">--}}
{{--                            <input type="text" placeholder="Coupon Code" class="form-control">--}}
{{--                            <button class="btn btn-dark" type="button" id="button-addon2">Apply Coupon</button>--}}
{{--                        </div>--}}
                    </div>

                    @else
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body d-flex justify-content-center align-items-center">
                                    <h4>Your cart is empty</h4>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection

@section('customJS')
    <script>
        function deleteItem(rowId) {
            if (confirm("Are you sure you want to delete?")) {
                $.ajax({
                    url: '{{ route('front.deleteItem.cart') }}',
                    type: 'post',
                    data: {rowId: rowId},
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = '{{ route('front.cart') }}';
                    }
                });
            }

        }
    </script>
@endsection
