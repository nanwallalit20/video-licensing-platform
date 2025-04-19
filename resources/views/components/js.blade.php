@php
    use Illuminate\Support\Facades\Session;
@endphp
{{--Required Jquery--}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

{{--Theme js--}}
<script src="{{asset('/assets/js/core/popper.min.js')}}"></script>
<script src="{{asset('/assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('/assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
<script src="{{asset('/assets/js/plugins/fullcalendar.min.js')}}"></script>
<script src="{{asset('/assets/js/plugins/chartjs.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"></script>

{{--Additional plugin--}}
<script src="{{asset('/plugins/notify/src/js/notify.js')}}" type="module" async></script>
<script type="module" async>
    // important to import first before use anywhere in project
    import {Notify} from '{{ asset('/plugins/notify/src/js/notify.js') }}'

    window.notify = new Notify({
        timeout: 6000
    })

    // Display Flash messages
    @php
        $sessionFlashTypes = ['danger','info','notice','success','warning']
    @endphp
    @foreach($sessionFlashTypes as $type)
        @php
            $message = Session::get($type);
            Session::remove($type);
        @endphp
        @if($message)
            {!! "notify.show('".$type."', '".$message."')" !!}
        @endif
    @endforeach

</script>


<script src="{{asset('/plugins/justify/justify.js')}}"></script>

{{--Custom js--}}
<script src="{{asset('/js/default.js')}}"></script>
<script src="{{asset('/js/fileUpload.js')}}"></script>
