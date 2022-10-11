    <script src="{{ asset('admin/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- apps -->
    @include('sweetalert::alert')
    
    <!-- apps -->
    <script src="{{ asset('admin/dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('admin/dist/js/feather.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('admin/dist/js/sidebarmenu.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('admin/dist/js/custom.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/moment/min/moment.min.js') }}"></script>

    <script src="{{ asset('admin/assets/libs/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/jquery-chained/jquery.chained.min.js') }}"></script>
    <script>
        $('.logout').on('click', function() {
            let token = "{{ csrf_token() }}";

            $.ajax({
                url: '{{url('logout')}}',
                type: 'POST',
                data: {
                    _token: token
            },
            success: function(e) {
                    window.location.href = '{{ route('login') }}';
                }
            });
        });

        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            $('.modalProfile').off('click').on('click', function () {
              $('#modalProfile').modal({backdrop: 'static', keyboard: false}) 
              
                $('#modalProfileContent').load($(this).attr('value'));
                $('#modalProfileTitle').html($(this).attr('title'));
            });
        });
            
        setInterval(function(){ 
            $('.modalEditProfile').off('click').on('click', function () {
              $('#modalEditProfile').modal({backdrop: 'static', keyboard: false}) 
              
                $('#modalEditProfileContent').load($(this).attr('value'));
                $('#modalEditProfileTitle').html($(this).attr('title'));
            });
        }, 500);
    </script>

    @yield('javascript')