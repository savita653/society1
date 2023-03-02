$(document).ready(function() {
    $("body").on("click", ".request-reminder", function() {
        let btn = $(this);

        let data = {
            'model_type': btn.data('modeltype'),
            'model_id': btn.data('modelid'),
            'type': btn.data('type')
        };
        
        submitAjax({
            type: 'POST',
            url: route('playlist.store', route),
            data: data,
        }, {
            submitBtn: btn,
            success: function(data) {
                if(data.success) {
                    btn.find('span').html(data.newtext);
                }
            }
        });
    });
});