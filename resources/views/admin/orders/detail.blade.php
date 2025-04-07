@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order: {{ $order->id }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('orders.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message')
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header pt-3">
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    <h1 class="h5 mb-3">Customer Info</h1>
                                    <address>
                                        <strong>{{ $order->first_name.' '.$order->last_name}}</strong><br>
                                        Phone: {{ $order->mobile }}<br>
                                        Email:{{ $order->email }}
                                    </address>
                                </div>

                                <div class="col-sm-4 invoice-col">
{{--                                    <b>Invoice #007612</b><br>--}}
{{--                                    <br>--}}
                                    <b>Order ID: </b> {{ $order->id }}<br>
                                    <b>Total: </b>{{ number_format($order->grand_total, 0, ',', '.') }} ₫<br>
                                    <b>Status:</b>
                                    @if($order->status == 'pending')
                                        <span class="text-danger">Pending</span>
                                    @elseif($order->status == 'delivered')
                                        <span class="text-success">Delivered</span>
                                    @else
                                        <span class="text-gray">Cancelled</span>
                                    @endif
                                    <br>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-3">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th width="100">Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($orderItems->isNotEmpty())
                                    @foreach($orderItems as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <th colspan="1" class="text-right">Subtotal:</th>
                                    <td>{{ number_format($order->subtotal, 0, ',', '.') }} ₫</td>
                                </tr>
                                <tr>
                                    <th colspan="1" class="text-right">Discount:</th>
                                    <td>{{ number_format($order->discount, 0, ',', '.') }} ₫</td>
                                </tr>
                                <tr>
                                    <th colspan="1" class="text-right">Grand Total:</th>
                                    <td>{{ number_format($order->grand_total, 0, ',', '.') }} ₫</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <form action="" method="post" name="changeOrderStatusForm" id="changeOrderStatusForm">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Order Status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="pending" {{ ($order->status == 'pending') ? 'selected' : '' }}>Pending</option>
                                        <option value="delivered" {{ ($order->status == 'delivered') ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled" {{ ($order->status == 'cancelled') ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Send Inovice Email</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option value="">Customer</option>
                                    <option value="">Admin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJS')
    <script>
        $("#changeOrderStatusForm").submit(function(event) {
           event.preventDefault();

           $.ajax({
              url: '{{ route('orders.changeOrderStatus', $order->id) }}',
              type: 'post',
              data: $(this).serializeArray(),
              dataType: 'json',
              success: function(response) {
                  window.location.href='{{ route("orders.detail", $order->id) }}'
              }
           });
        });
    </script>
@endsection
