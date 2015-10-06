@extends('layouts.master')


@section('content')
<h2>Recordings for extension: {{ $agent->extension }}</h2>
Phone number: {{ $agent->phone_number }}
<hr/>
<table class="table">
    <thead>
        <tr>
            <th>Caller number</th>
            <th>Transcription</th>
            <th>Recording</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($recordings as $recording)
        <tr>
            <td>{{ $recording->caller_number }}</td>
            <td>{{ $recording->transcription }}</td>
            <td>
                <audio controls src={{ $recording->recording_url }}>
                    This browser doesn't support the audio element
                </audio>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
