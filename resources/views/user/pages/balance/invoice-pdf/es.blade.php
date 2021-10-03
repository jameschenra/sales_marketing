<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <title>Recibo de pago</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            font-family: Arial;
        }

        body {
            background: #fff;
            background-image: none;
            font-size: 12px;
            font-family: Arial;
        }

        address {
            margin-top: 15px;
        }

        .container {
            padding-top: 30px;
        }

        .invoice-head td {
            padding: 0 8px;
        }

        .invoice-body {
            background-color: transparent;
        }

        .logo {
            padding-bottom: 10px;
        }

        .table th {
            vertical-align: bottom;
            font-weight: bold;
            padding: 8px;
            line-height: 20px;
            text-align: left;
        }

        .table td {
            padding: 8px;
            line-height: 20px;
            text-align: left;
            vertical-align: top;
            border-top: 1px solid #dddddd;
        }

        .well {
            margin-top: 15px;
        }

    </style>
</head>

<body>
    <div class="container">
        <table style="margin-left: auto; margin-right: auto" width="550">
            <tr>
                <!-- Organization Name / Image -->
                <td>
                    <h2>{!! trans('main.sitename') !!}</h2>
                    by Aligys s.r.l.<br />
                    Via della montagna 42<br />
                    87010 Frascineto (Cosenza) Italy<br />
                    IVA N. 03249460787
                </td>
            </tr>
            <tr>
                <td>
                    Orden no. {{ $transaction->book_id }}<br>
                    Recibo n. {{ $transaction->id }}<br>
                    Fecha {{ date('d-m-Y', strtotime($transaction->getOriginal('created_at'))) }}
                </td>
            </tr>
            <tr>
                <td align="right">
                    <strong>A la atención</strong><br />
                    {{ $user->name . ' ' . $user->surname }}
                </td>
            </tr>
            <tr>
                <!-- Organization Details -->
                <td>
                    <h2>Objeto:</h2>
                    @if ($transaction->service_id > 0 && $user->services()->find($transaction->service_id))
                        @if ($transaction->receiver_id == $user->id && $transaction->sender_id == 0 && $book->price == 0)
                            {{ trans('main.Refund Fee for the free service') }}
                        @elseif($transaction->receiver_id == $user->id && $transaction->sender_id == 0)
                            {{ trans('main.Refund Fee for the service') }}
                        @elseif($transaction->receiver_id == $user->id)
                            {{ trans('main.Payment online for the service') }}
                        @elseif($transaction->sender_id == $user->id && $transaction->receiver_id == 0 &&
                            $book->price == 0)
                            {{ trans('main.Fee for the free service') }}
                        @elseif($transaction->sender_id == $user->id && $transaction->receiver_id == 0)
                            {{ trans('main.Fee for the service') }}
                        @elseif($transaction->sender_id == $user->id)
                            {{ trans('main.Refund for the service') }}
                        @endif
                        {{ $transaction->service->name }}<br />
                        @if ($transaction->receiver_id == $user->id && $transaction->sender_id == 0 && $book->is_paid_online && $book->price != 0)
                            {{ trans('main.paid online and canceled by') }}
                        @elseif($transaction->receiver_id == $user->id && $transaction->sender_id == 0 &&
                            $book->is_paid_online == 0 && $book->price != 0)
                            {{ trans('main.to pay in office and canceled by') }}
                        @elseif($transaction->receiver_id == $user->id && $transaction->sender_id == 0 &&
                            $book->price == 0)
                            {{ trans('main.booked and canceled by') }}
                        @elseif($transaction->receiver_id == $user->id || $book->price == 0)
                            {{ trans('main.booked by') }}
                        @elseif($transaction->receiver_id == $user->id)
                            {{ trans('main.paid online and canceled by') }}
                        @elseif($transaction->sender_id == $user->id && $transaction->receiver_id == 0 &&
                            $book->is_paid_online)
                            {{ trans('main.paid online by') }}
                        @elseif($transaction->sender_id == $user->id && $transaction->receiver_id == 0 &&
                            $book->is_paid_online == 0)
                            {{ trans('main.to pay in office by') }}
                        @elseif($transaction->sender_id == $user->id)
                            {{ trans('main.paid online and canceled by') }}
                        @endif
                        @if ($transaction->receiver_id == $user->id && $transaction->sender_id == 0)
                            {{ $book->user->name . ' ' . $book->user->surname }}
                        @elseif($transaction->receiver_id == $user->id)
                            {{ $transaction->sender->name . ' ' . $transaction->sender->surname }}
                        @elseif($transaction->sender_id == $user->id && $transaction->receiver_id == 0)
                            {{ $book->user->name . ' ' . $book->user->surname }}
                        @elseif($transaction->sender_id == $user->id && $book->deleted_by == 'company')
                            {{ $transaction->sender->name . ' ' . $transaction->sender->surname }}
                        @elseif($transaction->sender_id == $user->id)
                            {{ $transaction->receiver->name . ' ' . $transaction->receiver->surname }}
                        @endif
                    @elseif($transaction->service_id > 0 && !$user->services()->find($transaction->service_id))
                        @if ($transaction->receiver_id == $user->id && $transaction->sender_id != 0 && $transaction->sender->services()->find($transaction->service_id))
                            {{ trans('main.Refund for the service') }} {{ $transaction->service->name }} <br />
                            {{ trans('main.paid online and canceled by') }}
                            @if ($transaction->receiver_id == $user->id && $book->deleted_by == 'company')
                                {{ $transaction->sender->name . ' ' . $transaction->sender->surname }}
                            @else
                                {{ $transaction->receiver->name . ' ' . $transaction->receiver->surname }}
                            @endif
                        @elseif($transaction->sender_id == $user->id && $transaction->receiver_id != 0 &&
                            $transaction->receiver->services()->find($transaction->service_id))
                            {{ trans('main.Online purchase for the service') }}
                            {{ $transaction->service->name }}<br />
                            {{ trans('main.paid to') }}
                            {{ $transaction->receiver->name . ' ' . $transaction->receiver->surname }}
                        @else
                            {{ trans('main.Top up balance') }}
                        @endif
                    @endif
                    <br /><br />
                    Cantidad de servicios pagado en linea : {{ $book->number_of_booking }}<br />
                    Precio unitario : @if ($book->price == 0)
                    {{ trans('main.Free Service') }} @else € {{ number_format($book->price - $book->discount, 2) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td align="right">
                    CANTIDAD € {{ number_format($transaction->amount, 2) }}<br /><br />
                    TOTAL € {{ number_format($transaction->amount, 2) }}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
