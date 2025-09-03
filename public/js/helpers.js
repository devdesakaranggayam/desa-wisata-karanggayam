const onlyNumber = (string) => {
    return !string.match(/[^0-9]/g)
}

const moneyIndoToDecimal = (value) => {
    if (value=='') {
        value='0,00'
    }

    const splitComma = value.split(",");
    const front = parseInt(splitComma[0].replace(/\./g, ''));
    const result = front+"."+splitComma[1];
    
    return parseFloat(result);
}

const decimalToMoneyIndo = (value, decimalSeparator, thousandsSeparator, nDecimalDigits) => {
    const num = parseFloat(value == '' ? '0,00' : value); //convert to float
    //default values
    decimalSeparator = decimalSeparator || ',';
    thousandsSeparator = thousandsSeparator || '.';
    nDecimalDigits = nDecimalDigits == null ? 2 : nDecimalDigits;

    const fixed = num.toFixed(nDecimalDigits); //limit or add decimal digits
    //separate begin [$1], middle [$2] and decimal digits [$4]  
    const parts = new RegExp('^(-?\\d{1,3})((?:\\d{3})+)(\\.(\\d{' + nDecimalDigits + '}))?$').exec(fixed);

    if (num == 0) {
        return fixed.replace('.', decimalSeparator);
    }

    if (parts) { //num >= 1000 || num < = -1000  
        return parts[1] + parts[2].replace(/\d{3}/g, thousandsSeparator + '$&') + (parts[4] ? decimalSeparator + parts[4] : '');
    } else {
        return fixed.replace('.', decimalSeparator);
    }
}

const randomString = (length = 8) => {
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    const charactersLength = characters.length;
    let counter = 0;
    while (counter < length) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
        counter += 1;
    }
    return result;
}

const displayErrMessages = (selector, data) => {
    let errs = ''
    data.forEach(element => {
        errs += `<div>${element.message}</div>`
    });
    const result = `
        <div class="alert alert-danger">
            ${errs}
        </div>
    `
    $(selector).html(result)
}

const saveAjaxFormData = ({url,form,successCallback,errorCallback, async}) => {
    const formData = new FormData(form[0])

    let success, error

    if (successCallback) {
        success = successCallback
    } else {
        success = (res) => {
            toastr.success(res.message)
            loadingCover(false)
        }
    }

    if (errorCallback) {
        error = errorCallback
    } else {
        error = (error) => {
            if (error.status == 400) {
                const res = JSON.parse(error.responseText)

                res.validations.forEach(element => {
                    toastr.error(element.message)
                });

                displayErrMessages('.err-wrapper',res.validations)
            } else {
                toastr.error('Terjadi Kesalahan')
            }
            loadingCover(false)
        }
    }

    $.ajax({
        url,
        data:formData,
        type: 'POST',
        async: async || false,
        timeout: 10000,
        processData: false,
        contentType: false,
        success: success,
        error: error
    })
}

const spinner = () => {
    return `
    <div class="text-center">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    `
}

const fetchFailed = () => {
    return `
    <div class="text-center mb-3">
        Tidak dapat memuat data
    </div>
    `
}

const loadingCover = (value) => {
    $("#loadingOverlay").hide();
    if (value) {
        $("#loadingOverlay").show();
    }
}

const getFormOffcanvas = (url) => {
    $.ajax({
        type: 'GET',
        url,
        dataType: "html",
        beforeSend: ()=>{
            $('#offcanvasBody').html(`<div class="py-3">${spinner()}</div>`)
        },
        success: function (response) {
            $("#offcanvasBody").html(response)
        },
        error: (err) => {
            $("#offcanvasBody").html(fetchFailed())
        }
    });
}

const setLoading = (value, selector, label) => {
    if (value) {
        $(selector).prop('disabled', true)
        $(selector).html(`${label}`)
    } else {
        $(selector).prop('disabled', false)
        $(selector).html(`${label}`)
    }
}

