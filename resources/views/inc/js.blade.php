<script>
    @if(Session::get('success'))
    Snackbar.show({
      text: '{{Session::get('success')}}',
      pos: 'top-right',
      backgroundColor: '#38c172'
    });
    @endif
    @if(Session::get('error'))
    Snackbar.show({
      text: '{{Session::get('error')}}',
      pos: 'top-right',
      backgroundColor: '#e3342f'
    });
    @endif
    @if(count($errors) > 0)
    Snackbar.show({
      @foreach($errors->all() as $error)
      text: '{{$error}}',
      @endforeach
      pos: 'top-right',
      backgroundColor: '#e3342f'
    });
    @endif
</script>
<script>
    $(document).ready(function() {
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();

            // Get the custom message from the data-message attribute
            var message = $(this).attr('data-message') || "Do you really want to delete this?";

            // Show a confirmation popup with the custom message
            swal({
                title: "Are you sure?",
                text: message,
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    window.location.href = $(this).attr('href');
                } else {
                    swal("You canceled the operation!");
                }
            });
        });
    });
    // confirm modal
    (function($) {
        "use strict";
        $(document).on('click', '.confirmBtn', function() {
            var modal = $('#confirmationModal');
            let data = $(this).data();
            modal.find('.question').text(`${data.question}`);
            modal.find('form').attr('action', `${data.action}`);
            modal.modal('show');
        });
    })(jQuery);
</script>
