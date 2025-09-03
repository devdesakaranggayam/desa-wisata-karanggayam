<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{ asset('dashboard/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('dashboard/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('dashboard/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('dashboard/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ asset('dashboard/vendor/js/menu.js') }}"></script>
<!-- endbuild -->

<!-- Vendors JS -->

<!-- Main JS -->
<script src="{{ asset('dashboard/js/main.js') }}"></script>

<!-- custom function taruh sini semua -->
<script src="{{ asset('js/helpers.js') }}"></script>

<!-- Page JS -->

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" integrity="sha512-Rdk63VC+1UYzGSgd3u2iadi0joUrcwX0IWp2rTh6KXFoAmgOjRS99Vynz1lJPT8dLjvo6JZOqpAHJyfCEZ5KoA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js" integrity="sha512-efAcjYoYT0sXxQRtxGY37CKYmqsFVOIwMApaEbrxJr4RwqVVGw8o+Lfh/+59TU07+suZn1BWq4fDl5fdgyCNkw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.all.min.js" integrity="sha256-O11zcGEd6w4SQFlm8i/Uk5VAB+EhNNmynVLznwS6TJ4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/js/all.min.js" integrity="sha512-gBYquPLlR76UWqCwD06/xwal4so02RjIR0oyG1TIhSGwmBTRrIkQbaPehPF8iwuY9jFikDHMGEelt0DtY7jtvQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

<script>
    $(function () {
        $('.summernote').summernote({
            placeholder: 'Tulis konten di sini...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['table', ['table']],
            ]
        });

        $('form').on('submit', function () {
            $('.summernote').val($('.summernote').summernote('code'));
        });
    });
</script>

<script>
    toastr.options.preventDuplicates = true;
    toastr.options.timeOut = 2000;

    @if(session()->has('success'))
        toastr.success(`{{ session()->get('success') }}`)
    @endif
    @if(session()->has('warning'))
        toastr.warning(`{{ session()->get('warning') }}`)
    @endif
    @if(session()->has('error'))
        toastr.error(`{{ session()->get('error') }}`)
    @endif

    const csrf_token = $('meta[name="csrf-token"]').attr('content')

    $.ajaxSetup({
        headers: { "X-CSRFToken": csrf_token },
        timeout: 10000,
        // async: false
    });

    $(document).ready(function () {
        $('#datatable').DataTable();
        $('.datatable').DataTable();

        $('.numbers').keypress(function (e) {
            return onlyNumber(String.fromCharCode(e.keyCode))
        });
        $('.numbers').focusout(function (e) {
            const el = $(e.target);
            const valid = onlyNumber(el.val())

            if (!valid) {
                alert('Hanya boleh angka')
                el.val('')
            }
        });

        $('.select2').select2({
            theme: 'bootstrap-5'
        });

        $('body').on('focus', '.money', (e) => {
            const value = $(e.target).val()
            newValue = moneyIndoToDecimal(value)
            $(e.target).val(newValue)
        })

        $('body').on('blur', '.money', (e) => {
            const value = $(e.target).val()
            newValue = decimalToMoneyIndo(value)
            $(e.target).val(newValue)
        })

        $('body').on('keypress', '.numeric',function(event) {
            // Get the ASCII value of the pressed key
            var charCode = (event.which) ? event.which : event.keyCode;

            // Allow only numbers (ASCII codes 48 to 57)
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                event.preventDefault();
            }
        });
    });
</script>