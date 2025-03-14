// $(document).click('.elementor-button', function () {
//     console.log(1)
//     console.log(document.getElementsByClassName("elementor-message-danger"))
//     // document.getElementsByClassName("elementor-message-danger")
// })

jQuery(document).ready(function ($) {
    $('.elementor-button').click(function (e) {
        let node = $(e.target);
        let hasClassSendForm = node.parents('.send-form');
        let hasClassSendFormBangGia = node.parents('.form-bang-gia');
        let hasClassSendFormPopup = node.parents('.form-popup');
        let count = 0; // Biến đếm
        if (hasClassSendForm.length > 0) {
            let intervalId = setInterval(function () {
                console.log(count)
                count++;
                // Tăng biến đếm lên 1
                // Kiểm tra nếu đã gọi hàm 10 lần
                if (count >= 200) { // Nếu đã gọi hàm 10 lần, dừng việc gọi lại hàm
                    clearInterval(intervalId);
                } else {
                    let nodeMess = node.parents('.elementor-element').find(".form-message-custom")
                    if (hasClassSendForm.find('[aria-invalid="true"]').length > 0) {
                        var messageError = hasClassSendForm.find(".elementor-message-danger")[0].innerHTML
                        nodeMess.attr("style", "background:red;color: white;align-items: center;")
                        document.getElementById("form-message-custom").value = '';
                        document.getElementById("form-message-custom").innerHTML = messageError;
                        clearInterval(intervalId);
                    } else {
                        if (hasClassSendForm.find('.elementor-message-success')[0]) {
                            var messageError = hasClassSendForm.find(".elementor-message-success")[0].innerHTML
                            nodeMess.attr("style", "background:green;color: white;align-items: center;")
                            document.getElementById("form-message-custom").value = '';
                            document.getElementById("form-message-custom").innerHTML = messageError;
                            clearInterval(intervalId);
                        }
                    }
                }
            }, 100);
        } else if (hasClassSendFormBangGia.length > 0) {
            let intervalId = setInterval(function () {
                count++;
                // Tăng biến đếm lên 1
                // Kiểm tra nếu đã gọi hàm 10 lần
                if (count >= 200) { // Nếu đã gọi hàm 10 lần, dừng việc gọi lại hàm
                    clearInterval(intervalId);
                } else {
                    let nodeMess = node.parents('.section-content').find(".form-bang-gia-message-custom")
                    if (hasClassSendFormBangGia.find('[aria-invalid="true"]').length > 0) {
                        var messageError = hasClassSendFormBangGia.find(".elementor-message-danger")[0].innerHTML
                        nodeMess.attr("style", "background:red;color: white;align-items: center;text-align: center;")
                        nodeMess[0].value = '';
                        nodeMess[0].innerHTML = messageError;
                        clearInterval(intervalId);
                    } else {
                        if (hasClassSendFormBangGia.find('.elementor-message-success')[0] != undefined) {
                            var messageError = hasClassSendFormBangGia.find('.elementor-message-success')[0].innerHTML
                            console.log(messageError)
                            nodeMess.attr("style", "background:green;color: white;align-items: center;text-align: center;")
                            nodeMess[0].value = '';
                            nodeMess[0].innerHTML = messageError;
                            clearInterval(intervalId);
                        }
                    }
                }
            }, 100);
        }else if (hasClassSendFormPopup.length > 0) {
            let intervalId = setInterval(function () {
                count++;
                // Tăng biến đếm lên 1
                // Kiểm tra nếu đã gọi hàm 10 lần
                if (count >= 200) { // Nếu đã gọi hàm 10 lần, dừng việc gọi lại hàm
                    clearInterval(intervalId);
                } else {
                    let nodeMess = node.parents('.elementor-element').find(".message-form-bang-gia-popup")
                    if (hasClassSendFormPopup.find('[aria-invalid="true"]').length > 0) {
                        var messageError = hasClassSendFormPopup.find(".elementor-message-danger")[0].innerHTML
                        nodeMess.attr("style", "background:red;color: white;align-items: center;text-align: center;")
                        nodeMess[0].value = '';
                        nodeMess[0].innerHTML = messageError;
                        clearInterval(intervalId);
                    } else {
                        if (hasClassSendFormPopup.find('.elementor-message-success')[0] != undefined) {
                            var messageError = hasClassSendFormPopup.find('.elementor-message-success')[0].innerHTML
                            nodeMess.attr("style", "background:green;color: white;align-items: center;text-align: center;")
                            nodeMess[0].value = '';
                            nodeMess[0].innerHTML = messageError;
                            clearInterval(intervalId);
                        }
                    }
                }
            }, 100);
        }
    })

})