const customSelect2 = ({placeholder, element, url, dropdownParent}) => {
    element.select2({
        placeholder: placeholder,
        theme: 'bootstrap-5',
        dropdownParent: dropdownParent || null,
        ajax: {
            url: url,
            dataType: 'json',
            data: function (params) {
                let query = {
                    search: params.term,
                    page: params.page
                }
                return query;
            },
            delay: 250,
            processResults: function (data) {
                return data;
            },
            cache: true
        }
    });
}

const hitungHargaDiskon = ({hargaPerItem, jumlahDiskon, jumlahItem, tipeDiskon}) => {
    // pastikan harga sudah di covert ke float
    const subtotal = hargaPerItem*jumlahItem

    let diskonPerItem = jumlahDiskon;
    let label = `-Rp. ${decimalToMoneyIndo(jumlahDiskon*jumlahItem)}`
    
    // Hitung diskon per item
    if (tipeDiskon=='persen') {
        const persentaseDiskon = parseInt(jumlahDiskon)
        diskonPerItem = hargaPerItem * (persentaseDiskon / 100);
        label = `- ${persentaseDiskon}%`
    }
    
    // Hitung harga setelah diskon per item
    const hargaSetelahDiskonPerItem = hargaPerItem - diskonPerItem;
    
    // Hitung total harga setelah diskon
    const total = hargaSetelahDiskonPerItem * jumlahItem;

    // Mengembalikan total harga setelah diskon
    return { subtotal, total, label};
}

const getJumlahDiskon = (jumlahDiskon, tipeDiskon) => {
    let result = moneyIndoToDecimal((jumlahDiskon||"0,00"))

    if(tipeDiskon === 'persen') {
        result = parseInt((jumlahDiskon||"0"))
    }

    return result
}

const getDiskonLabel = (jumlahDiskon, tipeDiskon) => {
    const diskon = getJumlahDiskon(jumlahDiskon,tipeDiskon)
    let result = `-Rp. ${decimalToMoneyIndo(diskon)}`
    
    if(tipeDiskon === 'persen') {
        result = `- ${diskon} %`
    }

    return result
}

const initDaterangePicker = (selector = '.daterange', args = {}) => {
    const options = {
        autoUpdateInput: false, // do not set value automatically
        locale: {
            format: 'DD MMM YYYY'
        },
        autoApply: true,
        alwaysShowCalendars: true,
        linkedCalendars: true,
    }

    $(selector).daterangepicker({options,...args}).on('apply.daterangepicker', function(ev, picker) {
        const now = moment();
        
        // Validate endDate is not in the future
        if (picker.endDate.isAfter(now, 'day')) {
            picker.setEndDate(now);
        }

        // Set the input value manually
        $(this).val(
            picker.startDate.format('DD MMM YYYY') + ' - ' + picker.endDate.format('DD MMM YYYY')
        );

        // Force calendar view to jump to the end date
        picker.leftCalendar.month = picker.startDate.clone();
        picker.rightCalendar.month = picker.endDate.clone();
        picker.updateCalendars();
    });

    // Optional: clear input on cancel
    $(selector).on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
}

const monthsIndo = () => {
    return {
        1: 'Januari', 
        2: 'Februari', 
        3: 'Maret', 
        4: 'April', 
        5: 'Mei', 
        6: 'Juni', 
        7: 'Juli', 
        8: 'Agustus',
        9: 'September', 
        10: 'Oktober', 
        11: 'November', 
        12: 'Desember'
    }
}

const tglIndo = (sqlDateString) => {
    if (!sqlDateString) return '-'

    const [year, month, day] = sqlDateString.split('-').map(Number);
    return `${day} ${monthsIndo()[month]} ${year}`
}

const downloadFile = (url, filename) => {
    $.ajax({
        url: url,
        method: 'GET',
        xhrFields: {
            responseType: 'blob' // Set the response type to blob
        },
        beforeSend: function (param) { 
            toastr.info('File akan segera didownload')
        },
        async:true,
        success: function(data) {
            const blob = new Blob([data]);
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = filename;
            link.click();
            loadingCover(false)
        },
        error: function (err) { 
            toastr.error('Terjadi Kesalahan')
        }
    });
}