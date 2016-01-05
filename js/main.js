$(document).ready(function(){
    function f() {
        $('#regAuth').modal('hide');
    }


    $("#submit").click(function(){
        $('#login-form').submit();

    })
    $("#submit_reg").click(function(){
        $('#reg-form').submit();

    })

    $('#login-form').on({
        'submit': function (e){
            e.preventDefault();
            var formdata = $(this).serialize();
            var dest_url;
            dest_url = 'http://loftphpdz7.local:8888/user/login';
            $.ajax({
                url: dest_url,
                type: 'post',
                data: formdata,
                dataType: 'json',
                success : function(dataSuccess){
                    if (dataSuccess.result.status == 0){
                        $('#fio').removeClass('hidden').children('.fio').text(dataSuccess.result.lastname+' '+dataSuccess.result.name);
                        $('.cart').removeClass('hidden');
                        $('#log_out').removeClass('hidden');
                        $('#log_in').addClass('hidden');
                        $('#reg').addClass('hidden');
                        var cnt = dataSuccess.result.cnt;
                        $('.goods-cnt').text(cnt);
                        $('#loginAuth').modal('hide');
                    }
                    else
                    {
                        $('#error_message').text('Логин или пароль не верны');
                    }
                }
            })
        }
    }
    )

    $('#reg-form').on({
            'submit': function (e){
                e.preventDefault();
                console.log('asdsad');
                var formdata = $(this).serialize();
                var dest_url;
                dest_url = 'http://loftphpdz7.local:8888/user/logreg';
                $.ajax({
                    url: dest_url,
                    type: 'post',
                    data: formdata,
                    dataType: 'json',
                    success : function(dataSuccess){
                        if (dataSuccess.result.status == 0){
                            $('#reg_error_message').text('Ваша учетная запись создана. Требуется активация вашей учётной записи администратором');
                            setTimeout(function () {
                                $('#regAuth').modal('hide');
                            }, 3000);
                        }
                        else
                        {
                            $('#reg_error_message').text(dataSuccess.result.message);
                        }
                    }
                })
            }
        }
    )

    $('.addProduct').on({
        'click': function (e){
            var curr_url = $(this).attr("href");
            e.preventDefault();
            $.ajax({
                url: curr_url,
                type: 'post',
                dataType: 'json',
                success : function(dataSuccess){
                    if (dataSuccess.result.status == 0){
                        var cnt = dataSuccess.result.cnt;
                        $('.goods-cnt').text(cnt);

                    }
                    else
                    {
                        $('#error_message').text('Для добавление товара в корзину необходимо авторизоваться.');
                        $('#error').modal();
                        //$('#error_message').text('Логин или пароль не верны');
                    }
                }


            })
        }
    }
    )

    var position = new google.maps.LatLng(45.067883, 7.687231);
    $('.map').gmap({'center': position,'zoom': 15, 'disableDefaultUI':true, 'callback': function() {
        var self = this;
        self.addMarker({'position': this.get('map').getCenter() });
    }
    });
});