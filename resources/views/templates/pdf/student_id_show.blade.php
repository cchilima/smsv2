{{--<link rel="stylesheet" href="{{$css}}">--}}
{{--<link rel="stylesheet" href="{{$app}}">--}}
<link href="">
<div id="studentID">
    <div class="header">

        <img src="data:image/gif;base64,{{$logo}}" alt="" style="
           background-repeat: no-repeat;
           background-position: center;
           background-size: cover;
           width: 215px;
           height:90px;

           ">
    </div>
    <div class="footer">
        <div class="">
            <h5>STUDENT IDENTIFICATION CARD</h5>
            <h3 style="font-size: 90px;font-weight: bold;">{{ $student->user->first_name }}
                @if(!empty($student->user->middle_name))
                <br>
                {{ $student->user->middle_name }}
                @endif
                <br>
                {{ $student->user->last_name }}
            </h3>
            <p>{{ $student->id }}</p>
            <p>2024-2026</p>

            <div id="qrcode">
                <img src="data:image/gif;base64,{{$logo}}" alt="" style="
                 height: 350px;
                 margin-top: 220px;
                 position: absolute;
                 z-index: 3;">
            </div>


        </div>
    </div>
</div>
