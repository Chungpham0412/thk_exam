@extends('admin.bookings.list')

@section('search_results')
    <div class="page-wrapper search-page-wrapper">
        <div class="search-result">
            <h3 class="search-result-title">検索結果</h3>
            @if (!empty($bookings))
                <table class="shopsearchlist_table">
                    <tbody>
                        <tr>
                            <td nowrap="" id="hotel">
                                ホテル名
                            </td>
                            <td nowrap="" id="customer_name">
                                顧客名
                            </td>
                            <td nowrap="" id="created_at">
                                お客様連絡先
                            </td>
                            <td nowrap="" id="updated_at">
                                チェックイン日
                            </td>
                            <td nowrap="" id="updated_at">
                                チェックアウト日
                            </td>
                            <td class="btn_center" id="edit"></td>
                            <td class="btn_center" id="delete"></td>
                        </tr>
                        @foreach($bookings as $booking)
                            <tr style="background-color:#BDF1FF">
                                <td>
                                    {{ $booking['hotel']['hotel_name'] }}
                                </td>
                                <td>
                                    {{ $booking['customer_name'] }}
                                </td>
                                <td>
                                    {{ $booking['customer_contact'] }}
                                </td>
                                <td>
                                    {{ (string) $booking['chekin_time'] }}
                                </td>
                                <td>
                                    {{ (string) $booking['checkout_time'] }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.bookings.edit', ['booking_id' => $booking['booking_id']]) }}">編集</a>
                                </td>
                                <td>
                                    <form action="{{ route('admin.bookings.destroy', ['booking_id' => $booking['booking_id']]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="booking_id" value="{{ $booking['booking_id'] }}">
                                        <button type="submit">削除</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>検索結果がありません</p>
            @endif
        </div>
    </div>
@endsection
