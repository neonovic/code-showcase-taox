<div>
    <table class="tabulka-hlavni">
        <tr class="tr">
            <td align="center"><b>Obázek</b></td>
            <td><b>Název</b></td>
            <td><b>Uživatelé</b></td>
        </tr>
        @foreach ($results as $result)
            <tr class="tr{{ $loop->odd ? '1' : '2' }}">
                <td align="center">
                    <a href="/admin/eshop_zbozi/editace.php?id={{ $result[0]->product->id }}">
                        <img src="/{{ $result[0]->product->nahled_obr }}" style="height:40px;max-height: 40px"/>
                    </a>
                </td>
                <td>
                    <a href="/admin/eshop_zbozi/editace.php?id={{ $result[0]->product->id }}" style="color:black;text-decoration:none">
                        {{ $result[0]->product->nazev_CZ }}
                    </a>
                </td>
                <td>
                    @foreach ($result as $item)
                        {{ $item->user->getFullName() }}: {{ $item->quantity }}ks<br>
                    @endforeach
                </td>
            </tr>
        @endforeach
    </table>
</div>
