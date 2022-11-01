// 小视图里的内容隐藏
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) {
            return pair[1];
        }
    }
    return (false);
}

// 判断是否为iframe打开
var isShow = getQueryVariable('show');

if (isShow == 1) {
    $('.main-header').hide()
    $('.main-sidebar').hide()
    $('.content-header').hide()
    $('.main-footer').hide()
    $('.content').css('style', 'padding:0px;')
    $('#export-box').hide()
    $('.content-wrapper').css('style', '')
} else {
    // 默认打开侧边栏
    setTimeout(function () {
        if ($('.skin-blue-light.sidebar-mini').hasClass('sidebar-collapse')) {
            $('.sidebar-toggle').click()
        }
    }, 3000)
}

// line的数组
var lineInputs = [
    '#xiapi_waybill_no',
    '#order_no',
    '#goods_describe',
    '.china_express_information',
    '#recipients',
    'input[name="internet_channel_id"]',
    '.internet_channel_id',
    '.file-caption-name',
]
var offlineInputs = [
    '#goods_describe',
    '#recipients',
    '#phone',
    '#address',
    '#collecting_amount'
]

// 切换订单类型的时候清空其它内容
$(document).on('click', '.card-group', function () {
    var radioValue = $('.panel.active').find('input[name="type"]').val()
    sessionStorage.setItem('order_type', radioValue)

    if (radioValue == 'line') {
        resetInput(offlineInputs)
    }
    if (radioValue == 'offline') {
        // $('.close').click()
        resetInput(lineInputs)
    }
})

function resetInput(inputs) {
    for (var key in inputs) {
        $(inputs[key]).val("")
    }
    // $('.btn-group.pull-left button[type="reset"] ').click()
}

var url = window.location.href
setInterval(function () {
    // 提交按钮ui调整
    $('button[type="submit"]').parent().removeClass('pull-right')
    $('button[type="submit"]').parent().addClass('pull-left')
    $('button[type="submit"]').css('margin-right', '100px')



    // 如果上次提交的是线下订单，则继续填写线下订单 TODO 隐藏
    // if (sessionStorage.getItem('order_type') == 'offline') {
    //     if (!$('.panel').eq(1).hasClass('active')) {
    //         $('.panel').eq(1).click()
    //     }
    // }
}, 1000)

setInterval(function () {
    // 继续创建按钮选择
    var isCreatePage = url.indexOf('create')
    if ($('.icheckbox_minimal-blue[aria-checked=false]').length > 0 && isCreatePage != -1) {
        $('.icheckbox_minimal-blue[aria-checked=false]').click()
        // $('.icheckbox_minimal-blue[aria-checked=false]').css('opacity', '0')
    }
}, 5000)

$(document).on('click', 'button[type="submit"]', function (event) {

    var radioValue = $('.panel.active').find('input[name="type"]').val()

    if (radioValue == 'line') {
        // pdf验证 TODO 先关掉
        if ($('.file-input .file-size-info').length == 0) {
            alert('请先上传pdf面单')
            event.preventDefault();
        }

        // 国内快递信息、虾皮订单号、虾皮运单号不能有重复 TODO 虾皮运单号未处理
        var orderNo = $('#order_no').val()
        var chinaExpressInformation = $('.china_express_information').val()
        var xiapiWaybillNo = $('#xiapi_waybill_no').val()

        if (orderNo == chinaExpressInformation || xiapiWaybillNo == chinaExpressInformation || xiapiWaybillNo == orderNo) {
            alert('国内快递信息、虾皮订单号、面單條碼不能有重复')
            event.preventDefault();

        }
    }

    // 上午9~到下午5点才可以正常录单

    // var nowHours = new Date().getHours();
    // if (url.indexOf('/orders/create') != -1) {
    //     if (nowHours < 9 || nowHours >= 17)  {
    //         alert('非录单时间，上午9~到下午5点才录单')
    //         event.preventDefault()
    //     }
    // }


})