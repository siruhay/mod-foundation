@include('foundation::reports.css')

<table class="report">
    <tbody>
        <tr class="without-border">
            <td><h3>{{ $title }}</h3></td>
        </tr>

        <tr class="without-border">
            <td><h5>PERIODE: {{ $periode }}</h5></td>
        </tr>

        @if($subdistrict)
        <tr class="without-border">
            <td><h5>KECAMATAN: {{ $subdistrict }}</h5></td>
        </tr>
        @endif
    </tbody>
</table>

<table class="report" style="margin-top: 4mm;">
    <tbody>
        <tr>
            <td><b><pre>NO</pre></b></td>
            <td><b><pre>NAMA</pre></b></td>
            <td><b><pre>DESA</pre></b></td>
            <td><b><pre>JENIS</pre></b></td>
            <td><b><pre>KETUA</pre></b></td>
        </tr>

        @foreach($records as $key => $record)
        <tr>
            <td>{{ $key +1 }}</td>
            <td>{{ $record->name }}</td>
            <td>{{ optional($record->village)->name }}</td>
            <td>{{ optional($record->communitymap)->name }}</td>
            <td>{{ optional($record->official)->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>