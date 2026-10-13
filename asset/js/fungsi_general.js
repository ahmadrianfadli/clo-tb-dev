/* Message Event Listener */
let messageEventListener = {};

function processDialog() {
    return bootbox.dialog({
        message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Sedang memproses. Harap tunggu...</div>',
        show: false,
        animate: false,
    });
}

window.addEventListener("message", function (event) {
    const key = e.message ? 'message' : 'data';
    const data = e[key];

    if (messageEventListener.hasOwnProperty(event.data)) {
        if (typeof messageEventListener[event.data] == 'function') {
            messageEventListener[event.data]();
        }
    }
}, false);

/*---------------------- Formulir Laporan Tindakan ----------------------*/
function formulirLaporanTindakan() {
    $('#modalLaporanTindakanBody').html('<iframe frameborder="0" style="width: 100%; height: 800px; margin-left: auto; margin-right: auto;" src="' + clo_tools_base_url + '/laporan-tindakan?episode_id=' + idEpisode + '&user_id=' + idUser + '"></iframe>');
    $('#modalLaporanTindakan').modal('show');
}

/*---------------------- Formulir Hasil Pemeriksaan Dengan Alat ----------------------*/
function formulirHasilPemeriksaanDenganAlat() {
    $('#modalLaporanPenggunaanAlatBody').html('<iframe frameborder="0" style="width: 100%; height: 800px; margin-left: auto; margin-right: auto;" src="' + clo_tools_base_url + '/laporan-hasil-pemeriksaan-dgn-alat?episode_id=' + idEpisode + '&user_id=' + idUser + '"></iframe>');
    $('#modalLaporanPenggunaanAlat').modal('show');
}

/*---------------------- Formulir Permintaan Ranap ----------------------*/
function formulirPermintaanRanap() {
    $('#modalPermintaanRanapBody').html('<iframe frameborder="0" style="width: 100%; height: 800px; margin-left: auto; margin-right: auto;" src="' + clo_tools_base_url + '/surat-permintaan-ranap?episode_id=' + idEpisode + '&user_id=' + idUser + '"></iframe>');
    $('#modalPermintaanRanap').modal('show');
}

/*---------------------- Formulir Permintaan Ranap ----------------------*/
function formulirPermintaanRanapBayi() {
    $('#modalPermintaanRanapBayiBody').html('<iframe frameborder="0" style="width: 100%; height: 800px; margin-left: auto; margin-right: auto;" src="' + clo_tools_base_url + '/surat-permintaan-ranap-bayi?episode_id=' + idEpisode + '&user_id=' + idUser + '"></iframe>');
    $('#modalPermintaanRanapBayi').modal('show');
}

/*---------------------- Surat Kontrol dan Rujukan Internal ----------------------*/
function suratKontrol() {
    //setTimeout(() => window.open('http://simrs.rsudpm.local/clo_tools/index.php/surat-kontrol-bpjs?episode_id=' + idEpisode + '&user_id=' + idUser, 'Surat Kontrol', windowFeatures), 1000);

    $('#suratKontrolBody').find('iframe').get(0).src = clo_tools_base_url + '/surat-kontrol-bpjs?episode_id=' + idEpisode + '&user_id=' + idUser;
    $('#suratKontrolFrame').css('height', $('#suratKontrolBody').css('height'));
    $('#suratKontrolModal').modal('show');

}

function suratRujukanInternal() {
    //setTimeout(() => window.open(clo_tools_base_url + '/surat-rujukan-internal-bpjs?episode_id=' + idEpisode + '&user_id=' + idUser, 'Surat Rujukan Internal', windowFeatures), 1000);

    $('#suratRujukanInternalBody').find('iframe').get(0).src = clo_tools_base_url + '/surat-rujukan-internal-bpjs?episode_id=' + idEpisode + '&user_id=' + idUser;
    $('#suratRujukanInternalFrame').css('height', $('#suratRujukanInternalBody').css('height'));
    $('#suratRujukanInternalModal').modal('show');

}

/*---------------------- Formularium ----------------------*/
function openFormularium() {
    $('#notifFormularium2021').modal('show');
}

