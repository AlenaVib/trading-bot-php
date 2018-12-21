@extends('layout')

@section('body')

    {{--<h2 style="margin-left: 20px;">Open Orders.  <span id="countdown"></span></h2>--}}
    <h2 style="margin-left: 20px;">Open Orders ({{$allCount}}) <span id="countdown"></span>
        <button id="state">On</button>
    </h2>
    @if($open->isNotEmpty())
        <table class="table table-hover table-responsive col-12">
            <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>P/L</th>
                <th>pom</th>
                <th>symbol</th>
                <th>Buy</th>
                <th>Current</th>
                <th>Quantity</th>
                <th>Side</th>
                <th>IsTrailing</th>
                <th>TP</th>
                <th>SL</th>
                <th>TTP</th>
                <th>TSL</th>
                <th>comment</th>
                <th>max</th>
                <th>min</th>
                <th>
                    Action
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($open as $symbol)

                @foreach($symbol as $order)
                    <form action="{{route('editPosition',$order->id)}}" method="post">
                        {{csrf_field()}}
                        <tr>
                            <td>{{$order->id}}</td>
                            <td>{{$order->created_at->diffForHumans()}}</td>
                            <td class="@if($order->inProfit()) bg-success @else bg-danger @endif">
                                {{round($order->getPL(),3)}}%
                            </td>
                            <td>
                                {{round($order->maxFloated - $order->getPL(),2)}}%
                            </td>
                            <td>{{$order->symbol}}</td>
                            <td>{{$order->price}}</td>
                            <td>{{$order->getCurrentPrice()}}</td>
                            <td>{{$order->origQty}}</td>
                            <td>{{$order->side}}</td>
                            <td>
                                @if($order->trailing)
                                    <a href="{{route('toggleTrailing',$order->id)}}" class="btn btn-secondary">Yes</a>
                                @else
                                    <a href="{{route('toggleTrailing',$order->id)}}" class="btn btn-outline-secondary">No</a>
                                @endif
                            </td>
                            <td><input type="text" size="2" value="{{$order->takeProfit}}" name="takeProfit">%</td>
                            <td><input type="text" size="2" value="{{$order->stopLoss}}" name="stopLoss">%</td>
                            <td><input type="text" size="2" value="{{$order->trailingTakeProfit}}"
                                       name="trailingTakeProfit">%
                            </td>
                            <td><input type="text" size="2" value="{{$order->trailingStopLoss}}"
                                       name="trailingStopLoss">%
                            </td>
                            <td>
                                <textarea name="comment" id="" cols="0" rows="1"
                                          style="width: 100px;">{{$order->comment}}</textarea>

                            </td>
                            <td>{{$order->maxFloated}}</td>
                            <td>{{$order->minFloated}}</td>
                            <td>
                                <button class="btn btn-success" type="submit">Save</button>
                                <a href="{{route('closePosition',$order->id)}}" class="btn btn-danger"
                                   onclick="return confirm('Are you sure?');">Close</a>
                                <a target="_blank"
                                   href="https://www.tradingview.com/chart/?symbol=BINANCE%3A{{$order->symbol}}"
                                   class="btn btn-default">TradingView</a>
                            </td>

                        </tr>
                    </form>
                @endforeach


            @endforeach
            </tbody>
        </table>
    @else
        <p class="col-12">
            no open order
        </p>
    @endif

    <div style="clear: both;"></div>

    <div class="row">
        <div class="col-md-4">@include('newPosition')</div>
        <div class="col-md-4">@include('orderDefaults')</div>
    </div>

    <script>
        $("#state").click(function () {
            var state = $(this).text();
            if (state === "On") {
                $(this).text('Off');
            }
            else {
                $(this).text('On');
            }
        });

        (function countdown(remaining) {
            if (remaining <= 0)
                location.reload(true);
            document.getElementById('countdown').innerHTML = remaining;
            setTimeout(function () {
                if ($("#state").text() === "On") {
                    countdown(remaining - 1);
                }
                else {
                    countdown(remaining)
                }
            }, 1000);
        })(15);


    </script>
@endsection