{{-- Vendor Scripts --}}
{{-- <script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script> --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/perfect-scrollbar.min.js" integrity="sha512-yUNtg0k40IvRQNR20bJ4oH6QeQ/mgs9Lsa6V+3qxTj58u2r+JiAYOhOW0o+ijuMmqCtCEg7LZRA+T4t84/ayVA==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/pace.min.js" integrity="sha512-2cbsQGdowNDPcKuoBd2bCcsJky87Mv0LEtD/nunJUgk6MOYTgVMGihS/xCEghNf04DPhNiJ4DZw5BxDd1uyOdw==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/classnames/2.3.1/index.min.js" integrity="sha512-6Wf/IjsSjLaFTYco3pXM+49kC5M7jtbHzxMcdmYvwDskjv7cMcBPmJX2053aoQ+LRi8Po4ZsCtkNMf+NhXhNyQ==" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/node-waves/0.7.6/waves.min.js"></script>
<script src="{{ asset('vendors/js/feather-icons/feather-icons.min.js') }}"></script>
<script src="{{ asset('vendors/js/unison-js/unison-js.min.js') }}"></script>
<script src="{{ asset(mix('vendors/js/ui/prism.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/blockui/blockui.min.js')) }}"></script>

{{-- Sweet Alert Scripts --}}
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>

@yield('vendor-script')

{{-- Theme Scripts --}}
<script src="{{ asset(mix('js/core/app-menu.js')) }}"></script>
<script src="{{ asset(mix('js/core/app.js')) }}"></script>
@if($configData['blankPage'] === false)
<script src="{{ asset(mix('js/scripts/customizer.js')) }}"></script>
@endif
{{-- page script --}}
<script src="{{ asset('js/scripts/main.js') }}"></script>
<script src="{{ asset('js/app-custom.js') }}"></script>
@yield('page-script')
{{-- page script --}}
<script>
    window.onUsersnapCXLoad = function(api) {
      api.init();
    }
    var script = document.createElement('script');
    script.async = 1;
    script.src = 'https://widget.usersnap.com/load/b88f3b45-e056-48ab-beb4-f8301dab39cc?onload=onUsersnapCXLoad';
    document.getElementsByTagName('head')[0].appendChild(script);
</script>

<script>
  $(document).ready(function() {
    $(".newsletter-form").submit(function(e) {
      let email = $(this).find('input[name="email"]');

      e.preventDefault();
      $.ajax({
        method: 'POST',
        url: route('subscribe-newsletter'),
        data: {
          email: email.val()
        },
        headers: {
          "X-CSRF-TOKEN" : window.Laravel.csrfToken,
        },
        beforeSend: function() {
          $(".form-submission-status").removeClass('invisible').addClass('visible');
          $(".form-submission-status").html("Please wait...");
        },
        success: function(data) {
          // setAlert({...data, positionClass: 'toast-bottom-center'});
          if(data.success) {
            email.val("");
            $(".form-submission-status").html("Thanks for subscribing to " + window.Laravel.appName);
          }
        },
        complete: function() {
          setTimeout(function() {
            $(".form-submission-status").removeClass('visible').addClass('invisible');
            $(".form-submission-status").html("");
          }, 5000);
        },
        error: function(data) {
          if (data.status == 422) {
            setAlert({
              code: "error",
              title: "Oops!",
              message: "You have validation error.",
              positionClass: 'toast-bottom-center'
            });
          } else if (data.status == 404) {
            let json = data.responseJSON;
            setAlert({
              code: "error",
              title: "Oops!",
              message: "Resource not found!",
              positionClass: 'toast-bottom-center'
            });
            console.warn(json.message);
          } else {
            setAlert({
              code: "error",
              title: "Oops!",
              message: "Something went wrong!",
              positionClass: 'toast-bottom-center'
            });
          }
        }
      });
    });
  });
</script>