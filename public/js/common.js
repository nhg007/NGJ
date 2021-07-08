let formatNumber = function (num) {
    return (num || 0).toString().replace(/(\d)(?=(?:\d{3})+$)/g, '$1,');
};


toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-bottom-left",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "3000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

let productUtils = {
    init: () => {
        productUtils.bindAddCartClick();
    },
    bindAddCartClick: function () {

        //カートに追加する
        $('.cart-add').on('click', function () {

            let id = this.dataset.id;
            let restrantId = this.dataset.rid;
            axios.post('/cart/addCart/' + restrantId + "/" + id).then(function (res) {
                const {status, data,message} = res.data;
                if (status === 201) {
                    location.href = '/login';
                    return;
                }
                if (status === 200) {
                    //カートに加入しました
                    toastr.success('カートに入れました');
                    $('#badge').text(data).show();
                }
                if (status === 500) {
                    toastr.error(message);
                }
            }).catch(function () {

            })
        })
    }
}

let cart = {
    init: function () {
        //checkbox
        let chks = $('.goods-list-item');
        let chkAll = $('#check-goods-all');

        $('.goods-list-item').on('click', function () {
            chkAll.prop('checked', false);
            let slChk = $('.goods-list-item:checked');
            if (this.checked) {
                $('#selectGoodsCount').text(slChk.length);
                if (slChk.length === chks.length) {
                    chkAll.prop('checked', true);
                }
            }

            //選択された商品価格の合計
            calcPrice(slChk);
        })

        //数量の減少
        $('.car-decrease').on('click', function () {
            let goodsCount = $(this).closest('.input-group').find('.goods-count');
            let number = parseInt(goodsCount.val(), 10);
            let slChk = $('.goods-list-item:checked');
            number = (--number) || 1;
            goodsCount.val(number);

            let cartId = this.dataset.id;
            let num = goodsCount.val();
            let _this = this;

            $('#cartUpdate').modal('show');

            //カートに商品数量を変更する
            updateCartNumber(cartId, num, function () {


                //カートの合計を更新する
                calcPrice($('.goods-list-item:checked'));

                //
                calcItemPrice($(_this).closest('.row'), number);

                calcPrice(slChk);
            })
        })
        //数量の増加
        $('.car-add').on('click', function (event) {
            let button = event.relatedTarget;
            let goodsCount = $(this).closest('.input-group').find('.goods-count');
            let number = parseInt(goodsCount.val(), 10);
            let slChk = $('.goods-list-item:checked');
            goodsCount.val(++number);

            let cartId = this.dataset.id;
            let num = goodsCount.val();

            $('#cartUpdate').modal('show');

            let _this = this;

            //カートに商品数量を変更する
            updateCartNumber(cartId, num, function () {

                //カートの合計を更新する
                calcPrice($('.goods-list-item:checked'));

                //
                calcItemPrice($(_this).closest('.row'), number);

                calcPrice(slChk);
            })

        })

        function updateCartNumber(cartId, num, callback) {
            axios.post('/cartProductNumber', {cartId: cartId, number: num}).then(
                function (res) {
                    if (res.status === 200) {
                        let data = res.data
                        if(data.status === 500){
                            toastr.error(data.message);
                        }else{
                            callback();
                        }
                    }
                }).catch(function (res) {
                toastr.error('エラーが発生しました');
                setTimeout(function () {
                    location.reload();
                }, 300)
            }).finally(function () {
                setTimeout(function () {
                    $("#cartUpdate").modal('hide')
                },1000)
            })
        }

        $('#deleteItemTip').on('show.bs.modal', function (event) {
            let button = event.relatedTarget;
            let cartId = button.dataset.id;
            let modal = $(this);
            modal.find('.deleteSure').off('click').on('click', function () {
                axios.delete('/cart/delete/' + cartId).then(function (res) {
                    if (res.status === 200) {

                        $(button).closest('.goods-item').remove();

                        //カートの合計を更新する
                        calcPrice($('.goods-list-item:checked'));

                        //ダイアログを閉じる
                        modal.modal('hide');

                    }
                }).catch(function () {

                })
            })
        })

        //すべての削除
        $('.delete-all').on('click', function (event) {
            let slChk = $('.goods-list-item:checked').length;
            if (slChk === 0) {
                $('#selectItemTip').modal('show');
            } else {
                $('#deleteMultyTip').modal('show');
            }

            event.preventDefault();
            event.stopPropagation();
            return false;
        })

        $('#deleteMultyTip').on('show.bs.modal', function (event) {
            let modal = $(this);

            let ids = [];
            let chks = $('.goods-list-item:checked');
            chks.each(function () {
                ids.push(this.dataset.id);
            });

            modal.find('.deleteMultySure').off('click').on('click', function () {
                axios.delete('/cart/mulDelete', {params: {ids: ids}}).then(function (res) {
                    if (res.status === 200) {

                        // console.info(res);
                        // chks.each(function () {
                        //     $(this).closest('.goods-item').remove();
                        // })
                        //
                        // //カートの合計を更新する
                        // calcPrice($('.goods-list-item:checked'));
                        //
                        // //ダイアログを閉じる
                        // modal.modal('hide');
                        location.reload();

                    }
                }).catch(function () {

                })
            })
        })

        //すべてチェックボックス
        $('#check-goods-all').on('click', function () {
            $('.goods-list-item').prop('checked', this.checked);
            calcPrice($('.goods-list-item:checked'));
        })

        //商品数量を応じて、合計を再計算する
        $('.goods-count').on("blur", function () {
            if (isNaN(parseFloat($(this).val())) || parseFloat($(this).val()) <= 0) {
                $(this).val(1);
            }

            let cartId = this.dataset.id;
            let num = $(this).val();

            $('#cartUpdate').modal('show');

            let _this = this;

            //カートに商品数量を変更する
            updateCartNumber(cartId, num, function () {

                //カートの合計を更新する
                calcPrice($('.goods-list-item:checked'));

                //ダイアログを閉じる

                calcItemPrice($(_this).closest('.row'), $(_this).val());

                calcPrice($('.goods-list-item:checked'));
            })

        });
        var selectedRestrantIdArrayWorked = [];//去掉重复后的数组

        console.info($('.goods-list-item:checked').length)

        //カートの合計を更新する
        setTimeout(function () {
            calcPrice($('.goods-list-item:checked'));
        }, 100)

        function calcItemPrice(rowObj, num) {
            let chkObj = rowObj.find('.goods-list-item');
            let price = chkObj.get(0).dataset.price;
            rowObj.find('.single-total').text(formatNumber(price * num))
        }

        function calcPrice(slChk) {
            selectedRestrantIdArrayWorked = [];
            let total = 0;
            let selectedRestrantIdArray=[];
            slChk.each(function () {
                //選択された商品の価格が合計された
                let cart_id = this.dataset.id;
                let restrantId = this.dataset.restrantid;
                selectedRestrantIdArray.push(restrantId);
                let price = this.dataset.price;
                let num = $(this).closest('.row').find('.goods-count').val();
                total += num * price;
            })
            var obj = {};//用于标记字符串


            for (var i = 0, len = selectedRestrantIdArray.length; i < len; i++) {
                var s = selectedRestrantIdArray[i];
                if (obj[s]) continue;//如果字符串已经存在就跳过
                else {
                    obj[s] = s;//加入标记对象中
                    selectedRestrantIdArrayWorked.push(s);//结果放入新数组中
                }
            }

            //選択された商品の数量
            $('.selectGoodsCount').text(slChk.length);
            $('.selectGoodsMoney').text(formatNumber(total));

            // let ids = $.map(slChk, function (item) {
            //     return item.dataset.id;
            // })

            if (total == 0) {
                $('#btnOrder').attr('disabled', 'disabled');
            } else {
                $('#btnOrder').removeAttr('disabled');
            }
        }

        //注文ボタンを活性する
        $('#btnOrder').removeAttr('disabled').on('click', function () {
            let ids = $.map($('.goods-list-item:checked'), function (item) {
                $('#hdRestrantId').val(item.dataset.restrantid);
                return item.dataset.id;
            })
            $('#hdCartIds').val(ids);


            console.log(selectedRestrantIdArrayWorked);
            if(selectedRestrantIdArrayWorked.length>1){

                $('#modal1').modal('show')
            }else {
                $(this).closest('form').submit();
            }
            //location.href='/cartConfirm?cartIds=' + ids
        });
    }
    ,
    //カートの確認
    confirm: function () {

        //まず、カートの商品をチェックする

        axios.post('/checkCartItem', {
            cartIds: $('#hdCartIds').val()
        }).then(function (res) {

            const {data, status} = res;
            if (data.data === 0) {
                location.href = '/dashboard';
                return;
            }

            $('#cartItems').removeClass('hide');

            $('#btnConfirm').on('click', function () {
                $(this).attr('disabled', 'disabled');
                $('#cartConfirm').modal('show');


                let cartIds = [];
                //ajaxで注文番号を作成する
                $('.cartIds').each(function () {
                    cartIds.push($(this).val());
                })
                let totalPrice = $('.totalPrice').val();
                let goods_number = $('.goods_number').val();
                let restrantId = $('#restrantId').val();
                let form = $(this).closest('form');

                axios.post('/createOrder', {
                    cartIds: cartIds,
                    goodNumber: goods_number,
                    totalPrice: totalPrice,
                    restrantId: restrantId
                }).then(function (res) {
                    const {data, status,message} = res.data;
                    if (status === 200) {
                        $('.order_id').val(data.id);
                        $('.order_sn').val(data.order_sn);
                        form.submit();
                    } else {

                        toastr.error(message || 'お支払いに失敗しました');
                        setTimeout(function () {
                            history.back();
                            //location.href = '/';
                        }, 2000)
                    }
                }).catch(function (res) {
                    toastr.error('お支払いに失敗しました');
                    setTimeout(function () {
                        //location.href = '/';
                    }, 1000)
                })
                //サブミット
                //$(this).closest('form').submit();
            })

        }).catch(function (res) {

        })
    }
}

