<table>
    <thead>
    <tr>
        <th>Title</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Website</th>
        <th>Deal Source</th>
        <th>Sales person</th>
        <th>Sales person</th>
        <th>Close Date</th>
        <th>Created At</th>





        
    </tr>
    </thead>
    <tbody>
    @foreach($deals as $deal)
        <tr>
            <td>{{ $deal->title }}</td>
            <td>{{ $deal->email }}</td>
            <td>{{ $deal->phone }}</td>
            <td>{{ $deal->webiste }}</td>
            <td>{{ $deal->deal_source }}</td>
            {{-- <td>{{ $deal->webiste }}</td> --}}
            {{-- <td>{{ $deal->name}}</td> --}}
            {{-- <td>{{ $deal->deal_source }}</td> --}}
            <td>{{ $deal->close_date }}</td>
            <td>{{ $deal->created_at }}</td>


        </tr>
    @endforeach
    </tbody>
</table>
