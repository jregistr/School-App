@extends('layouts.register')

@section('content')

<div class="container">
    <table style="text-align:center">
        <tr>
            <th>Select</th>
            <th>CRN</th>
            <th>Course</th>
            <th>Credits</th>
            <th>Instructors</th>
            <th>Start</th>
            <th>End</th>
            <th>Location</th>
            <th>Days</th>
        </tr>
        @foreach($classes as $class)
            @php $sections = $class->sectionsWithMeetings()->sections @endphp
            @foreach($sections as $section)
                <tr>
                    <td><input type="checkbox" name="{{$class->crn}}"/>&nbsp;</td>
                    <td>{{ $class->crn }}</td>
                    <td>{{ $class->name }}</td>
                    <td>{{ $class->credits }}</td>
                    <td>{{$section->instructors}}</td>
                @php $meetings = $section->meetings @endphp
                @foreach($meetings as $meeting)
                        <td>{{$meeting->start}}</td>
                        <td>{{$meeting->end}}</td>
                        <td>{{$meeting->location}}</td>

                        <?php
 
                            $days = "";
                            if($meeting->monday == 1){
                                $days = $days . "M";
                            } if($meeting->tuesday == 1){
                                $days . "T";
                            } if($meeting->wednesday == 1){
                                $days = $days . "W";
                            } if($meeting->thursday == 1){
                                $days = $days . "TH";
                            } if($meeting->friday == 1){
                                $days = $days . "F";
                            } if($meeting->saturday == 1){
                                $days = $days . "Sat";
                            } if($meeting->sunday == 1){
                                $days = $days . "Sun";
                            }
                            echo "<td>$days</td>";
                        ?>

                    </tr>
                @endforeach
            @endforeach
        @endforeach
    </table>
</div>
@endsection