let globalUtils = {
    init: function () {
        $('.menu_btn').click(function () {
            $('.menu').animate({width: 'toggle', right: '0'}, 'slow');
        });

        //カートに商品の数量を取得する
        axios.post('/cart/getProductNum').then(function (res) {
            const {status, data} = res.data;
            if (status === 200) {
                if (data) {
                    $('#badge').text(data).show();
                }
            }
            if (status === 200) {

            }
        }).catch(function () {

        })
    }
}

let payment = {
    init: function () {

        $(':radio').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        //代引き
        $('#chkPayment').on('ifChecked', function () {
            $('.deliveryCash').removeClass('hide');
            $('.credit').addClass('hide');
            $('#payment').val(1);
        })

        //クレジット
        $('#chkCredit').on('ifChecked', function () {
            $('.deliveryCash').addClass('hide');
            $('.credit').removeClass('hide');
            $('#payment').val(2);
        })

        if ($('#chkPayment')[0].checked) {
            $('.deliveryCash').removeClass('hide');
            $('.credit').addClass('hide');
            $('#payment').val(1);
        }

        if ($('#chkCredit')[0].checked) {
            $('.deliveryCash').addClass('hide');
            $('.credit').removeClass('hide');
            $('#payment').val(2);
        }

        //
        $('.takeoutRule').on('ifChecked', function(event){
            $('#takeoutId').val(this.value)
            $('#btnConfirm').removeAttr('disabled')
            $(this).closest('tr').find('.delivery_date').removeAttr('disabled')


        })


        //基本情報
        let form = $(".frmConsignee");
        form.validate({
            errorPlacement: function errorPlacement(error, element) {
                element.before(error);
            }
        });

        function checkTakeOutDate(obj){
            let value = obj.value;
            let takeDate = obj.dataset.take_date
            let startTime = obj.dataset.start_time
            let endTime = obj.dataset.end_time
            let result = true;

            if(new moment(value) - new moment(takeDate + ' ' + startTime) < 0){
                $(obj).parent().find('.error').text('货物を受け取る時間は開始時間より小さくしてはいけません');
                $(obj).addClass('error');
                return false
            }else{
                $(obj).parent().find('.error').text('');
                $(obj).removeClass('error');
                result = true;
            }

            if(new moment(value) - new moment(takeDate + ' ' + endTime) > 0){
                $(obj).parent().find('.error').text('货物を受け取る時間は終了時間より大きくなってはいけません');
                $(obj).addClass('error');
                return false
            }else{
                $(obj).parent().find('.error').text('');
                $(obj).removeClass('error');
                result = true;
            }

            return result;
        }


        //代引きの支払い
        $('#btnConfirm').on('click', function () {
            let id = form.find('#id').val();

            let inpt = $('.takeoutRule:checked').closest('tr').find('.delivery_date')[0]

            if (form.valid() && checkTakeOutDate(inpt)) {
                form.ajaxForm({
                    beforeSend: function () {
                    },
                    success: function (res) {
                        if (res.status === 200) {
                            toastr.success(res.message);
                            setTimeout(function () {
                                location.href = "/paySuccess?orderId=" + id + '&payment=1';
                            }, 300)
                        }

                        if(res.status === 500){
                            toastr.error(res.message);
                            //更新一下时间带
                            setTimeout(function () {
                                location.href = '/dashboard';
                            }, 2000)
                        }
                    }, error: function (res) {
                        toastr.error('注文を失敗しました！');
                        setTimeout(function () {
                            location.href = "/payFailed?orderId=" + id + '&payment=1';
                        }, 300)
                    }
                })
            }
            form.submit();
        })

        //クレジットの支払い
        $('#btnCredit').on('click', function () {

            let id = form.find('#id').val();

            if (form.valid()) {
                let frmCredit = $('#frmCredit');
                frmCredit.find('#telno').val($('#tel').val());

                form.ajaxForm({
                    beforeSend: function () {
                        $('#paymentConfirm').modal('show');
                    },
                    success: function (res) {
                        if (res.status === 200) {
                            //クレジット画面へ遷移
                            frmCredit.submit();
                        }
                    }, error: function (res) {
                        toastr.error('注文を失敗しました！');
                        setTimeout(function () {
                            location.href = "/payFailed?orderId=" + id + '&payment=2';
                        }, 300)
                    }
                })
            }
            form.submit();
        })

        $.extend($.fn.datetimepicker.dates , {
            ja: {
                days: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日', '日曜日'],
                daysShort: ['日', '月', '火', '水', '木', '金', '土', '日'],
                daysMin: ['日', '月', '火', '水', '木', '金', '土', '日'],
                months: ['1月', '2月', '3月', '4月', '5月', '6月','7月', '8月', '9月', '10月', '11月', '12月'],
                monthsShort: ['1月', '2月', '3月', '4月', '5月', '6月','7月', '8月', '9月', '10月', '11月', '12月']
            }
        });

        $('.delivery_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            locale: 'ja',
            tooltips: {
                close: '閉じる',
                selectMonth: '月を選択',
                prevMonth: '前月',
                nextMonth: '次月',
                selectYear: '年を選択',
                prevYear: '前年',
                nextYear: '次年',
                selectTime: '時間を選択',
                selectDate: '日付を選択',
                prevDecade: '前期間',
                nextDecade: '次期間',
                selectDecade: '期間を選択',
                prevCentury: '前世紀',
                nextCentury: '次世紀'
            },
            showClose: true
        })

            .on('dp.change',function(date,oldDate){
                checkTakeOutDate(this);
                $('#delivery_date').val(this.value)
        })

    }
}

let order = {
    //注文キャンセル
    init: function () {
        $('.btn-cancel').on('click', function () {
            let id = this.dataset.id;
            axios.post('/cancelOrder', {
                id: id
            }).then(function (res) {
                let {data, status} = res.data;
                if (status === 200) {
                    toastr.success('注文キャンセルされました');
                    setTimeout(function () {
                        location.reload();
                    }, 300)
                }

            }).catch(function (res) {

            })
        })
    }
}