$(document).ready(function () {
    let $docContainer = $('#notifFormularium2021Body'),
        $docElement = $('#doc'),
        $btnCloseNotif = $('#btn-close-notif'),
        docHeight = $docElement.get(0).clientHeight,
        $checkNotifFormularium = $('#check-notif-formularium');

    $btnCloseNotif.prop('disabled', true);
    $checkNotifFormularium.prop('disabled', true);

    $docContainer.scroll(function (e) {
        // console.log('Scroll height: ' + $docContainer.get(0).scrollTop, 'Doc height: ' + docHeight);

        if ($docContainer.get(0).scrollTop >= (docHeight - 1000)) {
            // alert('Reach bottom of the page');
            $btnCloseNotif.prop('disabled', false);
            $checkNotifFormularium.prop('disabled', false);
        }
    });

    $docContainer.get(0).scrollTop = 0;

    function setDisableNotif() {
        $docElement.css('height', '540px');
        $btnCloseNotif.prop('disabled', false).attr('has-read', '1').html('Tutup');
    }

    $.ajax({
        url: base_url + 'index.php/login_controller/cekNotifikasi',
        type: 'GET',
        dataType: 'json',
        data: {},
        success: function (res) {
            if (res.status === 0) {
                $('#notifFormularium2021').modal('show');
            } else {
                setDisableNotif();
            }
            docHeight = $docElement.get(0).clientHeight;
        }
    });

    $btnCloseNotif.on('click', function (e) {
        e.preventDefault();

        let hasRead = $(e.currentTarget).attr('has-read');

        if (hasRead !== undefined) {
            $.ajax({
                url: base_url + 'index.php/login_controller/disabledNotif',
                type: 'GET',
                dataType: 'json',
                data: {},
                success: function (res) {
                    $('#notifFormularium2021').modal('hide');
                    setDisableNotif();
                }
            });
        }
    });
});

/*---------------------- Load Data Master ----------------------*/
function loadDataMaster(callback) {
    $.ajax({
        url: base_url + 'index.php/masterdata_controller/loadDataMaster/',
        type: 'GET',
        dataType: 'json',
        data: {},
        success: function (res) {
            dataMasterResume = res;
            callback();
        }
    });
}

/*---------------------- Resume Medis ----------------------*/
function showModalIsianResume() {
    $('#modalResumeBody').html('<iframe frameborder="0" style="width: 100%; height: 800px; margin-left: auto; margin-right: auto;" src="' + clo_tools_base_url + '/resume-rajal?episode_id=' + idEpisode + '&user_id=' + idUser + '#resume-medis"></iframe>');
    $('#modalResume').modal('show');
}

function transferDiagTindak(episodeID) {
    $.ajax({
        url: clo_tools_base_url + '/resume-rajal/transferDiagTindak/' + episodeID,
        type: 'GET',
        dataType: 'json',
        success: function (res) {

        },
        complete: function () {

        }
    });
}

messageEventListener['onSuccessSaveResume'] = function () {
    $('#modalResume').modal('hide');
    updateSoap();
}

messageEventListener['onFailSaveResume'] = function () {
    $('#modalResume').modal('hide');
}

/*---------------------- Assesmen Awal ----------------------*/
let forceWajibAssesmenDokter = false;

messageEventListener['closeModalAssesmenAwal'] = messageEventListener['onFailSaveAssesmenDokter'] = function () {
    $('#modalAssesmenAwalRajal').modal('hide');

    cekAssesmenAwal(function (isAdaAssesmenAwal, isAdaAssesmenAwalDokter) {
        if (forceWajibAssesmenDokter) {
            if (!isAdaAssesmenAwalDokter) {
                getAssesmenAwal();
            }
        }
    });
}

messageEventListener['onSuccessSaveAssesmenDokter'] = function () {
    $('#modalAssesmenAwalRajal').modal('hide');
}

function cekAssesmenAwal(callback) {
    $.ajax({
        url: base_url + 'index.php/general_controller/cek_assesmen_awal',
        type: 'POST',
        dataType: 'json',
        data: {
            idEpisode: idEpisode,
        },
        beforeSend: function () {
            $('#list-menu-assesmen-awal').hide();
        },
        success: function (res) {
            if (res.success) {
                $('#list-menu-assesmen-awal').show();

                if (res.adaAssesmenAwalDokter) {
                    $('#btn-close-model-assesmen-awal').prop('disabled', false);
                } else {
                    $('#btn-close-model-assesmen-awal').prop('disabled', forceWajibAssesmenDokter);
                }

                if (callback !== undefined) {
                    if (typeof callback == 'function') {
                        callback(res.adaAssesmenAwal, res.adaAssesmenAwalDokter);
                    }
                }
            }

        },
        complete: function () {

        }
    });
}

function getAssesmenAwal() {
    $('#modalAssesmenAwalRajalBody').html('<iframe frameborder="0" style="width: 100%; height: 640px; margin-left: auto; margin-right: auto;" src="' + clo_tools_base_url + '/asesmen-dokter?episode_id=' + idEpisode + '&user_id=' + idUser + '#form-dokter"></iframe>');
    $('#modalAssesmenAwalRajal').modal('show');
}

/*---------------------- Assesmen Awal Geriatri ----------------------*/
function cekAssesmenAwalGeriatri(callback) {
    $.ajax({
        url: base_url + 'index.php/general_controller/cek_assesmen_geriatri',
        type: 'POST',
        dataType: 'json',
        data: {
            idEpisode: idEpisode,
        },
        beforeSend: function () {
            $('#list-menu-assesmen-awal-geriatri').hide();
        },
        success: function (res) {
            if (res.success) {
                $('#list-menu-assesmen-awal-geriatri').show();

                if (callback !== undefined) {
                    if (typeof callback == 'function') {
                        callback(res.adaAssesmenAwalGeriatri, res.adaAssesmenAwalDokterGeriatri);
                    }
                }
            }

        },
        complete: function () {

        }
    });
}

function getAssesmenAwalGeriatri() {
    $('#modalAssesmenAwalGeriatriBody').html('<iframe frameborder="0" style="width: 100%; height: 640px; margin-left: auto; margin-right: auto;" src="' + clo_tools_base_url + '/asesmen-geriatri?episode_id=' + idEpisode + '&user_id=' + idUser + '"></iframe>');
    $('#modalAssesmenAwalGeriatri').modal('show');
}

/*----------------------  One Day Care ----------------------*/
function oneDayCareStatus() {
    $.ajax({
        url: base_url + 'index.php/general_controller/getOneDayCare',
        type: 'POST',
        dataType: 'json',
        data: {
            idEpisode: idEpisode,
        },
        success: function (res) {
            if (res.odc) {
                isOneDayCare = true;
                $('#btnOneDayCare').addClass('btn-warning');
                $('#status-odc').html('<img src="../asset/image/medi/Select.png" style="height:16px; width:16px">');
            } else {
                isOneDayCare = false;
                $('#btnOneDayCare').removeClass('btn-warning');
                $('#status-odc').html('');
            }
        },
        complete: function () {

        }
    });
}

function setOneDayCare() {
    let loadingBox = processDialog();

    $.ajax({
        url: base_url + 'index.php/general_controller/setOneDayCare',
        type: 'POST',
        dataType: 'json',
        data: {
            idEpisode: idEpisode,
            flag: isOneDayCare ? '0' : '1', // invert nilainya
        },
        beforeSend: function () {
            loadingBox.modal('show');
        },
        success: function (res) {
            loadingBox.modal('hide');
            let message = (isOneDayCare ? ' menonaktifkan ' : ' mengaktifkan ') + ' One Day Care';

            if (res.success) {
                message = 'Berhasil ' + message;
            } else {
                message = 'Gagal ' + message;
            }
            bootbox.alert(message);
        },
        complete: function () {
            loadingBox.modal('hide');
            loadingBox.remove();
            oneDayCareStatus();
        }
    });
}

function labModal2() {
    var iframe = '<iframe frameborder="0" style="width: 100%; height: 800px; margin-left: auto; margin-right: auto;" src="' + clo_url + 'tools/lab?episode_id=' + idEpisode + '&user_id=' + idUser + '&poliid=' + idPoli + '"></iframe>';
    $('#formModal7').modal('show');
    $('#formModal7Body').html(iframe);
    $("#title_formModal7").html('Permintaan Laboratorium');
}
window.addEventListener('message', function (event) {

    if (event.data && event.data.type === 'modalContentClose') {
        $('#formModal7').modal('hide');
        getTotal();
    }
    if (event.data && event.data.type === 'modalContentSave') {
        $('#formModal7').modal('hide');
        getTotal();
    }
});

function radModal2() {
    var iframe = '<iframe frameborder="0" style="width: 100%; height: 800px; margin-left: auto; margin-right: auto;" src="' + clo_url + 'tools/rad?episode_id=' + idEpisode + '&user_id=' + idUser + '&poliid=' + idPoli + '"></iframe>';
    $('#formModal7').modal('show');
    $('#formModal7Body').html(iframe);
    $("#title_formModal7").html('Permintaan Radiologi');
}